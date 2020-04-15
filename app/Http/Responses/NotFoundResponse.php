<?php

namespace App\Http\Responses;

use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;

class NotFoundResponse extends Response
{
    /**
     * NotFoundResponse constructor.
     *
     * @param string $content
     * @param int $status
     * @param array $headers
     */
    public function __construct($content = '', $status = 404, array $headers = [])
    {
        parent::__construct($content, $status, $headers);
    }
}
