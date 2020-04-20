<?php

namespace App\Http\Responses;

use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;

class NotImplementedResponse extends Response
{
    /**
     * NotImplementedResponse constructor.
     *
     * @param string $content
     * @param int $status
     * @param array $headers
     */
    public function __construct($content = '', $status = 501, array $headers = [])
    {
        parent::__construct($content, $status, $headers);

    }
}
