<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use \App\Repositories\Postgres\Admin\AccountRepositoryInterface;
use \App\Models\Postgres\Admin\Account;
use \App\Repositories\Eloquent\AuthenticationRepository;

class AccountRepository extends AuthenticationRepository implements AccountRepositoryInterface
{
    protected $querySearchTargets = ['name', 'email'];

    public function getBlankModel()
    {
        return new Account();
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

    public function getOneArrayAccountByFilter($filter)
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

    public function getOneObjectAccountByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);
        $data = $query->first();

        return $data;
    }

    public function deleteAllAccountByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);
        $data = $query->delete();

        return $data;
    }

    public function getAllAccountsByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);
        $data = $query->get()->toArray();

        return $data;
    }

    private function filter($filter, &$query)
    {
        if (isset($filter['api_access_token'])) {
            $query = $query->where('accounts.api_access_token', $filter['api_access_token']);
        }

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('accounts.id', $filter['id']);
            } else {
                $query = $query->where('accounts.id', $filter['id']);
            }
        }

        if (isset($filter['is_active'])) {
            $query = $query->where('accounts.is_active', $filter['is_active']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('accounts.project_id', $filter['project_id']);
        }

        if (isset($filter['email'])) {
            $query = $query->where('accounts.email', $filter['email']);
        }

        if (isset($filter['username'])) {
            $key_word = $filter['username'];
            $query = $query->where(function ($query) use ($key_word) {
                $query->orWhere('accounts.email',$key_word);
                $query->orWhere('accounts.username', $key_word);
            });
        }


        if (isset($filter['deleted_at'])) {
            $query = $query->where('accounts.deleted_at', null);
        }

    }

}
