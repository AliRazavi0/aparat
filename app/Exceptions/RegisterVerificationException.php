<?php

namespace App\Exceptions;

use Exception;

class RegisterVerificationException extends Exception
{
    /**
     * Report the exception.
     *
     * @return void
     */
     public function report()
     {
        //
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response([
            'message' => $this->getMessage()
        ], 400);
    }
}
