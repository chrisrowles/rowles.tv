<?php

namespace Rowles\Console\Commands\Stripe;

use Illuminate\Console\Command;
use Rowles\Models\SubscriptionPackage;

class GetSubscriptionsFromStripeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:get-subscriptions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch subscriptions in Stripe and update subscription packages table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
//        $package = new SubscriptionPackage();
//        $package->product_id = "prod_J4tmNE95F7eNqC";
//        $package->name = "1 Month Recurring Subscription";
//        $package->description = "A 1-month recurring subscription, includes full access to all videos and new uploads, and unlimited downloads.";
//        $package->price = "A 1-month recurring subscription, includes full access to all videos and new uploads, and unlimited downloads.";
//        $package->interval = "month";
//
//        if ($package->save()) {
//            return 0;
//        }

        return 1;
    }
}
