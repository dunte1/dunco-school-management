<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
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
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle API exceptions
        $this->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return $this->handleApiException($e, $request);
            }
        }, 0);
    }

    /**
     * Handle API exceptions
     */
    protected function handleApiException(Throwable $e, $request): JsonResponse
    {
        $statusCode = 500;
        $message = 'Internal server error';

        if ($e instanceof ValidationException) {
            $statusCode = 422;
            $message = 'Validation failed';
            return response()->json([
                'status' => 'error',
                'message' => $message,
                'code' => $statusCode,
                'data' => ['errors' => $e->errors()],
                'timestamp' => now()->toIso8601String(),
            ], $statusCode);
        }

        if ($e instanceof AuthenticationException) {
            $statusCode = 401;
            $message = 'Unauthenticated';
        }

        if ($e instanceof AuthorizationException) {
            $statusCode = 403;
            $message = 'Forbidden';
        }

        if ($e instanceof ModelNotFoundException) {
            $statusCode = 404;
            $message = 'Resource not found';
        }

        if ($e instanceof NotFoundHttpException) {
            $statusCode = 404;
            $message = 'Endpoint not found';
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            $statusCode = 405;
            $message = 'Method not allowed';
        }

        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            $message = $e->getMessage() ?: 'HTTP error';
        }

        if ($e instanceof QueryException) {
            $statusCode = 500;
            $message = 'Database error';
        }

        return response()->json([
            'status' => 'error',
            'message' => $message,
            'code' => $statusCode,
            'timestamp' => now()->toIso8601String(),
        ], $statusCode);
    }
}
