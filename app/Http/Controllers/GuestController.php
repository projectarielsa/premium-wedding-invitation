<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\GuestCategory;
use App\Http\Requests\ImportGuestRequest;
use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Controller for managing wedding guests within an invitation.
 *
 * Handles guest CRUD, bulk import from CSV/Excel, WhatsApp link
 * generation, and bulk actions like delete and category updates.
 */
class GuestController extends Controller
{
    /**
     * Display a listing of guests for the invitation.
     */
    public function index(Request $request, Invitation $invitation): View
    {
        $this->authorize('viewAny', [Guest::class, $invitation]);

        $query = $invitation->guests()->with('rsvp');

        // Apply filters
        if ($request->filled('category')) {
            $category = GuestCategory::tryFrom($request->input('category'));
            if ($category) {
                $query->inCategory($category);
            }
        }

        if ($request->filled('status')) {
            match ($request->input('status')) {
                'responded' => $query->responded(),
                'pending' => $query->pending(),
                'whatsapp_sent' => $query->whatsappSent(),
                'whatsapp_pending' => $query->whatsappPending(),
                'checked_in' => $query->checkedIn(),
                default => null,
            };
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_direction', 'desc');

        if ($sortBy === 'priority') {
            $query->orderByPriority();
        } else {
            $query->orderBy($sortBy, $sortDir);
        }

        $guests = $query->paginate(25)->withQueryString();

        // Get category counts for filters
        $categoryCounts = $invitation->guests()
            ->select('category', DB::raw('count(*) as count'))
            ->groupBy('category')
            ->pluck('count', 'category');

        return view('guests.index', compact('invitation', 'guests', 'categoryCounts'));
    }



    /**
     * Store a newly created guest.
     */
    public function store(StoreGuestRequest $request, Invitation $invitation): RedirectResponse
    {
        $data = $request->validated();

        $invitation->guests()->create($data);

        return back()->with('success', 'Guest added successfully!');
    }

    /**
     * Update the specified guest.
     */
    public function update(
        UpdateGuestRequest $request,
        Invitation $invitation,
        Guest $guest
    ): RedirectResponse {
        // Verify guest belongs to invitation
        if ($guest->invitation_id !== $invitation->id) {
            abort(404);
        }

        $guest->update($request->validated());

        return back()->with('success', 'Guest updated successfully!');
    }

    /**
     * Remove the specified guest.
     */
    public function destroy(
        Request $request,
        Invitation $invitation,
        Guest $guest
    ): RedirectResponse {
        $this->authorize('delete', $guest);

        // Verify guest belongs to invitation
        if ($guest->invitation_id !== $invitation->id) {
            abort(404);
        }

        $guest->delete();

        return back()->with('success', 'Guest removed successfully!');
    }

    /**
     * Import guests from CSV/Excel file.
     */
    public function import(ImportGuestRequest $request, Invitation $invitation): RedirectResponse
    {
        $file = $request->file('file');
        $skipHeader = $request->boolean('skip_header', true);
        $columnMapping = $request->input('column_mapping', [
            'name' => 0,
            'phone_number' => 1,
            'whatsapp' => 2,
            'email' => 3,
            'category' => 4,
            'max_attendees' => 5,
            'notes' => 6,
        ]);

        $extension = strtolower($file->getClientOriginalExtension());
        $importedCount = 0;
        $errors = [];

        try {
            DB::beginTransaction();

            if (in_array($extension, ['csv', 'txt'])) {
                $result = $this->importFromCsv($file, $invitation, $skipHeader, $columnMapping);
            } else {
                // For Excel files, we'd need a package like PhpSpreadsheet
                // For now, return error for unsupported formats
                return back()->with('error', 'Excel import requires additional setup. Please use CSV format.');
            }

            $importedCount = $result['imported'];
            $errors = $result['errors'];

            DB::commit();

            $message = "Successfully imported {$importedCount} guests.";
            if (!empty($errors)) {
                $message .= ' ' . count($errors) . ' rows had errors.';
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }



    /**
     * Import guests from CSV file.
     */
    private function importFromCsv(
        $file,
        Invitation $invitation,
        bool $skipHeader,
        array $columnMapping
    ): array {
        $handle = fopen($file->getPathname(), 'r');
        $imported = 0;
        $errors = [];
        $rowNumber = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNumber++;

            if ($skipHeader && $rowNumber === 1) {
                continue;
            }

            try {
                $guestData = [
                    'name' => trim($row[$columnMapping['name'] ?? 0] ?? ''),
                    'phone_number' => trim($row[$columnMapping['phone_number'] ?? 1] ?? '') ?: null,
                    'whatsapp' => trim($row[$columnMapping['whatsapp'] ?? 2] ?? '') ?: null,
                    'email' => trim($row[$columnMapping['email'] ?? 3] ?? '') ?: null,
                    'category' => $this->parseCategory($row[$columnMapping['category'] ?? 4] ?? ''),
                    'max_attendees' => (int) ($row[$columnMapping['max_attendees'] ?? 5] ?? 2) ?: 2,
                    'notes' => trim($row[$columnMapping['notes'] ?? 6] ?? '') ?: null,
                ];

                // Skip if no name
                if (empty($guestData['name'])) {
                    $errors[] = "Row {$rowNumber}: Name is required.";
                    continue;
                }

                $invitation->guests()->create($guestData);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNumber}: " . $e->getMessage();
            }
        }

        fclose($handle);

        return ['imported' => $imported, 'errors' => $errors];
    }

    /**
     * Parse category string to enum value.
     */
    private function parseCategory(string $category): string
    {
        $category = strtolower(trim($category));

        $mapping = [
            'family' => GuestCategory::Family->value,
            'keluarga' => GuestCategory::Family->value,
            'friend' => GuestCategory::Friend->value,
            'teman' => GuestCategory::Friend->value,
            'vip' => GuestCategory::Vip->value,
            'colleague' => GuestCategory::Colleague->value,
            'rekan' => GuestCategory::Colleague->value,
            'neighbor' => GuestCategory::Neighbor->value,
            'tetangga' => GuestCategory::Neighbor->value,
        ];

        return $mapping[$category] ?? GuestCategory::Friend->value;
    }

    /**
     * Generate WhatsApp share link for a guest.
     */
    public function whatsappLink(
        Request $request,
        Invitation $invitation,
        Guest $guest
    ): JsonResponse {
        $this->authorize('sendWhatsapp', $guest);

        // Verify guest belongs to invitation
        if ($guest->invitation_id !== $invitation->id) {
            abort(404);
        }

        $link = $guest->whatsapp_share_link;

        if (!$link) {
            return response()->json([
                'success' => false,
                'message' => 'Guest does not have a WhatsApp number.',
            ], 422);
        }

        // Mark WhatsApp as generated (optional tracking)
        $guest->markWhatsappSent();

        return response()->json([
            'success' => true,
            'link' => $link,
            'guest_name' => $guest->name,
        ]);
    }



    /**
     * Bulk delete guests.
     */
    public function bulkDelete(Request $request, Invitation $invitation): RedirectResponse
    {
        $this->authorize('bulkManage', [Guest::class, $invitation]);

        $request->validate([
            'guest_ids' => ['required', 'array', 'min:1'],
            'guest_ids.*' => ['required', 'integer'],
        ]);

        $deleted = $invitation->guests()
            ->whereIn('id', $request->input('guest_ids'))
            ->delete();

        return back()->with('success', "{$deleted} guests deleted successfully!");
    }

    /**
     * Bulk update guest category.
     */
    public function bulkUpdateCategory(Request $request, Invitation $invitation): RedirectResponse
    {
        $this->authorize('bulkManage', [Guest::class, $invitation]);

        $request->validate([
            'guest_ids' => ['required', 'array', 'min:1'],
            'guest_ids.*' => ['required', 'integer'],
            'category' => ['required', 'string', \Illuminate\Validation\Rule::enum(GuestCategory::class)],
        ]);

        $updated = $invitation->guests()
            ->whereIn('id', $request->input('guest_ids'))
            ->update(['category' => $request->input('category')]);

        return back()->with('success', "{$updated} guests updated successfully!");
    }

    /**
     * Bulk mark WhatsApp as sent.
     */
    public function bulkMarkWhatsappSent(Request $request, Invitation $invitation): RedirectResponse
    {
        $this->authorize('bulkManage', [Guest::class, $invitation]);

        $request->validate([
            'guest_ids' => ['required', 'array', 'min:1'],
            'guest_ids.*' => ['required', 'integer'],
        ]);

        $updated = $invitation->guests()
            ->whereIn('id', $request->input('guest_ids'))
            ->update(['whatsapp_sent_at' => now()]);

        return back()->with('success', "{$updated} guests marked as WhatsApp sent!");
    }

    /**
     * Export guests to CSV.
     */
    public function export(Request $request, Invitation $invitation)
    {
        $this->authorize('viewAny', [Guest::class, $invitation]);

        $guests = $invitation->guests()->with('rsvp')->get();

        $filename = Str::slug($invitation->couple_name) . '-guests-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($guests) {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, [
                'Name', 'Phone Number', 'WhatsApp', 'Email', 'Category',
                'Max Attendees', 'Notes', 'RSVP Status', 'Attendance Count',
                'RSVP Message', 'Personal Link'
            ]);

            foreach ($guests as $guest) {
                fputcsv($handle, [
                    $guest->name,
                    $guest->phone_number,
                    $guest->whatsapp,
                    $guest->email,
                    $guest->category->label(),
                    $guest->max_attendees,
                    $guest->notes,
                    $guest->rsvp?->attendance_status?->label() ?? 'Pending',
                    $guest->rsvp?->attendance_count ?? 0,
                    $guest->rsvp?->message,
                    $guest->personalized_url,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
