<?php


namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * Retorna uma resposta JSON padronizada para sucesso.
     *
     * @param string $message
     * @param array|object|null $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function success(string $message, $data = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Retorna uma resposta JSON padronizada para erro.
     *
     * @param string $message
     * @param int $statusCode
     * @param array|null $errors
     * @return JsonResponse
     */
    public static function error(string $message, int $statusCode = 400, array $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
}
