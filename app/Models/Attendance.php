<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Attendance extends Model
{
    use HasFactory, HasApiTokens, Notifiable;
    protected $fillable = [
        'employees_id',
        'schedules_id',
        'at_in',
        'at_out',
        'status',
        'lembur',
        'catatan',
    ];
    public function employees()
    {
        return $this->belongsTo(Employee::class);
    }
    public function schedules()
    {
        return $this->belongsTo(Schedule::class);
    }
}
