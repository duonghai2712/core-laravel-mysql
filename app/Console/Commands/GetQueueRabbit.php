<?php

namespace App\Console\Commands;

use App\Jobs\GetDataQueueRabbit;
use Illuminate\Console\Command;

class GetQueueRabbit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:rabbit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lệnh chạy mỗi phút để lấy data từ trên queue Rabbit của app gửi lên';

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
        GetDataQueueRabbit::dispatch('report_data_app');
    }
}
