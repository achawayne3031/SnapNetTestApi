<?php

namespace App\Policies;

use App\Models\User;
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

    public function update(Users $user, Project $project)
    {
        return $user->id === $project->user_id;
    }

    public function delete(Users $user, Project $project)
    {
        return $user->project_id === $project->user_id;
    }

}
