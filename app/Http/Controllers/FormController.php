<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FormController extends Controller
{
   
    
    public function index()
    {
        $form = Form::firstOrCreate(['id' => 1], ['title' => 'My Form']);
        return view('form.index', compact('form'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|un
            ique:forms,slug',
            'schema' => 'required|array'
        ]);

        // dd($request->schema);   

        $slug = $request->slug ?: Str::slug($request->title) . '-' . random_int(1000, 9999);

        $form = Form::create([
            'title' => $request->title,
            'slug' => $slug,
            'schema' => $request->schema
        ]);

        return response()->json([
            'message' => 'Form saved',
            'form' => $form
        ]);
    }
}
