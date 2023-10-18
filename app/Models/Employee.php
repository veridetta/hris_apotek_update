<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'jabatans_id',
        'rfid',
        'name',
        'jk',
        'ttl',
        'faceid',
        'facereq',
    ];
    public function jabatans()
    {
        return $this->belongsTo(Jabatan::class);
    }
    public function schedules()
    {
        return $this->belongsTo(Schedule::class);
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
