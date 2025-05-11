<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeCollection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:collect {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new collection class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Http/Resources/{$name}.php");

        // Extract directory from the provided name
        $directory = dirname($path);

        // Check if the file already exists
        if (File::exists($path)) {
            $this->error("Collection already exists at {$path}!");
            return 1;
        }

        // Create directory if it does not exist
        File::ensureDirectoryExists($directory);

        // Determine the namespace based on the directory structure
        $namespace = 'App\\Http\\Resources';
        $relativePath = Str::replaceFirst(app_path() . DIRECTORY_SEPARATOR, '', $directory);
        $namespacePart = trim(Str::replaceFirst('Http/Resources', '', str_replace(DIRECTORY_SEPARATOR, '/', $relativePath)), '/');
        if (!empty($namespacePart)) {
            $namespace .= '\\' . str_replace('/', '\\', $namespacePart);
        }

        // Load the stub file and replace the placeholders
        $stub = file_get_contents(resource_path('stubs/collection.stub'));
        $stub = str_replace(['{{ namespace }}', '{{ class }}'], [$namespace, class_basename($name)], $stub);

        // Create the service file
        File::put($path, $stub);

        $this->info("Collection created successfully at {$path}.");
        return 0;
    }
}
