<?php

namespace App\Http\Controllers\Tool;

use App\Http\Controllers\Controller;
use App\Jobs\SendBulkEmailJob;
use App\Models\Marketplace\ShopUser;
use App\Models\Tool\EmailCampaign;
use App\Models\Tool\EmailCampaignRecipient;
use App\Models\Tool\EmailTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmailCampaignController extends Controller
{
    public function index()
    {
        $campaigns = EmailCampaign::with(['template', 'creator',])
            ->latest()
            ->get();

        return view('tool.emails.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        $templates = EmailTemplate::query()
            ->where('status', 'active')
            ->latest()
            ->get();

        // $senders = EmailSender::query()
        //     ->where('status', 'active')
        //     ->orderBy('name')
        //     ->get();

        return view(
            'tool.emails.campaigns.create',
            compact('templates')
        );
    }

    /**
     * AJAX users
     */
    public function users(Request $request)
    {

        $users = User::query()
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return response()->json([
            'data' => $users,
        ]);
    }


    /**
     * AJAX sellers
     */
    public function sellers(Request $request)
    {
        $sellers = ShopUser::select('id', 'username', 'email','first_name')
            ->whereNotNull('email')
            ->where('group_id', 4)
            ->get();

        return response()->json([
            'data' => $sellers,
        ]);
    }


    /**
     * Store campaign
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'template_id' => ['required', 'exists:email_templates,id'],
            'sender_email' => ['required', 'email', 'max:255'],
            'recipient_type' => ['required', 'in:users,sellers,custom'],
            'recipient_ids' => ['nullable', 'array'],
            'recipient_ids.*' => ['integer'],
            'custom_emails' => ['nullable', 'string'],
        ]);

        // return $validated['recipient_type'];


        /*
        |--------------------------------------------------------------------------
        | Validate recipients
        |--------------------------------------------------------------------------
        */

        if ($validated['recipient_type'] === 'users' && empty($validated['recipient_ids'])) {
            return back()->withInput()->withErrors(['recipient_ids' => 'Please select at least one user.']);
        }

        if ($validated['recipient_type'] === 'sellers' && empty($validated['recipient_ids'])) {
            return back()->withInput()->withErrors(['recipient_ids' => 'Please select at least one seller.']);
        }

        if ($validated['recipient_type'] === 'custom' && empty($validated['custom_emails'])) {
            return back()->withInput()->withErrors(['custom_emails' => 'Please enter at least one email.']);
        }


        /*
        |--------------------------------------------------------------------------
        | Get selected senders
        |--------------------------------------------------------------------------
        */
        // $senders = EmailSender::query()
        //     ->whereIn(
        //         'id',
        //         $validated['sender_ids']
        //     )
        //     ->where('status', 'active')
        //     ->get();


        // if ($senders->isEmpty()) {

        //     return back()
        //         ->withInput()
        //         ->withErrors([
        //             'sender_ids' =>
        //             'No active sender email selected.',
        //         ]);
        // }


        /*
        |--------------------------------------------------------------------------
        | Create campaign
        |--------------------------------------------------------------------------
        */

        $campaign = DB::transaction(function () use ($validated) {

            // return 'adasd';

            $campaign = EmailCampaign::create([
                'name' => $validated['name'],
                'template_id' => $validated['template_id'],
                'recipient_type' => $validated['recipient_type'],
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);


            $recipients = [];


            /*
            | Users
            |--------------------------------------------------------------------------
            */

            if ($validated['recipient_type'] === 'users') {
                $users = User::query()
                    ->whereIn('id', $validated['recipient_ids'])
                    ->whereNotNull('email')
                    ->select(['id', 'name', 'email'])
                    ->get();

                foreach ($users as $user) {
                    $recipients[] = [
                        'campaign_id' => $campaign->id,
                        'recipient_type' => 'user',
                        'recipient_id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'sender_email' => $validated['sender_email'],
                        'status' => 'queued',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Sellers
            |--------------------------------------------------------------------------
            */

            if ($validated['recipient_type'] === 'sellers') {
                $sellers = ShopUser::query()
                    ->whereIn('id', $validated['recipient_ids'])
                    ->whereNotNull('email')
                    ->select(['id', 'first_name', 'email'])
                    ->get();

                foreach ($sellers as $seller) {
                    $recipients[] = [
                        'campaign_id' => $campaign->id,
                        'recipient_type' => 'seller',
                        'recipient_id' => $seller->id,
                        'name' => $seller->first_name,
                        'email' => $seller->email,
                        'sender_email' => $validated['sender_email'],
                        'status' => 'queued',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Custom Emails
            |--------------------------------------------------------------------------
            */
            if ($validated['recipient_type'] === 'custom') {
                /*
                Supports:

                john@gmail.com
                test@gmail.com

                OR

                john@gmail.com,test@gmail.com
                */

                $emails = preg_split('/[\s,;]+/', $validated['custom_emails']);

                // return $emails;

                $emails = collect($emails)
                    ->map(function ($email) {
                        return strtolower(trim($email));
                    })
                    ->filter()
                    ->unique()
                    ->values();

                //    return $emails;

                foreach ($emails as $email) {
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        continue;
                    }

                    $recipients[] = [
                        'campaign_id' => $campaign->id,
                        'recipient_type' => 'custom',
                        'recipient_id' => null,
                        'name' => null,
                        'email' => $email,
                        'sender_email' => $validated['sender_email'],
                        'status' => 'queued',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            // return $recipients;

            /*
            |--------------------------------------------------------------------------
            | Insert recipients
            |--------------------------------------------------------------------------
            */

            if (!empty($recipients)) {
                foreach (array_chunk($recipients, 500) as $chunk) {
                    EmailCampaignRecipient::insert($chunk);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Update campaign count
            |--------------------------------------------------------------------------
            */

            $count = count($recipients);

            if ($count === 0) {
                throw new \Exception('No valid recipients found.');
            }

            $campaign->update([
                'total_recipients' => $count,
                'queued_count' => $count,
                'status' => 'queued',
            ]);

            return $campaign;
        });

        // return $campaign;


        /*
        |--------------------------------------------------------------------------
        | Dispatch jobs
        |--------------------------------------------------------------------------
        */

        // $campaign->recipients()
        //     ->where('status', 'queued')
        //     ->select('id')
        //     ->chunkById(100, function ($recipients) {
        //         foreach ($recipients as $recipient) {
        //             SendBulkEmailJob::dispatch($recipient->id)
        //                 ->onQueue('emails');
        //         }
        //     });

        // $campaign->update([
        //     'status' => 'processing',
        //     'started_at' => now(),
        // ]);

        return redirect()->route('emails.campaigns.show', $campaign)->with('status', 'success')->with('message', 'Campaign has been queued successfully.');
    }


    /**
     * Campaign details
     */
    public function show(EmailCampaign $campaign)
    {
        $campaign->load('template');

        $recipients = $campaign->recipients()->latest()->get();

        return view('tool.emails.campaigns.show', compact('campaign', 'recipients'));
    }


    /**
     * Send / queue campaign
     *
     * This method can be used if you prefer creating
     * the campaign first and sending separately.
     */
    public function send(EmailCampaign $campaign)
    {
        $campaign->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);


        $campaign->recipients()
            ->where('status', 'queued')
            ->select('id')
            ->chunkById(100, function ($recipients) {
                foreach ($recipients as $recipient) {
                    SendBulkEmailJob::dispatch($recipient->id)
                        ->onQueue('emails');
                }
            });


        return back()->with('message','Campaign has been queued.')->with('status','success');
    }


    /**
     * Retry failed recipients
     */
    public function retry(EmailCampaign $campaign)
    {
        $campaign->recipients()
            ->where('status', 'failed')
            ->update([
                'status' => 'queued',
                'error_message' => null,
            ]);


        $failedRecipients = $campaign->recipients()
            ->where('status', 'queued')
            ->select('id')
            ->get();


        foreach ($failedRecipients as $recipient) {
            SendBulkEmailJob::dispatch(
                $recipient->id
            )->onQueue('emails');
        }


        $campaign->update(['status' => 'processing']);


        return back()->with(
            'success',
            'Failed emails have been queued again.'
        );
    }


    /**
     * Delete campaign
     */
    public function destroy(EmailCampaign $campaign)
    {

        // return $campaign;
        $campaign->delete();

        return redirect()->route('emails.campaigns.index')
            ->with('message', 'Campaign deleted successfully.')->with('status', 'success');
    }
}
