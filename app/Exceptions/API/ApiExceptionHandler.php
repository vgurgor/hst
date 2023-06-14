<?php

namespace App\Exceptions\API;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiExceptionHandler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return response()->json([
                'error' => [
                    'message' => "Bad Request",
                    'status_code' => 400
                ]
            ], 400);
        } else if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'error' => [
                    'message' => "Not Found",
                    'status_code' => 404
                ]
            ], 404);
        }

        return response()->json([
            'error' => [
                'message' => "Internal Server Error",
                'status_code' => 500,
                'error' => $exception->getMessage()
            ]
        ], 500);

        return parent::render($request, $exception);
    }
}
