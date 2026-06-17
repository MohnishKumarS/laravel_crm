<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $forms = Form::latest()->paginate(20);
        return view('admin.forms.index', compact('forms'));
    }

    public function create()
    {
        return view('admin.forms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'  => 'required|string',
            'slug'   => 'required|string|unique:forms,slug',
            'fields' => 'required|json',
        ]);

        Form::create([
            'title'  => $request->title,
            'slug'   => $request->slug,
            'fields' => json_decode($request->fields, true),
        ]);

        return redirect()->route('forms.index')->with('success', 'Form created');
    }

    public function show(string $id)
    {
        $form = Form::with('submissions')->findOrFail($id);
        return view('admin.forms.show', compact('form'));
    }

    public function edit(string $id)
    {
        $form = Form::findOrFail($id);
        return view('admin.forms.edit', compact('form'));
    }

    public function update(Request $request, string $id)
    {
        $form = Form::findOrFail($id);

        $request->validate([
            'title'  => 'required|string',
            'slug'   => 'required|string|unique:forms,slug,' . $form->id,
            'fields' => 'required|json',
        ]);
// print_r($request->all());exit;
        $form->update([
            'title'  => $request->title,
            'slug'   => $request->slug,
            'fields' => json_decode($request->fields, true),
        ]);

        return redirect()->route('forms.index')->with('success', 'Form updated');
    }

    public function destroy(string $id)
    {
        Form::findOrFail($id)->delete();
        return redirect()->route('forms.index')->with('success', 'Form deleted');
    }
}
