<?php

namespace App\Helpers;

use Google\Cloud\Storage\StorageClient;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StoreHelper
{
    private static $DATA_PATH = [
        'image_product' => 'public/temp/img/product',
        'image_evidence' => 'public/temp/img/evidence',
    ];

    public static function store($file, string $type)
    {
        try {
            if (isset(self::$DATA_PATH[$type]) && self::$DATA_PATH[$type] != null) {
                // return self::storeToPublicLocal($file, $type);
                return self::storeToCloud($file, self::$DATA_PATH[$type]);
            }

            throw new \Exception('Path not found');
        } catch (\Throwable $th) {
            Log::error('StoreHelper : ' . $th->getMessage());
            throw new \Exception($th->getMessage());
        }
    }

    private static function createDirectoryIfNotExists($folderPath)
    {
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0755, true);
        }
    }

    private static function storeToPublicLocal($file, $type)
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $folderPath = public_path(self::$DATA_PATH[$type]);

        self::createDirectoryIfNotExists($folderPath);
        $file->move($folderPath, $filename);

        return self::$DATA_PATH[$type] . '/' . $filename;
    }

    public static function storeToCloud(UploadedFile $file, string $destination, string $name = ''): ?string
    {
        // Ambil nama file asli tanpa ekstensi & tambahkan timestamp agar unik
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->timestamp;
        $fileName = $name ?: "{$originalName}_{$timestamp}.{$extension}";

        // Buat full path berdasarkan root folder
        $root = rtrim(env('GOOGLE_CLOUD_ROOT_FOLDER', ''), '/') . '/';
        $path = $root . ltrim($destination, '/') . $fileName;

        try {

            $client = new StorageClient([
                'keyFilePath' => base_path(env('GOOGLE_CLOUD_KEY_FILE')),
            ]);

            $bucket = $client->bucket(env('GOOGLE_CLOUD_STORAGE_BUCKET'));

            $object = $bucket->upload(file_get_contents($file), [
                'name' => $path
            ]);

            $signUrl = $object->signedUrl(new \DateTime('+10 years'));

            return $signUrl;
        } catch (\Exception $e) {
            Log::error("Store Helper: Failed to store file to GCS: {$e->getMessage()}");
            throw new \Exception($e->getMessage());
        }
    }
}
