<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateModelCasts extends Command
{
    protected $signature = 'generate:model-casts {connection} {table}';

    protected $description = 'Generate the $casts array for a given table';

    public function handle()
    {
        $connection = $this->argument('connection');
        $table = $this->argument('table');

        // Use the 'local_mls' connection
        // check if the connection exists
        if (!DB::connection()->getDatabaseName()) {
            $this->error("Connection [$connection] not found.");
            return false;
        }

        // Get the columns for the table
        $columns = DB::connection($connection)->select("SHOW COLUMNS FROM $table");

        $casts = [];

        foreach ($columns as $column) {
            $field = $column->Field;
            $type = $column->Type;

            $castType = $this->mapTypeToCast($type);

            if ($castType) {
                $casts[$field] = $castType;
            }
        }

        // Output the $casts array
        echo "protected \$casts = [\n";
        foreach ($casts as $field => $castType) {
            echo "    '$field' => '$castType',\n";
        }
        echo "];\n";
    }

    private function mapTypeToCast($type)
    {
        $type = strtolower($type);

        if (preg_match('/^int/', $type) || preg_match('/^bigint/', $type)) {
            return 'integer';
        }

        if (preg_match('/^decimal\((\d+),(\d+)\)/', $type, $matches)) {
            $precision = $matches[1];
            $scale = $matches[2];
            return "decimal:$scale";
        }

        if (preg_match('/^(varchar|char|mediumtext|text)/', $type)) {
            return 'string';
        }

        if ($type === 'date') {
            return 'date';
        }

        if ($type === 'datetime') {
            return 'datetime';
        }

        if ($type === 'point') {
            return 'string'; // Handle spatial types accordingly
        }

        if ($type === 'char(1)') {
            return 'string';
        }

        if (preg_match('/^enum/', $type)) {
            return 'string'; // Handle enum types accordingly
        }

        //handle json
        if (preg_match('/^json/', $type)) {
            return 'array';
        }

        return null; // Skip types we don't need to cast
    }
}
