<?php

namespace App\Traits;

trait HttpResponses
{
    protected function successfullRequest($data, string $message = null, int $code = 200)
    {
        return response()->json([
            'status' => 'Request successful',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function failedRequest($data, string $message = null, int $code = 200)
    {
        return response()->json([
            'status' => 'Request failed',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
