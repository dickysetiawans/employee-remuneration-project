<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskRecord extends Model
{
    protected $fillable = [
        'employee_name',
        'task_description',
        'date',
        'hours_spent',
        'hourly_rate',
        'additional_charges',
        'total_remuneration',
    ];
}
