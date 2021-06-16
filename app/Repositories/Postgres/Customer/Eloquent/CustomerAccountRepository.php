<?php namespace App\Repositories\Postgres\Customer\Eloquent;

use App\Repositories\Eloquent\SingleKeyModelRepository;
use \App\Repositories\Postgres\Customer\CustomerAccountRepositoryInterface;
use \App\Models\Postgres\Customer\CustomerAccount;

class CustomerAccountRepository extends SingleKeyModelRepository implements CustomerAccountRepositoryInterface
{

    public function getBlankModel()
    {
        return new CustomerAccount();
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

}
