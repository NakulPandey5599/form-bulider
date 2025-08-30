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
            'slug' => 'nullable|string|max:255|unique:forms,slug',
            'schema' => 'required|array'
        ]);

        //  $schemaArray = is_string($request->schema)
        //     ? json_decode($request->schema, true)
        //     : $request->schema;

        // // Ensure it has the 'fields' wrapper like your builder produces
        // if (!isset($schemaArray['fields'])) {
        //     $schemaArray = ['fields' => $schemaArray];
        // }

        $slug = $request->slug ?: Str::slug($request->title) . '-' . random_int(1000, 9999);

        $form = Form::create([
            'title' => $request->title,
            'slug' => (string) Str::uuid(),
            'schema' => $request->schema
        ]);
         $link = route('form.public', $form->slug);

    // return redirect()->back()->with('form_link', $link);
        return response()->json([
            'message' => 'Form saved',
            'form' => $form
        ]);
    }

    public function preview($id)
    {   
    $form = Form::findOrFail($id);
    
    return view('form.preview', [
        'form' => $form,
        'schema' => $form->schema // already array
    ]);

    }

    public function public($slug)
    {
        $form = Form::where('slug', $slug)->firstOrFail();
        $schema = $form->schema;

        return view('form.preview', compact('form', 'schema'));
    }


}
