<?php

namespace App\Traits;

trait APIResponses
{
    public function okResponse(mixed $data = [], string $message = null)
    { 
        return $this->response($data, 200, $message ?? 'OK');
    }

    public function createdResponse(mixed $data = [], string $message = null)
    { 
        return $this->response($data, 201, $message ?? 'Created Resource');
    }

    public function badResponse(mixed $data = [], string $message = null)
    { 
        return $this->response($data, 400, $message ?? 'Bad Request');
    }

    public function unauthorizedResponse(mixed $data = [], string $message = null)
    { 
        return $this->response($data, 401, $message ?? 'Unauthorized');
    }

    public function response(mixed $data, int $code, string $message)
    {
        return response()->json([
            'success'   => $code >= 200 && $code <= 299,
            'code'      => $code,
            'message'   => $message,
            'data'      => $data,
        ], $code);
    }
}