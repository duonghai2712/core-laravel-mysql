<?php
namespace App\Repositories\Postgres\Admin;

use App\Repositories\SingleKeyModelRepositoryInterface;

interface OauthRefreshTokenRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param int    $id
     * @param string $accessTokenId
     *
     * @return mixed
     */
    public function updateOldAccessTokenRevoke($id, $accessTokenId);
}
