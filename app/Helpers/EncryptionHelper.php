<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class EncryptionHelper
{
    /**
     * Encrypt an ID for use in URLs
     */
    public static function encryptId($id)
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException('ID must be numeric');
        }
        
        // Add a random prefix to make the encrypted string less predictable
        $prefix = Str::random(4);
        $encrypted = Crypt::encrypt($prefix . '|' . $id);
        
        // Make it URL-safe
        return str_replace(['+', '/', '='], ['-', '_', ''], $encrypted);
    }
    
    /**
     * Decrypt an ID from URL
     */
    public static function decryptId($encryptedId)
    {
        // If it's already a plain numeric ID, return it as is
        if (is_numeric($encryptedId)) {
            return (int) $encryptedId;
        }
        
        try {
            // Make it URL-safe again
            $encryptedId = str_replace(['-', '_'], ['+', '/'], $encryptedId);
            
            // Add back padding if needed
            $padding = strlen($encryptedId) % 4;
            if ($padding) {
                $encryptedId .= str_repeat('=', 4 - $padding);
            }
            
            $decrypted = Crypt::decrypt($encryptedId);
            $parts = explode('|', $decrypted);
            
            if (count($parts) !== 2) {
                throw new \InvalidArgumentException('Invalid encrypted ID format');
            }
            
            $id = $parts[1];
            
            if (!is_numeric($id)) {
                throw new \InvalidArgumentException('Decrypted ID is not numeric');
            }
            
            return (int) $id;
        } catch (\Exception $e) {
            throw new \InvalidArgumentException('Invalid or corrupted encrypted ID: ' . $e->getMessage());
        }
    }
}
