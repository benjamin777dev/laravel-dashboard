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

    // Function to convert datetime string to UTC
    public static function convertToUTC($datetime)
    {
        try {
            $date = new \DateTime($datetime);
            $date->setTimezone(new \DateTimeZone('UTC'));
            return $date->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            Log::error("Error converting datetime to UTC: " . $e->getMessage());
            throw $e;
        }
    }

    // Function to convert UTC to Mountain Standard Time (MST)
    public static function convertToMST($datetime)
    {
        try {
            $date = new \DateTime($datetime);
            $date->setTimezone(new \DateTimeZone('America/Denver'));
            return $date->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            Log::error("Error converting datetime to MST: " . $e->getMessage());
            throw $e;
        }
    }
}
