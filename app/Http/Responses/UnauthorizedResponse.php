<?php

namespace App\Http\Responses;

use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;

class UnauthorizedResponse extends Response
{
    /**
     * UnauthorizedResponse constructor.
     *
     * @param string $content
     * @param int $status
     * @param array $headers
     */
    public function __construct($content = '', $status = 401, array $headers = [])
    {
        parent::__construct($content, $status, $headers);
    }
}
