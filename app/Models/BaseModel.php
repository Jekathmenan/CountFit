<?php

namespace CountFit\Models;

use InvalidArgumentException;

class BaseModel
{
    /**
     * Validates and sanitizes a string input safely.
     *
     * @param string $input The input string to validate.
     * @param int $minLength Minimum allowed length.
     * @param int $maxLength Maximum allowed length.
     * @return string Sanitized string.
     * @throws InvalidArgumentException If the input is invalid.
     */
    protected function validateSafeString(string $input, int $minLength = 1, int $maxLength = 255): string
    {
        // Trim whitespace from both ends
        $input = trim($input);

        // Check if length is within allowed range
        $length = mb_strlen($input);
        if ($length < $minLength || $length > $maxLength) {
            throw new InvalidArgumentException("Input must be between $minLength and $maxLength characters.");
        }

        // Remove any HTML and PHP tags
        $input = strip_tags($input);

        // Reject input with disallowed/suspicious terms (basic blacklist)
        $blacklist = ['<script', '<?php', '?>', 'SELECT', 'INSERT', 'DELETE', 'DROP', '--'];
        foreach ($blacklist as $term) {
            if (stripos($input, $term) !== false) {
                throw new InvalidArgumentException("Input contains disallowed content.");
            }
        }

        // Optional: allow only basic characters (letters, numbers, spaces, common punctuation)
        if (!preg_match('/^[\w\s.,!?@#%&()\-\'"]+$/u', $input)) {
            throw new InvalidArgumentException("Input contains invalid characters.");
        }

        return $input;
    }
}
