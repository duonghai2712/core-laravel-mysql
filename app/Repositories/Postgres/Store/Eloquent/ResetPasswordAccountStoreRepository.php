<?php namespace App\Repositories\Postgres\Store\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Store\ResetPasswordAccountStoreRepositoryInterface;
use \App\Models\Postgres\Store\ResetPasswordAccountStore;

class ResetPasswordAccountStoreRepository extends SingleKeyModelRepository implements ResetPasswordAccountStoreRepositoryInterface
{

    public function getBlankModel()
    {
        return new ResetPasswordAccountStore();
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

    public function getOneObjectByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);
        $data = $query->first();

        return $data;
    }

    public function createOrUpdateByFilter($filter, $data)
    {
        ResetPasswordAccountStore::updateOrCreate($filter, $data);

        return true;
    }

    public function getOneArrayByFilter($filter)
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

    public function updateResetPasswordByFilter($filter, $params)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);
        $data = $query->update($params);

        return $data;
    }

    private function filter($filter, &$query)
    {

        if (isset($filter['email'])) {
            $query = $query->where('reset_password_account_stores.email', $filter['email']);
        }

        if (isset($filter['token'])) {
            $query = $query->where('reset_password_account_stores.token', $filter['token']);
        }
    }
}
