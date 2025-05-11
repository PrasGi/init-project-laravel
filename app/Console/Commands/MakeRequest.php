<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:req {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new request class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $path = base_path("app/Http/Requests/{$name}.php");

        // Extract directory from the provided name
        $directory = dirname($path);

        // Check if the file already exists
        if (File::exists($path)) {
            $this->error("Request already exists at {$path}!");
            return 1;
        }

        // Create directory if it does not exist
        File::ensureDirectoryExists($directory);

        // Fix namespace issue - Manually set the correct namespace
        $namespace = 'App\Http\Requests';

        // Check if the name has subdirectories (e.g., Partner/PartnerController)
        if (strpos($name, '/') !== false || strpos($name, '\\') !== false) {
            $namespace .= '\\' . str_replace(['/', '\\'], '\\', dirname($name));
        }

        // Load the stub file and replace the placeholders
        $stub = file_get_contents(resource_path('stubs/request.stub'));
        $stub = str_replace(['{{ namespace }}', '{{ class }}'], [$namespace, class_basename($name)], $stub);

        // Create the controller file
        File::put($path, $stub);

        $this->info("Request created successfully at {$path}.");
        return 0;
    }
}
