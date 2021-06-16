<?php
namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface OauthAccessTokenRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param int $id
     * @param int $userId
     * @param int $clientId
     *
     * @return mixed
     */
    public function updateOldTokenRevoke($id, $userId, $clientId);
}
