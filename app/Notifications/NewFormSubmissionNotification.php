<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewFormSubmissionNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected $submission;
    protected $form;

    public function __construct($submission, $form)
    {
        $this->submission = $submission;
        $this->form = $form;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }


    public function toDatabase($notifiable)
    {
        return [

            'title' => 'New Submission Received - ' . ucwords($this->form->title),

            // 'message' => 'New enquiry received from ' . $this->submission->data['name'],

            'form_name' => $this->form->title,

            'form_id'  => $this->form->id,

            'submission_id' => $this->submission->id,

            'date' => $this->submission->created_at,

            // 'name' => $this->submission->data['name'] ?? '',

            // 'email' => $this->submission->data['email'] ?? '',

            // 'phone' => $this->submission->data['phone'] ?? '',

            // 'icon' => 'fa-user-plus',

            'url' => route('forms.submissions', $this->form->id),
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //         ->line('The introduction to the notification.')
    //         ->action('Notification Action', url('/'))
    //         ->line('Thank you for using our application!');
    // }

    // /**
    //  * Get the array representation of the notification.
    //  *
    //  * @return array<string, mixed>
    //  */
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         //
    //     ];
    // }
}
