<?php

namespace App\Jobs;

use App\Services\Production\CommonService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetDataQueueRabbit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $queue_name;
    protected $commonService;

    public function __construct($queue_name)
    {
        $this->queue_name = $queue_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CommonService $commonService)
    {
        $queue_name = $this->queue_name;
        $this->commonService = $commonService;

        if (!empty($queue_name)){
            $this->commonService->getMessageQueueRabbit($queue_name);
        }

        return;
    }
}
