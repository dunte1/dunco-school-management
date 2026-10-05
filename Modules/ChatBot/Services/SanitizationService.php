<?php

namespace Modules\ChatBot\Services;

use Illuminate\Support\Facades\Log;

class SanitizationService
{
    /**
     * Sanitize user input
     *
     * @param string $input
     * @return string
     */
    public function sanitizeInput($input)
    {
        try {
            // Remove null bytes
            $input = str_replace("\0", '', $input);
            
            // Remove control characters except tab, newline, and carriage return
            $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
            
            // Remove or escape HTML/JS
            $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            
            // Remove potentially dangerous patterns
            $dangerousPatterns = [
                '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', // Script tags
                '/javascript:/mi', // JavaScript URLs
                '/vbscript:/mi', // VBScript URLs
                '/on\w+\s*=/mi', // Event handlers
                '/expression\s*\(/mi', // CSS expressions
                '/data:/mi', // Data URLs
                '/about:/mi', // About URLs
                '/xmlns:/mi', // XML namespaces
            ];
            
            foreach ($dangerousPatterns as $pattern) {
                $input = preg_replace($pattern, '', $input);
            }
            
            // Limit length
            $input = substr($input, 0, 4096);
            
            // Trim whitespace
            $input = trim($input);
            
            return $input;
        } catch (\Exception $e) {
            Log::error('Input sanitization error: ' . $e->getMessage());
            // Return a safe default if sanitization fails
            return htmlspecialchars(substr(trim($input), 0, 1000), ENT_QUOTES, 'UTF-8');
        }
    }

    /**
     * Sanitize message for database storage
     *
     * @param string $message
     * @return string
     */
    public function sanitizeForDatabase($message)
    {
        try {
            // Remove null bytes
            $message = str_replace("\0", '', $message);
            
            // Remove control characters
            $message = preg_replace('/[\x00-\x1F\x7F]/', '', $message);
            
            // Limit length to prevent oversized data
            $message = substr($message, 0, 65535);
            
            return $message;
        } catch (\Exception $e) {
            Log::error('Database sanitization error: ' . $e->getMessage());
            return substr($message, 0, 1000);
        }
    }

    /**
     * Validate and sanitize file name
     *
     * @param string $filename
     * @return string
     */
    public function sanitizeFilename($filename)
    {
        try {
            // Remove path traversal attempts
            $filename = str_replace(['../', '..\\', '..'], '', $filename);
            
            // Remove null bytes
            $filename = str_replace("\0", '', $filename);
            
            // Remove control characters
            $filename = preg_replace('/[\x00-\x1F\x7F]/', '', $filename);
            
            // Allow only safe characters
            $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
            
            // Limit length
            $filename = substr($filename, 0, 255);
            
            return $filename;
        } catch (\Exception $e) {
            Log::error('Filename sanitization error: ' . $e->getMessage());
            return 'sanitized_filename_' . time() . '.txt';
        }
    }

    /**
     * Validate and sanitize URL
     *
     * @param string $url
     * @return string|false
     */
    public function sanitizeUrl($url)
    {
        try {
            // Remove null bytes
            $url = str_replace("\0", '', $url);
            
            // Remove control characters
            $url = preg_replace('/[\x00-\x1F\x7F]/', '', $url);
            
            // Validate URL format
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return false;
            }
            
            // Only allow http and https protocols
            if (!preg_match('/^https?:\/\//i', $url)) {
                return false;
            }
            
            // Limit length
            $url = substr($url, 0, 2048);
            
            return $url;
        } catch (\Exception $e) {
            Log::error('URL sanitization error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove potentially harmful content from text
     *
     * @param string $text
     * @return string
     */
    public function removeHarmfulContent($text)
    {
        try {
            // Remove SQL injection patterns
            $sqlPatterns = [
                '/union\s+select/i',
                '/insert\s+into/i',
                '/update\s+\w+\s+set/i',
                '/delete\s+from/i',
                '/drop\s+table/i',
                '/create\s+table/i',
                '/exec(\s|\()+/i',
                '/execute(\s|\()+/i',
            ];
            
            foreach ($sqlPatterns as $pattern) {
                $text = preg_replace($pattern, '', $text);
            }
            
            // Remove file system patterns
            $filePatterns = [
                '/etc\/passwd/i',
                '/\/etc\/passwd/i',
                '/\/windows\/system32/i',
                '/c:\\\\windows\\\\system32/i',
                '/\.{2,}/', // Multiple dots
            ];
            
            foreach ($filePatterns as $pattern) {
                $text = preg_replace($pattern, '', $text);
            }
            
            return $text;
        } catch (\Exception $e) {
            Log::error('Harmful content removal error: ' . $e->getMessage());
            return substr($text, 0, 1000);
        }
    }

    /**
     * Sanitize and validate email address
     *
     * @param string $email
     * @return string|false
     */
    public function sanitizeEmail($email)
    {
        try {
            // Remove null bytes
            $email = str_replace("\0", '', $email);
            
            // Remove control characters
            $email = preg_replace('/[\x00-\x1F\x7F]/', '', $email);
            
            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
            
            // Limit length
            $email = substr($email, 0, 254);
            
            return $email;
        } catch (\Exception $e) {
            Log::error('Email sanitization error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Sanitize and validate phone number
     *
     * @param string $phone
     * @return string
     */
    public function sanitizePhone($phone)
    {
        try {
            // Remove everything except digits, +, -, (, ), and space
            $phone = preg_replace('/[^0-9+\-\(\)\s]/', '', $phone);
            
            // Remove null bytes
            $phone = str_replace("\0", '', $phone);
            
            // Limit length
            $phone = substr($phone, 0, 20);
            
            return $phone;
        } catch (\Exception $e) {
            Log::error('Phone sanitization error: ' . $e->getMessage());
            return substr(preg_replace('/[^0-9]/', '', $phone), 0, 10);
        }
    }
}