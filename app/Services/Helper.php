<?php
namespace App\Services;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\ZohoCRM;
use Illuminate\Support\Facades\File;
use League\Csv\Reader;


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

    public function extractZipFile($zipUrl,$zoho)
    {
        try {
            // Download the ZIP file
            $zipContents = Http::withHeaders([
                'Authorization' => 'Zoho-oauthtoken ' . $zoho->getAccessToken(),
            ])->get($zipUrl);

            // Save the ZIP file to storage
            $zipPath = 'temp/' . uniqid() . '.zip';
            Storage::put($zipPath, $zipContents);

            // Extract the ZIP file
            $extractPath = 'temp/' . uniqid();
            Storage::makeDirectory($extractPath);
            $zip = new ZipArchive;
            if ($zip->open(storage_path('app/' . $zipPath)) === true) {
                $zip->extractTo(storage_path('app/' . $extractPath));
                $zip->close();
                // Retrieve extracted files
                $extractedFiles = Storage::files($extractPath);
                // Cleanup: Remove the ZIP file
                Storage::delete($zipPath);
                return $extractedFiles;
            } else {
                // Failed to open the ZIP file
                \Log::error('Failed to open the ZIP file.');
                return false;
            }
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Exception occurred while extracting ZIP file: ' . $e->getMessage());
            return false;
        }
    }

    public function csvToJson($csvFilePaths)
    {
        try {
            $jsonArray = [];

            foreach ($csvFilePaths as $csvFilePath) {
                // Construct the full path
                $fullPath = storage_path('app/' . $csvFilePath);

                // Check if the file exists
                if (!file_exists($fullPath)) {
                    \Log::error('CSV file does not exist: ' . $fullPath);
                    continue;
                }

                // Read the CSV file contents
                $csvContents = file_get_contents($fullPath);
                \Log::info('CSV contents: ', ['csvContents' => $csvContents]);

                // Convert CSV to associative array
                $csvArray = array_map("str_getcsv", explode("\n", $csvContents));
                \Log::info('CSV array: ', ['csvArray' => $csvArray]);

                // Remove null rows
                $csvArray = array_filter($csvArray, function($row) {
                    Log::info('CSV row: ', ['row' => $row]);
                    return !is_null($row[0]);
                });

                // Ensure each row has the same number of values as the header row
                $headerCount = count($csvArray[0]);
                foreach ($csvArray as $row) {
                    \Log::info('count of array: ', ['count of array' => count($row)]);
                    if (count($row) !== $headerCount) {
                        \Log::error('CSV row does not have the same number of values as the header row: ' . $fullPath);
                        continue 2; // Skip to the next CSV file
                    }
                }

                // Extract header row
                $keys = array_shift($csvArray);

                // Convert rows to associative arrays
                $json = [];
                foreach ($csvArray as $row) {
                    $json[] = array_combine($keys, $row);
                }

                // Merge the JSON data
                $jsonArray = array_merge($jsonArray, $json);
            }

            // Convert the associative array to JSON
            $jsonString = $jsonArray;
            Log::info('JSON DATA ', [$jsonString]);
            return $jsonString;
        } catch (\Exception $e) {
            // Log the exception
            \Log::error('Exception occurred while converting CSV to JSON: ' . $e->getMessage());
            return false;
        }
    }

    public function array_find(array $array, callable $callback) {
    foreach ($array as $item) {
        if ($callback($item)) {
            return $item;
        }
    }
    return null;
}

}
