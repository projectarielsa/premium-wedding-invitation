<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Exception for invitation-related errors.
 */
class InvitationException extends Exception
{
    /**
     * The HTTP status code.
     */
    protected int $statusCode;

    /**
     * Create a new exception instance.
     */
    public function __construct(
        string $message = 'An invitation error occurred.',
        int $statusCode = 400,
        ?Exception $previous = null
    ) {
        parent::__construct($message, $statusCode, $previous);
        $this->statusCode = $statusCode;
    }

    /**
     * Get the HTTP status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Render the exception.
     */
    public function render(Request $request): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => true,
                'message' => $this->getMessage(),
            ], $this->statusCode);
        }

        return back()
            ->withInput()
            ->withErrors(['invitation' => $this->getMessage()]);
    }



    // =========================================================================
    // STATIC FACTORY METHODS
    // =========================================================================

    /**
     * Create exception for invitation not found.
     */
    public static function notFound(): static
    {
        return new static('Invitation not found.', 404);
    }

    /**
     * Create exception for invitation not published.
     */
    public static function notPublished(): static
    {
        return new static('This invitation is not published yet.', 403);
    }

    /**
     * Create exception for invitation archived.
     */
    public static function archived(): static
    {
        return new static('This invitation has been archived and cannot be modified.', 403);
    }

    /**
     * Create exception for unauthorized access.
     */
    public static function unauthorized(): static
    {
        return new static('You are not authorized to access this invitation.', 403);
    }

    /**
     * Create exception for invalid slug.
     */
    public static function invalidSlug(): static
    {
        return new static('The invitation URL is invalid.', 404);
    }

    /**
     * Create exception for guest not found.
     */
    public static function guestNotFound(): static
    {
        return new static('Guest not found.', 404);
    }

    /**
     * Create exception for invalid guest token.
     */
    public static function invalidGuestToken(): static
    {
        return new static('Invalid guest invitation link.', 404);
    }

    /**
     * Create exception for RSVP already submitted.
     */
    public static function rsvpAlreadySubmitted(): static
    {
        return new static('You have already submitted your RSVP.', 409);
    }

    /**
     * Create exception for RSVP disabled.
     */
    public static function rsvpDisabled(): static
    {
        return new static('RSVP is not enabled for this invitation.', 403);
    }

    /**
     * Create exception for gift section disabled.
     */
    public static function giftDisabled(): static
    {
        return new static('Gift section is not enabled for this invitation.', 403);
    }

    /**
     * Create exception for duplicate guest.
     */
    public static function duplicateGuest(): static
    {
        return new static('A guest with this phone number already exists.', 409);
    }

    /**
     * Create exception for import error.
     */
    public static function importError(string $details = ''): static
    {
        $message = 'Failed to import guests.';
        if ($details) {
            $message .= ' ' . $details;
        }

        return new static($message, 422);
    }

    /**
     * Create exception for event date in the past.
     */
    public static function eventDatePast(): static
    {
        return new static('Event date cannot be in the past.', 422);
    }

    /**
     * Create exception for max attendees exceeded.
     */
    public static function maxAttendeesExceeded(int $max): static
    {
        return new static("Maximum number of attendees ({$max}) exceeded.", 422);
    }
}
