<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface Resource {

    /**
     * List the resources.
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request);

    /**
     * Save a resource.
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request);

    /**
     * Obtain a resource.
     *
     * @param Model $model
     * @return mixed
     */
    public function show(Model $model);

    /**
     * Update a resource.
     *
     * @param Model $model
     * @param Request $request
     * @return mixed
     */
    public function update(Model $model, Request $request);

    /**
     * Destroy a resource.
     *
     * @param Model $model
     * @return mixed
     */
    public function destroy(Model $model);
}
