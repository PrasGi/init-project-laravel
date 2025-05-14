<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeService extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'make:service {name} {--with-transaction}';

    /**
     * The console command description.
     */
    protected $description = 'Create a new service class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $withTransaction = $this->option('with-transaction');

        $path = app_path("Http/Services/{$name}.php");
        $directory = dirname($path);

        if (File::exists($path)) {
            $this->error("Service already exists at {$path}!");
            return 1;
        }

        File::ensureDirectoryExists($directory);

        $namespace = 'App\\Http\\Services';
        $relativePath = Str::replaceFirst(app_path() . DIRECTORY_SEPARATOR, '', $directory);
        $namespacePart = trim(Str::replaceFirst('Http/Services', '', str_replace(DIRECTORY_SEPARATOR, '/', $relativePath)), '/');
        if (!empty($namespacePart)) {
            $namespace .= '\\' . str_replace('/', '\\', $namespacePart);
        }

        // Pilih stub sesuai opsi
        $stubFile = $withTransaction
            ? resource_path('stubs/service-with-transaction.stub')
            : resource_path('stubs/service.stub');

        if (!File::exists($stubFile)) {
            $this->error("Stub file not found: {$stubFile}");
            return 1;
        }

        $stub = file_get_contents($stubFile);
        $stub = str_replace(['{{ namespace }}', '{{ class }}'], [$namespace, class_basename($name)], $stub);

        File::put($path, $stub);

        $this->info("Service created successfully at {$path}.");
        return 0;
    }
}
