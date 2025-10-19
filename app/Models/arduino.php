<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Arduino extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nombre', 'ip', 'code'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function triggers()
    {
        return $this->hasMany(Trigger::class);
    }
}