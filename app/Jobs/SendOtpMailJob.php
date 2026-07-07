<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendOtpMailJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $backoff = 30;

    public $user;
    public $otp;
    public function __construct(User $user, $otp)
    {
        $this->user = $user;
        $this->otp = $otp;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            Mail::send('emails.otp', [
                'otp' => $this->otp
            ], function ($mail) {
                $mail->to($this->user->email)
                    ->subject('Password Reset OTP');
            });

            Log::info('OTP email sent to ' . $this->user->email);
        } catch (Throwable $e) {

            Log::error('OTP email failed.', [
                'email' => $this->user->email,
                'error' => $e->getMessage(),
            ]);

            throw $e; // Important: allows Laravel to retry the job
        }
    }
}
