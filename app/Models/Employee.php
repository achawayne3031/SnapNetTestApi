<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employee';


    protected $fillable = [
        'name',
        'project_id',
        'position',
        'email',
    ];



    public function project()
    {
        return $this->hasMany(Project::class, 'id', 'project_id');
    }
    




}
