<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Setting extends Model
{
    use HasFactory, HasApiTokens, Notifiable;
    protected $fillable = [
        'company',
        'leader',
        'address',
        'logo',
        'ttd',
        'lokasi',
        'lat',
        'lng',
        'radius',
    ];
}
