<?php

namespace App\Console\Commands;

use App\Models\Postgres\Admin\Store;
use App\Repositories\Postgres\Admin\StoreRepositoryInterface;
use Illuminate\Console\Command;

class AddPointStore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'store:AddPoint {point}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Thêm điểm cho cửa hàng';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $storeRepository;

    public function __construct(StoreRepositoryInterface $storeRepository)
    {
        parent::__construct();
        $this->storeRepository = $storeRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $point = $this->argument('point');
        if (!empty($point)){
            $stores = $this->storeRepository->getAllStoreByFilter(['deleted_at' => true]);
            if (!empty($stores)){
                $storeInstance = new Store();
                $index = 'id';
                $arrStores = [];
                foreach ($stores as $store){
                    $arrStores[] = [
                        'id' => $store['id'],
                        'total_point' => (int)$point,
                        'current_point' => (int)$point
                    ];
                }

                if (!empty($arrStores)){
                    \Batch::update($storeInstance, $arrStores, $index);
                }
            }
        }
    }
}
