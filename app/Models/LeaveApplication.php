<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    // Add this fillable array to allow mass assignment
    protected $fillable = [
        'user_id',
        'leave_type',
        'from_date',
        'to_date',
        'duration',
        'reason',
        'attachment',
        'status',
    ];
}