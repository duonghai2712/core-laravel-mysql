<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Repositories\Postgres\Admin\AccountPasswordResetRepositoryInterface;

class AccountPasswordResetRepository extends PasswordResettableRepository implements AccountPasswordResetRepositoryInterface
{
    protected $tableName = 'admin_password_resets';
}
