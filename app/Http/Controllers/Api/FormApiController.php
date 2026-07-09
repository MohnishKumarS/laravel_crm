<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\FormSubmissionMail;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\User;
use App\Notifications\NewFormSubmissionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;


class FormApiController extends Controller
{

 /**
     * GET /api/forms/{slug}
     * Returns the form schema for Next.js to render.
     */
    public function show(string $slug)
    {
        $form = Form::where('slug', $slug)
            ->where('active', true)
            ->first();

        if (! $form) {
            return response()->json(['message' => 'Form not found'], 404);
        }

        return response()->json([
            'title'  => $form->title,
            'slug'   => $form->slug,
            'fields' => $form->fields,
        ]);
    }

    /**
     * POST /api/forms/{slug}/submit
     * Validates dynamically based on stored field config, then stores the submission.
     */
   public function submit(Request $request, string $slug)
{
    $form = Form::where('slug', $slug)
        ->where('active', true)
        ->first();

    if (! $form) {
        return response()->json(['message' => 'Form not found'], 404);
    }

    $rules = $this->buildValidationRules($form->fields);
    $validated = $request->validate($rules);

    $submission = FormSubmission::create([
        'form_id' => $form->id,
        'data'    => $validated,
    ]);

    if ($form->send_email) {
        $this->sendSubmissionEmails($form, $validated);
    }

    User::where('role','admin')
        ->get()
        ->each(function($admin) use($submission,$form){

            $admin->notify(
                new NewFormSubmissionNotification($submission,$form)
            );

        });

    return response()->json([
        'message' => 'Submitted successfully',
        'id'      => $submission->id,
    ], 201);
}

    /**
     * Builds Laravel validation rules dynamically from the form's field schema.
     */
    protected function buildValidationRules(array $fields): array
    {
        $rules = [];

        foreach ($fields as $field) {
            $name = $field['name'] ?? null;
            if (! $name) {
                continue;
            }

            $fieldRules = [];

            $fieldRules[] = ($field['required'] ?? false) ? 'required' : 'nullable';

            switch ($field['type'] ?? 'text') {
                case 'email':
                    $fieldRules[] = 'email';
                    break;

                case 'mobile':
                    $fieldRules[] = 'string';
                    $fieldRules[] = 'regex:/^[0-9+\-\s()]{7,15}$/';
                    break;

                case 'select':
                    if (! empty($field['options'])) {
                        $fieldRules[] = Rule::in($field['options']);
                    }
                    break;

                case 'checkbox':
                    if (! empty($field['options'])) {
                        // checkbox group → array of selected values
                        $fieldRules = [($field['required'] ?? false) ? 'required' : 'nullable', 'array'];
                        $rules["{$name}.*"] = Rule::in($field['options']);
                    } else {
                        // single checkbox → boolean
                        $fieldRules[] = 'boolean';
                    }
                    break;

                case 'textarea':
                case 'text':
                default:
                    $fieldRules[] = 'string';
                    break;
            }

            $rules[$name] = $fieldRules;
        }

        return $rules;
    }

/**
 * Replaces {{field_name}} placeholders in a template with submitted values.
 */
    protected function renderTemplate(string $template, array $data): string
   {
    return preg_replace_callback('/\{\{\s*([a-z0-9_]+)\s*\}\}/i', function ($matches) use ($data) {
        $key = $matches[1];
        $value = $data[$key] ?? '';

        if (is_array($value)) {
            return implode(', ', $value);
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        return e($value); // escape to prevent HTML injection from user input
    }, $template);
}

protected function sendSubmissionEmails($form, array $data): void
{
    // 1. Confirmation email to the customer
    if ($form->email_field_name && ! empty($data[$form->email_field_name])) {
        $customerEmail = $data[$form->email_field_name];

        if (filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            $subject = $this->renderTemplate($form->customer_subject ?? 'Thank you for your submission', $data);
            $body = $this->renderTemplate($form->customer_template ?? '<p>Thank you for submitting {{title}}.</p>', $data);

            try {
                Mail::to($customerEmail)->send(new FormSubmissionMail($subject, $body));
            } catch (\Exception $e) {
                // print_r($e->getMessage());exit;
                Log::error('Customer form email failed: ' . $e->getMessage());
            }
        }
    }

    // 2. Notification email to admin
    if ($form->admin_email) {
        $subject = $this->renderTemplate($form->admin_subject ?? 'New submission for ' . $form->title, $data);
        $body = $this->renderTemplate($form->admin_template ?? $this->defaultAdminBody($form, $data), $data);

        try {
            Mail::to($form->admin_email)->send(new FormSubmissionMail($subject, $body));
        } catch (\Exception $e) {
            Log::error('Admin form email failed: ' . $e->getMessage());
        }
    }
}

protected function defaultAdminBody($form, array $data): string
{
    $rows = collect($form->fields)->map(function ($field) use ($data) {
        $value = $data[$field['name']] ?? '';
        if (is_array($value)) $value = implode(', ', $value);
        if (is_bool($value)) $value = $value ? 'Yes' : 'No';
        return "<tr><td><strong>" . e($field['label'] ?? $field['name']) . "</strong></td><td>" . e($value) . "</td></tr>";
    })->implode('');

    return "<h3>New submission for {$form->title}</h3><table>{$rows}</table>";
}
    //
}
