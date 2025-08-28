<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $fillable = ['form_id','data','meta'];
    protected $casts = ['data' => 'array', 'meta' => 'array'];

    public function form() {
        return $this->belongsTo(Form::class);
    }
}
