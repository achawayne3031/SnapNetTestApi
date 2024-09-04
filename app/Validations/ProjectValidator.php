<?php

namespace App\Validations;
use App\Helpers\Func;

class ProjectValidator
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'create' => [
                'name' => 'required|unique:project',
                'description' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ],

            'edit' => [
                'id' => 'required'
            ],

        ];

        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
