<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LoginException extends Exception
{
    public function report(): void
    {
    }

    public function render(Request $request): Response
    {
        return response()->view('errors.error', [
            'message' => $this->getMessage()
        ]);
    }

}