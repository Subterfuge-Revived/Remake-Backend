<?php

namespace App\Http\Responses;

use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;

class DeletedResponse extends Response
{
    /**
     * DeletedResponse constructor.
     *
     * @param Model $model
     * @param string $content
     * @param int $status
     * @param array $headers
     */
    public function __construct(Model $model, $content = '', $status = null, array $headers = [])
    {
        parent::__construct($content, $status, $headers);

        // If not explicitly specified, set the status code to 200 or 204,
        // depending on whether we have a response body.
        $this->setStatusCode($status ? $status : (empty($content) ? 204 : 200));
    }
}
