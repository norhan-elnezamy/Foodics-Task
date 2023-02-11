<?php

namespace App\Jobs;

use App\Notifications\SendEmailNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class UnstableItemExist implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $itemDetails;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($itemDetails)
    {
        $this->itemDetails = $itemDetails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $payload = [
            'subject' => 'Unstable Stock Item',
            'body' => 'There is a stock item (' . $this->itemDetails['name'] . ') quantity become less than 50%!'
        ];
        Notification::route('mail', config('emails.stock.unstable_item'))
            ->notify(new SendEmailNotification($payload));
    }
}
