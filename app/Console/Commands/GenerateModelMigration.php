<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class GenerateModelMigration extends Command
{
    protected $signature = 'generate:model-migration {model : Contact}';

    protected $description = 'Generate migration file based on model attributes';

    public function handle()
    {
        $model = $this->argument('model');

        $modelClass = 'App\\Models\\' . $model;

        if (!class_exists($modelClass)) {
            $this->error("Model class '$modelClass' does not exist.");
            return;
        }

        $tableName = (new $modelClass)->getTable();
        $fillableAttributes = (new $modelClass)->getFillable();
        $migrationContent = $this->generateMigrationContent($tableName, $fillableAttributes);

        $migrationFileName = 'create_' . strtolower($tableName) . '_table.php';
        $migrationFilePath = database_path('migrations') . '/' . date('Y_m_d_His') . '_' . $migrationFileName;

        File::put($migrationFilePath, $migrationContent);

        $this->info("Migration file created: $migrationFileName");
    }

    protected function generateMigrationContent($tableName, $fillableAttributes)
    {
        $fields = '';

        foreach ($fillableAttributes as $attribute) {
            $fields .= "\$table->string('$attribute')->nullable();\n            ";
        }

        $content = <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Create{$tableName}Table extends Migration
{
    public function up()
    {
        Schema::create('{$tableName}', function (Blueprint \$table) {
            \$table->id();
            $fields
            \$table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('{$tableName}');
    }
}
PHP;

        return $content;
    }
}
