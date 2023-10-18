<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Payment extends Model
{
    use HasFactory,  HasApiTokens, Notifiable;
    protected $fillable = [
        'employees_id',
        'lembur',
        'month',
        'year',
        'telat',
        'tidak_masuk',
        'makan',
        'transport',
        'potongan',
        'payment'
    ];
    public function employees()
    {
        return $this->belongsTo(Employee::class);
    }
    
}
