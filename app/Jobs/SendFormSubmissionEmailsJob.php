<?php

namespace App\Jobs;

use App\Mail\FormSubmissionMail;
use App\Models\Form;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendFormSubmissionEmailsJob implements ShouldQueue
{
    use Queueable;

    //     public $tries = 3;
    // public $backoff = 30;

    protected $form;
    protected $data;
    public function __construct(Form $form, array $data)
    {
        $this->form = $form;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            // Customer Email
            if (
                $this->form->email_field_name &&
                !empty($this->data[$this->form->email_field_name])
            ) {

                Log::info('Entered submission email block');

                $email = $this->data[$this->form->email_field_name];

                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                    $subject = $this->renderTemplate(
                        $this->form->customer_subject ?? 'Thank you for your submission',
                        $this->data
                    );

                    $body = $this->renderTemplate(
                        $this->form->customer_template ?? '<p>Thank you for reaching out! Your submission has been received successfully.Our team has received your request and will review it shortly. We will get in touch with you soon.</p>',
                        $this->data
                    );

                    Mail::to($email)->send(new FormSubmissionMail($subject, $body));

                    Log::info('Submission email sent to :' . $email);
                }
            }

            // Admin Email
            if ($this->form->admin_email) {

                $subject = $this->renderTemplate(
                    $this->form->admin_subject ?: 'New submission for ' . $this->form->title,
                    $this->data
                );

                $body = $this->renderTemplate(
                    $this->form->admin_template ?: $this->defaultAdminBody($this->form, $this->data),
                    $this->data
                );

                Mail::to($this->form->admin_email)
                    ->send(new FormSubmissionMail($subject, $body));

                Log::info('Admin Submission email sent to :' . $this->form->admin_email);
            }
        } catch (Throwable $e) {

            Log::error('Form email failed', [
                'form_name' => $this->form->title,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    protected function renderTemplate(string $template, array $data): string
    {
        return preg_replace_callback(
            '/\{\{\s*([a-z0-9_]+)\s*\}\}/i',
            function ($matches) use ($data) {

                $value = $data[$matches[1]] ?? '';

                if (is_array($value)) {
                    return implode(', ', $value);
                }

                if (is_bool($value)) {
                    return $value ? 'Yes' : 'No';
                }

                return e($value);
            },
            $template
        );
    }

    protected function defaultAdminBody($form, array $data): string
    {
        $rows = collect($form->fields)->map(function ($field) use ($data) {
            $value = $data[$field['name']] ?? '';
            if (is_array($value)) $value = implode(', ', $value);
            if (is_bool($value)) $value = $value ? 'Yes' : 'No';
            return "<tr><td><strong>" . e($field['label'] ?? $field['name']) . "</strong></td><td>" . e($value) . "</td></tr>";
        })->implode('');

        return '
        <h2 style="margin-bottom:10px;">New Form Submission</h2>

        <p>A new submission has been received for the <strong>' . e($form->title) . '</strong> form.</p>

        <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;border:1px solid #ddd;">
            ' . $rows . '
        </table>

        <p style="margin-top:20px;">
            This is an automated email from <strong>' . e(config('app.name')) . '</strong>.
        </p>';
    }
}
