<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trigger extends Model
{
    use HasFactory;

    protected $fillable = ['arduino_id', 'nombre', 'contexto'];

    public function arduino()
    {
        return $this->belongsTo(Arduino::class);
    }
}