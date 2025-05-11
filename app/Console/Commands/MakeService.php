<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new service class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = app_path("Http/Services/{$name}.php");

        // Extract directory from the provided name
        $directory = dirname($path);

        // Check if the file already exists
        if (File::exists($path)) {
            $this->error("Service already exists at {$path}!");
            return 1;
        }

        // Create directory if it does not exist
        File::ensureDirectoryExists($directory);

        // Determine the namespace based on the directory structure
        $namespace = 'App\\Http\\Services';
        $relativePath = Str::replaceFirst(app_path() . DIRECTORY_SEPARATOR, '', $directory);
        $namespacePart = trim(Str::replaceFirst('Http/Services', '', str_replace(DIRECTORY_SEPARATOR, '/', $relativePath)), '/');
        if (!empty($namespacePart)) {
            $namespace .= '\\' . str_replace('/', '\\', $namespacePart);
        }

        // Load the stub file and replace the placeholders
        $stub = file_get_contents(resource_path('stubs/service.stub'));
        $stub = str_replace(['{{ namespace }}', '{{ class }}'], [$namespace, class_basename($name)], $stub);

        // Create the service file
        File::put($path, $stub);

        $this->info("Service created successfully at {$path}.");
        return 0;
    }
}
