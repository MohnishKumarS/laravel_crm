<?php

namespace App\Http\Controllers\Tool;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tool\StoreEmailTemplateRequest;
use App\Models\Tool\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailTemplateController extends Controller
{
    public function index()
    {
        $templates = EmailTemplate::with('creator')
            ->latest()
            ->get();

        return view(
            'tool.emails.templates.index',
            compact('templates')
        );
    }


    public function create()
    {
        return view(
            'tool.emails.templates.create'
        );
    }

    public function store(StoreEmailTemplateRequest $request)
    {
         $validated = $request->validated();

        // return $validated;
        $validated['created_by'] = Auth::id();

        $created = EmailTemplate::create($validated);
        if ($created) {
            return redirect()->route('emails.templates.index')->with('status', 'success')
                ->with('message', 'Email template created successfully.');
        }

        return redirect()->back()->withInput()->with('status', 'error')->with('message', 'Failed to create email template.');
    }


    public function edit(EmailTemplate $template)
    {
        return view(
            'tool.emails.templates.edit',
            compact('template')
        );
    }


    public function update(Request $request, EmailTemplate $template)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'body' => ['required', 'string'],
            'status' => ['required', 'in:active,inactive'],
        ]);

        // return $request->all();

        $updated =   $template->update($validated);
        if ($updated) {
            return redirect()->route('emails.templates.index')->with('status', 'success')
                ->with('message', 'Email template updated successfully.');
        }

        return redirect()->back()->withInput()->with('status', 'danger')->with('message', 'Failed to updated email template.');
    }

    public function destroy(EmailTemplate $template) {
    
        // if ($template->campaigns()->exists()) {
        //     return back()->with('error','This template is already used in a campaign and cannot be deleted.');
        // }

        $template->delete();

        return redirect()->route('emails.templates.index')->with('status', 'danger')->with('message','Email template deleted successfully.');
    }
}
