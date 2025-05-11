<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StoreHelper
{
    private static $DATA_PATH = [
        'image_product' => 'public/img/product'
    ];

    public static function store($file, string $type)
    {
        try {
            if (isset(self::$DATA_PATH[$type]) && self::$DATA_PATH[$type] != null) {
                return self::storeToPublicLocal($file, $type);
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
}
