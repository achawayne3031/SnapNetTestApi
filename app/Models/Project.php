<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'project';



    protected $fillable = [
        'name',
        'description',
        'status',
        'start_date',
        'end_date'
    ];


    public function employee()
    {
        return $this->hasMany(Employee::class, 'project_id', 'id');
    }



}
