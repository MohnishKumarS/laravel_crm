<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

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

        // print_r($form->fields);exit;

        $rules = $this->buildValidationRules($form->fields);

        // print_r($rules);exit;

        $validated = $request->validate($rules);
        
        // print_r($validated);exit;

        $submission = FormSubmission::create([
            'form_id' => $form->id,
            'data'    => $validated,
        ]);

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
    //
}
