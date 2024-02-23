<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserPendingException extends Exception
{
    public function report(): void
    {
    }

    public function render(Request $request): Response
    {
        return response()->view('errors.info', [
            'message' => $this->getMessage(),
            'goto' => '/',
            'gotoLabel' => 'Home'
        ]);
    }


}
