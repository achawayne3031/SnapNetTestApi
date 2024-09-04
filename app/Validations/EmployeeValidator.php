<?php

namespace App\Validations;
use App\Helpers\Func;

class EmployeeValidator
{
    protected static $validation_rules = [];

    public static function validate_rules($request, string $arg)
    {
        self::$validation_rules = [
            'create' => [
                'project_id' => 'required',
                'name' => 'required',
                'position' => 'required',
                'email' => 'required|unique:employee',
            ],

            'edit' => [
                'id' => 'required',
            ],

        ];

        return Func::run_validation($request, self::$validation_rules[$arg]);
    }
}
