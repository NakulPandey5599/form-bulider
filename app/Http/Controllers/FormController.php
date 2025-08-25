<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
   
    
    public function index()
    {
        $form = Form::firstOrCreate(['id' => 1], ['title' => 'My Form']);
        return view('form.index', compact('form'));
    }

    public function save(Request $request)
    {
        $form = Form::first();
        $form->update([
            'fields' => $request->fields
        ]);

        return response()->json(['success' => true]);
    }
}
