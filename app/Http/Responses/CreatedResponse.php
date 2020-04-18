<?php

namespace App\Http\Responses;

use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;

class CreatedResponse extends Response
{
    /**
     * CreatedResponse constructor.
     *
     * @param Model $model
     * @param string $content
     * @param int $status
     * @param array $headers
     */
    public function __construct(Model $model, $content = '', $status = 201, array $headers = [])
    {
        parent::__construct($content, $status, $headers);

        if (!$this->headers->has('Location')) {
            // By default, we will assume our REST APIs will follow the naming convention
            // identical to our database structure. However, we may override this by specifying
            // a resourcePath attribute on the model if we want.
            $location = $model->resourcePath ?? $model->getTable();
            $this->header('Location', url("$location/{$model->id}"));
        }
    }
}
