<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Models\Postgres\Admin\AdminDeviceImage;
use App\Models\Postgres\Store\StoreAccount;
use App\Models\Postgres\Store\StoreDeviceCollection;
use \App\Repositories\Postgres\Admin\DeviceRepositoryInterface;
use \App\Models\Postgres\Admin\Device;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

class DeviceRepository extends SingleKeyModelRepository implements DeviceRepositoryInterface
{

    public function getBlankModel()
    {
        return new Device();
    }

    public function rules()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
        ];
    }

    public function incrementByFilter($filter, $field, $number)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $query->increment($field, $number);

        return true;
    }

    public function decrementByFilter($filter, $field, $number)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $query->decrement($field, $number);

        return true;
    }

    public function getOneArrayDeviceWithStoreAndBranchByFilter($filter)
    {
        $query = $this->withStoreAndBranch();

        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function getOneArrayDeviceByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function getOneArrayDeviceForAppByFilter($filter)
    {
        $query = $this->withRank();

        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function getAllDeviceByFilter($filter)
    {
        $query = $this->withBranch();

        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    public function countAllDeviceByFilter($filter)
    {
        $query = $this->withBranch();

        $this->filter($filter, $query);

        $data = $query->count();

        return $data;
    }

    public function getDetailDeviceWithCollectionSelfByFilter($filter)
    {
        $query = $this->withCollection();

        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function getOneObjectDeviceByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);
        $data = $query->first();

        return $data;
    }

    public function deleteAllDeviceByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);
        $data = $query->delete();

        return $data;
    }

    public function updateAllDeviceByFilter($filter, $params)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);
        $data = $query->update($params);

        return $data;
    }

    public function getListDeviceByFilter($limit, $filter)
    {
        $query = $this->withStoreAndBranch();

        $this->filter($filter, $query);

        $data = $query->paginate($limit)->toArray();

        return $data;
    }

    public function getAllDeviceWithCollectionByFilter($filter)
    {
        $query = $this->withCountCollection();

        $this->filter($filter, $query);
        $data = $query->get()->toArray();

        return $data;
    }

    public function getAllDeviceWithRankByFilter($filter)
    {
        $query = $this->withRank();

        $this->filter($filter, $query);
        $data = $query->get()->toArray();

        return $data;
    }

    private function withBranch()
    {
        $query = $this->getBlankModel()->select([
            "devices.*",

        ])->with(['branch' => function($query){
            $query->select('branches.id', 'branches.rank_id');
        }])
        ->withCount(['collections']);

        return $query;
    }

    private function withCountCollection()
    {
        $query = $this->getBlankModel()->select([
            "devices.*",

        ])
        ->with(['branch' => function($query){
            $query->select('branches.id', 'branches.rank_id');
        }]);

        return $query;
    }

    private function withRank()
    {
        $query = $this->getBlankModel()->select([
            "devices.*",
        ])
            ->with(['branch' => function($query){
                $query->select('branches.id', 'branches.rank_id')
                    ->with(['rank' => function($query){
                        $query->select('ranks.id', 'ranks.name', 'ranks.coefficient');
                    }]);
            }]);

        return $query;
    }

    private function withStoreAndBranch()
    {
        $query = $this->getBlankModel()->select([
            "devices.id",
            "devices.name",
            "devices.description",
            "devices.device_code",
            "devices.own",
            "devices.is_active",
            "devices.status",
            "devices.active_code",
            "devices.device_token",
            "devices.block_ads",
            "devices.store_id",
            "devices.branch_id",
            "devices.account_id",
            "devices.project_id",
            "devices.total_time_admin",
            "devices.total_time_store",
            "devices.total_time_empty",
        ])->with(['store' => function($query){
                $query->select([
                    "stores.id",
                    "stores.name",
                    "stores.address",
                    "stores.is_active",
                    "stores.account_id",
                    "stores.district_id",
                    "stores.province_id",
                ])
                    ->with(['account' => function($query){
                        $query->Where('store_accounts.role', StoreAccount::ADMIN)->Where('store_accounts.group_store_account_id', null)
                            ->select('store_accounts.id', 'store_accounts.store_id', 'store_accounts.representative', 'store_accounts.username', 'store_accounts.email', 'store_accounts.profile_collection_id')
                            ->with(['image' => function($query){
                                $query->select('collections.id', 'collections.name', 'collections.type', 'collections.mimes', 'collections.source', 'collections.source_thumb');
                            }]);
                    }])
                    ->with(['createdBy' => function($query){
                    $query->leftJoin('images', 'images.id', '=', 'accounts.profile_image_id')
                        ->select('accounts.id', 'accounts.name', 'accounts.username', 'accounts.email', 'images.source', 'images.source_thumb', 'images.mimes');
                    }])
                    ->with(['province' => function($query){
                        $query->select('provinces.id', 'provinces.name');
                    }])
                    ->with(['district' => function($query){
                        $query->select('districts.id', 'districts.name');
                    }]);
            }])
            ->with(['branch' => function($query){
                $query->select([
                    "branches.id",
                    "branches.name",
                    "branches.contact",
                    "branches.phone_number",
                    "branches.address",
                    "branches.account_id",
                    "branches.store_id",
                    "branches.rank_id",
                    "branches.district_id",
                    "branches.province_id",
                ])
                    ->with(['account' => function($query){
                        $query->leftJoin('images', 'images.id', '=', 'accounts.profile_image_id')
                            ->select('accounts.id', 'accounts.name', 'accounts.username', 'accounts.email', 'images.source', 'images.source_thumb');
                    }])
                    ->with(['province' => function($query){
                        $query->select('provinces.id', 'provinces.name');
                    }])
                    ->with(['district' => function($query){
                        $query->select('districts.id', 'districts.name');
                    }])
                    ->with(['rank' => function($query){
                        $query->select([
                            "ranks.id",
                            "ranks.name",
                            "ranks.coefficient",
                        ]);
                    }]);
            }]);
        return $query;
    }

    private function withCollection()
    {
        $query = $this->getBlankModel()->select([
            "devices.id",
            "devices.name",
            "devices.description",
            "devices.device_code",
            "devices.own",
            "devices.is_active",
            "devices.status",
            "devices.active_code",
            "devices.device_token",
            "devices.store_id",
            "devices.branch_id",
            "devices.block_ads",
            "devices.account_id",
            "devices.project_id",
            "devices.total_time_empty",
            "devices.total_time_admin",
            "devices.total_time_store",
        ])
            ->with(['branch' => function($query){
                $query->select('branches.id', 'branches.name');
            }])
            ->with(['storeCollection' => function($query){
                $query->select('collections.id', 'collections.name', 'collections.mimes', 'collections.source', 'collections.source_thumb',  'store_device_collections.id as store_device_collection_id',  'store_device_collections.type',  'store_device_collections.position',  'store_device_collections.second',  'store_device_collections.volume')->orderBy('position', 'asc');
        }])
            ->with(['adminImage' => function($query){
                $query->select('images.id', 'images.name', 'images.type', 'images.mimes', 'images.source', 'images.source_thumb',  'admin_device_images.id as admin_device_image_id',  'admin_device_images.type',  'admin_device_images.position',  'admin_device_images.second',  'admin_device_images.volume')->orderBy('position', 'asc');
            }]);
        return $query;
    }

    private function changeTime($params, &$query)
    {
        if (isset($params['total_time_admin'])){
            if (isset($params['total_time_admin']['increment'])){
                $query->increment('total_time_admin', $params['total_time_admin']['increment']);
            }

            if (isset($params['total_time_admin']['decrement'])){
                $query->decrement('total_time_admin', $params['total_time_admin']['decrement']);
            }
        }

        if (isset($params['total_time_store'])){
            if (isset($params['total_time_store']['increment'])){
                $query->increment('total_time_store', $params['total_time_store']['increment']);
            }

            if (isset($params['total_time_store']['decrement'])){
                $query->decrement('total_time_store', $params['total_time_store']['decrement']);
            }
        }


        if (isset($params['total_time_empty'])){
            if (isset($params['total_time_empty']['increment'])){
                $query->increment('total_time_empty', $params['total_time_empty']['increment']);
            }

            if (isset($params['total_time_empty']['decrement'])){
                $query->decrement('total_time_empty', $params['total_time_empty']['decrement']);
            }
        }
    }

    private function filter($filter, &$query)
    {

        if (isset($filter['key_word'])) {
            $query = $query->search($filter['key_word']);
        }

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('devices.id', $filter['id']);
            } else {
                $query = $query->where('devices.id', $filter['id']);
            }
        }

        if (isset($filter['device_not_in_id'])) {
            if (is_array($filter['device_not_in_id'])) {
                $query = $query->whereNotIn('devices.id', $filter['device_not_in_id']);
            } else {
                $query = $query->where('devices.id', '!=', $filter['device_not_in_id']);
            }
        }

        if (isset($filter['store_id_not_in'])) {
            if (is_array($filter['store_id_not_in'])) {
                $query = $query->whereNotIn('devices.store_id', $filter['store_id_not_in']);
            } else {
                $query = $query->where('devices.store_id', '!=', $filter['store_id_not_in']);
            }
        }

        if (isset($filter['deleted_at'])) {
            $query = $query->where('devices.deleted_at', null);
        }

        if (isset($filter['isDelete'])){
            $query = $query->where('devices.deleted_at', '!=', null);
        }

        if (isset($filter['direction']) && isset($filter['order'])) {
            $query = $query->orderBy('devices.' . $filter['order'], $filter['direction']);
        }

        if (isset($filter['branch_id'])) {
            if (is_array($filter['branch_id'])) {
                $query = $query->whereIn('devices.branch_id', $filter['branch_id']);
            } else {
                $query = $query->where('devices.branch_id', $filter['branch_id']);
            }
        }


        if (isset($filter['store_id'])) {
            if (is_array($filter['store_id'])) {
                $query = $query->whereIn('devices.store_id', $filter['store_id']);
            } else {
                $query = $query->where('devices.store_id', $filter['store_id']);
            }
        }

        if (isset($filter['active_code'])) {
            $query = $query->where('devices.active_code', $filter['active_code']);
        }

        if (isset($filter['total_time_empty'])) {
            $query = $query->where('devices.total_time_empty', '>=', $filter['total_time_empty']);
        }

        if (isset($filter['device_token'])) {
            $query = $query->where('devices.device_token', $filter['device_token']);
        }

        if (isset($filter['device_code'])) {
            if (is_array($filter['device_code'])) {
                $query = $query->whereIn('devices.device_code', $filter['device_code']);
            } else {
                $query = $query->where('devices.device_code', $filter['device_code']);
            }
        }

        if (isset($filter['not_in_device_code'])) {
            if (is_array($filter['not_in_device_code'])) {
                $query = $query->whereNotIn('devices.device_code', $filter['not_in_device_code']);
            } else {
                $query = $query->where('devices.device_code', '!=', $filter['not_in_device_code']);
            }
        }

        if (isset($filter['account_id'])) {
            $query = $query->where('devices.account_id', $filter['account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('devices.project_id', $filter['project_id']);
        }

    }
}
