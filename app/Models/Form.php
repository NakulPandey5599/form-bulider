<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = ['title','slug','schema','is_active'];
    protected $casts = ['schema' => 'array', 'is_active' => 'boolean'];

    public function submissions() {
        return $this->hasMany(FormSubmission::class);
    }
}
