<?php

namespace App\Console\Commands;

use App\Jobs\SendEvents;
use Illuminate\Console\Command;

class DailyEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send  daily user activity to cAPI ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SendEvents::dispatch();
    }
}
