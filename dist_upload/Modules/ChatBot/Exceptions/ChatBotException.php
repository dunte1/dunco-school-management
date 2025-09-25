<?php

namespace Modules\ChatBot\Exceptions;

use Exception;

class ChatBotException extends Exception
{
    protected $code = 500;

    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMessage(),
                'error_type' => 'chatbot_error',
            ], $this->getCode() ?: 500);
        }

        return parent::render($request);
    }
} 