<?php

namespace App\Console\Commands;

use App\Elibs\eFunction;
use App\Models\Postgres\Admin\Account;
use App\Models\Postgres\Admin\Image;
use App\Repositories\Postgres\Admin\AccountRepositoryInterface;
use App\Repositories\Postgres\Admin\OwnerRepositoryInterface;
use Illuminate\Console\Command;

class AddCollectionOwner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:collection';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create collection owner ants';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $accountRepository;
    protected $ownerRepository;

    public function __construct(AccountRepositoryInterface $accountRepository, OwnerRepositoryInterface $ownerRepository)
    {
        parent::__construct();
        $this->accountRepository = $accountRepository;
        $this->ownerRepository = $ownerRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $accounts = $this->accountRepository->getAllAccountsByFilter(['is_active' => Account::IS_ACTIVE, 'deleted_at' => true]);
        if (!empty($accounts)){
            $accounts = collect($accounts)->keyBy('project_id')->values()->toArray();
            $collectionOwners = [];
            foreach ($accounts as $account){
                $collectionOwners[] = [
                    'name' => Image::ANTS_MEDIA,
                    'slug' => eFunction::generateSlug(Image::ANTS_MEDIA, '-'),
                    'level' => Image::ANT,
                    'project_id' => $account['project_id'],
                    'account_id' => $account['id'],
                    'created_at' => eFunction::getDateTimeNow(),
                    'updated_at' => eFunction::getDateTimeNow()
                ];
            }

            if (!empty($collectionOwners)){
                $this->ownerRepository->insertMulti($collectionOwners);
            }
        }
    }
}
