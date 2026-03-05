<?php

declare(strict_types=1);

namespace Awcodes\Botly\Http\Controllers;

use Awcodes\Botly\Action\ParseDirectivesToText;

class BotlyController
{
    public function __invoke(): \Illuminate\Contracts\Routing\ResponseFactory | \Illuminate\Http\Response
    {
        $text = (new ParseDirectivesToText())->handle();

        return response($text, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
