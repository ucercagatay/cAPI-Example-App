<?php

namespace App\Jobs;

use App\Helpers\cAPIEvents;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEvents implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $counts = array();
        $types = [0,1,2];
        foreach ($types as $type) {
            try
            {
                $result = cAPIEvents::eventMapper($type);
                array_push($counts,$result);
            }
            catch (\Exception $e ){
                throw new \Exception($e);
            }
        }
        echo $result;
    }
}
