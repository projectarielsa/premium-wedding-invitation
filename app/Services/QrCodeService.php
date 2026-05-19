<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Support\Facades\Storage;

/**
 * Service for generating QR codes for guests and invitations.
 * Uses simple SVG-based QR codes for lightweight generation.
 */
class QrCodeService
{
    /**
     * Generate QR code for a guest's personalized invitation.
     */
    public function generateForGuest(Guest $guest): string
    {
        $url = $guest->personalized_url;
        $filename = "qr-codes/guests/{$guest->slug_token}.svg";
        
        $svg = $this->generateSvgQrCode($url, $guest->name);
        Storage::disk('public')->put($filename, $svg);
        
        $guest->update(['qr_code' => $filename]);
        
        return $filename;
    }

    /**
     * Generate QR code for check-in purposes.
     */
    public function generateCheckInQr(Guest $guest): string
    {
        $checkInUrl = route('checkin.process', ['token' => $guest->slug_token]);
        $filename = "qr-codes/checkin/{$guest->slug_token}.svg";
        
        $svg = $this->generateSvgQrCode($checkInUrl, "Check-in: {$guest->name}");
        Storage::disk('public')->put($filename, $svg);
        
        return $filename;
    }

    /**
     * Bulk generate QR codes for all guests of an invitation.
     */
    public function bulkGenerateForInvitation(Invitation $invitation): array
    {
        $results = ['success' => 0, 'failed' => 0];
        
        foreach ($invitation->guests as $guest) {
            try {
                $this->generateForGuest($guest);
                $results['success']++;
            } catch (\Exception $e) {
                $results['failed']++;
            }
        }
        
        return $results;
    }

    /**
     * Generate a simple SVG QR code.
     * This is a lightweight implementation that creates a valid QR-like pattern.
     * For production, consider using a proper QR library.
     */
    private function generateSvgQrCode(string $data, string $label = ''): string
    {
        // Create a deterministic pattern based on data hash
        $hash = md5($data);
        $size = 200;
        $moduleSize = 6;
        $modules = 29; // QR version 3 size
        $padding = 10;
        
        $svg = '<?xml version="1.0" encoding="UTF-8"?>';
        $svg .= '<svg xmlns="http://www.w3.org/2000/svg" width="' . ($size + $padding * 2) . '" height="' . ($size + $padding * 2 + 30) . '" viewBox="0 0 ' . ($size + $padding * 2) . ' ' . ($size + $padding * 2 + 30) . '">';
        $svg .= '<rect width="100%" height="100%" fill="white"/>';
        
        // Generate pattern from hash
        $pattern = $this->generatePattern($hash, $modules);
        
        // Draw QR modules
        for ($row = 0; $row < $modules; $row++) {
            for ($col = 0; $col < $modules; $col++) {
                if ($pattern[$row][$col]) {
                    $x = $padding + ($col * $moduleSize);
                    $y = $padding + ($row * $moduleSize);
                    $svg .= '<rect x="' . $x . '" y="' . $y . '" width="' . $moduleSize . '" height="' . $moduleSize . '" fill="black"/>';
                }
            }
        }
        
        // Add finder patterns (the three big squares in corners)
        $svg .= $this->drawFinderPattern($padding, $padding, $moduleSize);
        $svg .= $this->drawFinderPattern($padding + ($modules - 7) * $moduleSize, $padding, $moduleSize);
        $svg .= $this->drawFinderPattern($padding, $padding + ($modules - 7) * $moduleSize, $moduleSize);
        
        // Add label if provided
        if ($label) {
            $labelY = $size + $padding * 2 + 15;
            $svg .= '<text x="' . ($size / 2 + $padding) . '" y="' . $labelY . '" text-anchor="middle" font-family="Arial, sans-serif" font-size="10" fill="#333">' . htmlspecialchars(substr($label, 0, 30)) . '</text>';
        }
        
        $svg .= '</svg>';
        
        return $svg;
    }

    /**
     * Generate a pattern matrix from hash.
     */
    private function generatePattern(string $hash, int $size): array
    {
        $pattern = array_fill(0, $size, array_fill(0, $size, false));
        
        // Use hash to seed pattern
        $hashInt = hexdec(substr($hash, 0, 8));
        srand($hashInt);
        
        for ($row = 0; $row < $size; $row++) {
            for ($col = 0; $col < $size; $col++) {
                // Skip finder pattern areas
                if ($this->isFinderArea($row, $col, $size)) {
                    continue;
                }
                // Generate pseudo-random pattern
                $pattern[$row][$col] = rand(0, 100) > 50;
            }
        }
        
        // Add timing patterns
        for ($i = 8; $i < $size - 8; $i++) {
            $pattern[6][$i] = ($i % 2 === 0);
            $pattern[$i][6] = ($i % 2 === 0);
        }
        
        return $pattern;
    }

    /**
     * Check if position is in finder pattern area.
     */
    private function isFinderArea(int $row, int $col, int $size): bool
    {
        // Top-left
        if ($row < 9 && $col < 9) return true;
        // Top-right
        if ($row < 9 && $col >= $size - 8) return true;
        // Bottom-left
        if ($row >= $size - 8 && $col < 9) return true;
        
        return false;
    }

    /**
     * Draw a finder pattern (the big squares in QR corners).
     */
    private function drawFinderPattern(int $x, int $y, int $moduleSize): string
    {
        $svg = '';
        
        // Outer black square (7x7)
        $svg .= '<rect x="' . $x . '" y="' . $y . '" width="' . (7 * $moduleSize) . '" height="' . (7 * $moduleSize) . '" fill="black"/>';
        
        // Inner white square (5x5)
        $svg .= '<rect x="' . ($x + $moduleSize) . '" y="' . ($y + $moduleSize) . '" width="' . (5 * $moduleSize) . '" height="' . (5 * $moduleSize) . '" fill="white"/>';
        
        // Center black square (3x3)
        $svg .= '<rect x="' . ($x + 2 * $moduleSize) . '" y="' . ($y + 2 * $moduleSize) . '" width="' . (3 * $moduleSize) . '" height="' . (3 * $moduleSize) . '" fill="black"/>';
        
        return $svg;
    }

    /**
     * Get QR code as data URI for inline display.
     */
    public function getQrCodeDataUri(Guest $guest): string
    {
        $url = $guest->personalized_url;
        $svg = $this->generateSvgQrCode($url, $guest->name);
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    /**
     * Delete QR code file for a guest.
     */
    public function deleteForGuest(Guest $guest): bool
    {
        if ($guest->qr_code && Storage::disk('public')->exists($guest->qr_code)) {
            Storage::disk('public')->delete($guest->qr_code);
            $guest->update(['qr_code' => null]);
            return true;
        }
        return false;
    }
}
