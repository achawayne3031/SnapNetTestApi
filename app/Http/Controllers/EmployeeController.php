<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\Auth;
use App\Validations\EmployeeValidator;
use App\Validations\ErrorValidation;
use App\Helpers\ResponseHelper;
use App\Models\Project;
use App\Models\Employee;

use App\Helpers\DBHelpers;
use App\Helpers\Func;
use App\Jobs\SendEmail;



class EmployeeController extends Controller
{
    //



    public function all(){
        $employee = Employee::query()->with(['project'])->get();
 
        return ResponseHelper::success_response(
         'All employee fetched was successful',
            $employee
         );
     }
 
     
     public function create(Request $request)
     {
         if ($request->isMethod('post')) {
             $validate = EmployeeValidator::validate_rules($request, 'create');
             if (!$validate->fails() && $validate->validated()) {

                if(!Project::find($request->project_id)){
                    return ResponseHelper::success_response(
                        'Project not found or deleted',
                        null
                    );
                }

                    $data = [
                        'name' => $request->name,
                        'project_id' => $request->project_id,
                        'position' => $request->position,
                        'email' => $request->email
                    ];
    
                    /// Save  /////
                    DBHelpers::create_query(Employee::class, $data);

                    SendEmail::dispatch($data);
            
                    return ResponseHelper::success_response(
                        'Employee creation was successful',
                        null
                    );

             } else {
                 $errors = json_decode($validate->errors());
                 $props = ['name', 'project_id', 'email', 'position'];
                 $error_res = ErrorValidation::arrange_error($errors, $props);
                 return ResponseHelper::error_response(
                     'validation error',
                     $error_res,
                     401
                 );
             }
         } else {
             return ResponseHelper::error_response(
                 'HTTP Request not allowed',
                 '',
                 404
             );
         }
     }
 
 
 
     public function show($id){

        if(!Employee::find($id)){
            return ResponseHelper::success_response(
                'Employee not found or deleted',
                null
            );

        }

        $employee = Employee::query()->with(['project'])->where(['id' => $id])->get();
         return ResponseHelper::success_response(
             'Employee data was fetched successfully',
             $employee
         );
     }
 
 
     
     public function edit(Request $request)
     {
         if ($request->isMethod('post')) {
             $validate = EmployeeValidator::validate_rules($request, 'edit');
             if (!$validate->fails() && $validate->validated()) {


                if(!Employee::find($request->id)){
                    return ResponseHelper::success_response(
                        'Employee not found or deleted',
                        null
                    );
                }
 

                if(isset($request->project_id) && !Project::find($request->project_id)){
                    return ResponseHelper::success_response(
                        'Project not found or deleted',
                        null
                    );
                }
 
            
                $employee = DBHelpers::query_filter_first(Employee::class, ['id' => $request->id]);
 
                $update_data = [
                    'name' => $request->name ? $request->name : $employee->name,
                    'email' => $request->email ? $request->email : $employee->email,
                    'position' => $request->position ? $request->position : $employee->position,
                    'project_id' => $request->project_id ? $request->project_id : $employee->project_id,

                ];
 
                DBHelpers::update_query_v3(Employee::class, $update_data, ['id' => $request->id]);
             
                 return ResponseHelper::success_response(
                     'Employee updated successfully',
                     null
                 );
 
 
             } else {
                 $errors = json_decode($validate->errors());
                 $props = ['id'];
                 $error_res = ErrorValidation::arrange_error($errors, $props);
                 return ResponseHelper::error_response(
                     'validation error',
                     $error_res,
                     401
                 );
             }
         } else {
             return ResponseHelper::error_response(
                 'HTTP Request not allowed',
                 '',
                 404
             );
         }
     }

     
    
    public function delete($id){
        if(!Project::find($id)){
            return ResponseHelper::success_response(
                'Employee not found or deleted',
                null
            );
        }
       
        $project = Employee::find($id);
        $project->delete();


        return ResponseHelper::success_response(
            'Employee data deleted successfully',
            null
        );
    }




    public function restore($id){

        if(!Project::find($id)){
            return ResponseHelper::success_response(
                'Employee not found or deleted',
                null
            );
        }

        $employee = Employee::withTrashed()->find($id);
        $employee->restore();

        return ResponseHelper::success_response(
            'Employee data restored successfully',
            $employee
        );
    }


}
