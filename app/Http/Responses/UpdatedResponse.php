<?php

namespace App\Http\Responses;

use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;

class UpdatedResponse extends Response
{
    /**
     * UpdatedResponse constructor.
     *
     * @param Model $model
     * @param string $content
     * @param int $status
     * @param array $headers
     */
    public function __construct(Model $model, $content = '', $status = 204, array $headers = [])
    {
        parent::__construct($content, $status, $headers);

        // We will return an empty response body for update requests.
        // Should we want to return the updated resource, we can
        // enable the lines of code below.
        // if (empty($content)) {
            // $this->setContent($model);
            // $this->setStatusCode(200);
        // }
    }
}
