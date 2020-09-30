<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\ExpectationFailedException;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    public function assertPassesValidation($data, array $validation) {
        $validator = Validator::make($data, $validation);

        if ($validator->fails()) {
            throw new ExpectationFailedException($validator->errors()->toJson());
        }
    }
}
