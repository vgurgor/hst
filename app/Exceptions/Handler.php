<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
        'name',
        'status',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {

        if (env("APP_DEBUG")) {
            return parent::render($request, $e);
        }

        if ($e instanceof ValidationException) {
            return response()->json([
                'error' => [
                    'message' => "Bad Request",
                    'status_code' => 400
                ]
            ], 400);
        } else if ($e instanceof NotFoundHttpException) {
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
                'error' => $e->getMessage()
            ]
        ], 500);

    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json($exception->errors(), $exception->status);
    }
}
