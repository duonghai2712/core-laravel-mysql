<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Admin\ResetPasswordAccountRepositoryInterface;
use \App\Models\Postgres\Admin\ResetPasswordAccount;

class ResetPasswordAccountRepository extends SingleKeyModelRepository implements ResetPasswordAccountRepositoryInterface
{

    public function getBlankModel()
    {
        return new ResetPasswordAccount();
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
        ResetPasswordAccount::updateOrCreate($filter, $data);

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
            $query = $query->where('reset_password_accounts.email', $filter['email']);
        }

        if (isset($filter['token'])) {
            $query = $query->where('reset_password_accounts.token', $filter['token']);
        }
    }
}
