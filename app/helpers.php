<?php

use Illuminate\Database\Eloquent\Model;

if (!function_exists('resource_url')) {

    /**
     * Get the RESTful route to the Model.
     * By default, the table name will correspond to the REST route.
     * This may be overriden by specifying a $resourceName variable on the model.
     *
     * @param Model $model
     * @return string
     */
    function resource_url(Model $model)
    {
        $root = property_exists($model, 'resourceName')
            ? $model->resourceName
            : $model->getTable();

        return url('/api') . "/$root/{$model->getRouteKey()}";
    }
}
