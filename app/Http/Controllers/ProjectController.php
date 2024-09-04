<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Validations\ProjectValidator;
use App\Validations\ErrorValidation;
use App\Helpers\ResponseHelper;
use App\Models\Project;
use App\Helpers\DBHelpers;
use App\Helpers\Func;
use App\Models\Employee;

class ProjectController extends Controller
{
    //

    public function all(){

        try {
            $projects = Project::query()->with(['employee'])->get();

        } catch (Exception $e) {
            return ResponseHelper::error_response(
                'Server Error',
                $e->getMessage(),
                401,
                $e->getLine()
            );
        }


       return ResponseHelper::success_response(
        'All project fetched was successful',
        $projects
        );
    }

    
    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $validate = ProjectValidator::validate_rules($request, 'create');
            if (!$validate->fails() && $validate->validated()) {

                $data = [
                    'name' => $request->name,
                    'description' => $request->description,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date
                ];

                /// Save  /////
                DBHelpers::create_query(Project::class, $data);
        
                return ResponseHelper::success_response(
                    'Project creation was successful',
                    null
                );

            } else {
                $errors = json_decode($validate->errors());
                $props = ['name', 'description', 'start_date', 'end_date'];
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
        if(!DBHelpers::exists(Project::class, ['id' => $id])){
            return ResponseHelper::error_response(
                'Project not found',
                [],
                404
            );
        }

        $project = Project::find($id);
        return ResponseHelper::success_response(
            'Project data was fetched successfully',
            $project
        );
    }


    
    public function edit(Request $request, Employee $employee)
    {
        if ($request->isMethod('post')) {
            $validate = ProjectValidator::validate_rules($request, 'edit');
            if (!$validate->fails() && $validate->validated()) {

                  // authorize the user's action
                $this->authorize('update', $employee);
        

                if(!DBHelpers::exists(Project::class, ['id' => $request->id])){
                    return ResponseHelper::error_response(
                        'Project not found',
                        [],
                        401
                    );
                }

               $project = DBHelpers::query_filter_first(Project::class, ['id' => $request->id]);

               $update_data = [
                   'name' => $request->name ? $request->name : $project->name,
                   'start_date' => $request->start_date ? $request->start_date : $project->start_date,
                   'end_date' => $request->end_date ? $request->end_date : $project->end_date,
                   'description' => $request->description ? $request->description : $project->description,
               ];

               DBHelpers::update_query_v3(Project::class, $update_data, ['id' => $request->id]);
            
                return ResponseHelper::success_response(
                    'Project updated successfully',
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



    public function delete($id, Employee $employee){


           // authorize the user's action
        $this->authorize('delete', $employee);

        if(!DBHelpers::exists(Project::class, ['id' => $id])){
            return ResponseHelper::error_response(
                'Project not found',
                [],
                404
            );
        }


        $project = Project::find($id);
        $project->delete();


        return ResponseHelper::success_response(
            'Project data deleted successfully',
            null
        );
    }




    public function restore($id){
        $project = Project::withTrashed()->find($id);
        $project->restore();

        return ResponseHelper::success_response(
            'Project data restored successfully',
            $project
        );
    }




}
