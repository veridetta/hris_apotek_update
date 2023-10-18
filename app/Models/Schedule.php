<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Schedule extends Model
{
    use HasFactory, HasApiTokens, Notifiable;
    protected $fillable = [
        'employees_id',
        'shifts_id',
        'dates',
        'izin', //aktif atau Nonaktif
    ];
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
