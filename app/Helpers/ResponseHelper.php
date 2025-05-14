<?php

namespace App\Helpers;

class ResponseHelper {
    public static function success($message = 'Success', $data = null, $status_code = 200, $status = true)
    {
        return response()->json([
            'status' => $status,
            'code' => $status_code,
            'message' => $message,
            'data' => $data,
            'errors' => []
        ], 200);
    }

    public static function error($message = 'Error', $errors = [], $status_code = 200, $data = null, $status = false)
    {
        return response()->json([
            'status' => $status,
            'code' => $status_code,
            'message' => $message,
            'data' => $data,
            'errors' => $errors
        ], 200);
    }
}
