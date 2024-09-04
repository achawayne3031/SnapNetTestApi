<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\Project;


class ProjectPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(Employee $employee, Project $project)
    {
        return $employee->project_id === $project->user_id;
    }

    public function delete(Employee $employee, Project $project)
    {
        return $employee->project_id === $project->user_id;
    }

}
