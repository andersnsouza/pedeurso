<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    /**
     * Retorna resposta de sucesso.
     */
    protected function success(
        mixed $data = null,
        string $message = 'Operação realizada com sucesso.',
        int $statusCode = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Retorna resposta de erro.
     */
    protected function error(
        string $message = 'Ocorreu um erro.',
        int $statusCode = 400,
        mixed $errors = null
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Retorna resposta de recurso criado.
     */
    protected function created(
        mixed $data = null,
        string $message = 'Recurso criado com sucesso.'
    ): JsonResponse {
        return $this->success($data, $message, 201);
    }

    /**
     * Retorna resposta de recurso não encontrado.
     */
    protected function notFound(string $message = 'Recurso não encontrado.'): JsonResponse
    {
        return $this->error($message, 404);
    }

    /**
     * Retorna resposta de recurso deletado.
     */
    protected function deleted(string $message = 'Recurso removido com sucesso.'): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
        ], 200);
    }

    /**
     * Retorna resposta paginada.
     */
    protected function paginated(
        ResourceCollection $collection,
        string $message = 'Dados recuperados com sucesso.'
    ): JsonResponse {
        $paginator = $collection->resource;

        $meta = [];
        if ($paginator instanceof LengthAwarePaginator) {
            $meta = [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ];
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $collection,
            'meta' => $meta,
        ]);
    }
}
