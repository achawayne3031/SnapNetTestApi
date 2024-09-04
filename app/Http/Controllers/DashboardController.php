<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use App\Models\Project;
use App\Models\Employee;
use App\Helpers\DBHelpers;
use App\Helpers\Func;



class DashboardController extends Controller
{
    //

    public function summary(){
        $total_employee = DBHelpers::count(Employee::class);
        $total_project = DBHelpers::count(Project::class);


        $data = [
            'total_employee' => $total_employee,
            'total_project' => $total_project,
        ];


        return ResponseHelper::success_response(
            'Summary fetched was successful',
               $data
            );
    }


    public function projects(){
        $projects = Project::query()->with(['employee'])->get();

        return ResponseHelper::success_response(
         'All project fetched was successful',
         $projects
         );
    }



    public function paginated_employee(){
        $employee = Employee::query()->with(['project'])->paginate(10);

        return ResponseHelper::success_response(
            'All employee fetched was successful',
            $employee
        );
    }


}
