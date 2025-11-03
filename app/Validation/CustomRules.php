<?php

namespace App\Validation;

class CustomRules
{
    /**
     * Validates that a string is a valid JSON string
     *
     * @param string $str The string to validate
     * @param string &$error Error message to return
     * @return bool
     */
    public function valid_json_string($str = null, string &$error = null): bool
    {
        // Let 'required' handle empties
        if ($str === null || $str === '') {
            return true;
        }
        
        // If already array (e.g., from form with multiple fields), accept; controller encodes.
        if (is_array($str)) {
            return true;
        }
        
        if (!is_string($str)) {
            $error = 'The {field} field must be a JSON string.';
            return false;
        }
        
        json_decode($str, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return true;
        }
        
        $error = 'The {field} field must contain valid JSON.';
        return false;
    }
}
