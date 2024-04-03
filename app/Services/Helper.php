<?php
namespace App\Services;


use Illuminate\Support\Facades\Log;


class Helper
{
    public static function getValue($variable, $noteApiName)
    {
        try {
            Log::info("Helper Function", ['variable' => $variable, 'noteApiName' => $noteApiName]);
            foreach ($variable as $value) {
                if ($value === $noteApiName) {
                    return $value;
                }
            }
            // If no match found, return null or any other value as needed
            return null;
        } catch (\Exception $e) {
            Log::error("Error retrieving notes: " . $e->getMessage());
            throw $e; 
        }
        
    }
}
