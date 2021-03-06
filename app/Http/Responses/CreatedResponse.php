<?php

namespace App\Http\Responses;

use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request as RequestFacade;

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
        parent::__construct($content ? $content : $model, $status, $headers);

        if (!$this->headers->has('Location')) {
            $this->header('Location', RequestFacade::url() . "/{$model->id}");
        }

    }
}
