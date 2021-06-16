<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Repositories\Postgres\Admin\PasswordResettableRepositoryInterface;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;

class PasswordResettableRepository extends DatabaseTokenRepository implements PasswordResettableRepositoryInterface
{
    protected $tableName = 'password_resets';

    protected $hashKey   = 'random';

    protected $expires   = 1440;

    public function __construct()
    {
        parent::__construct($this->getDatabaseConnection(), app()['hash'], $this->tableName, $this->hashKey, $this->expires);
    }

    protected function getDatabaseConnection()
    {
        return $connection = app()['db']->connection();
    }
}
