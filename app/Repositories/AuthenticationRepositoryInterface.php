<?php

namespace App\Repositories;

use App\Models\AuthenticationBase;

interface AuthenticationRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param string $email
     *
     * @return AuthenticationBase|null
     */
    public function findByEmail($email, $includeDeleted = false);

    /**
     * @param string $username
     *
     * @return AuthenticationBase|null
     */
    public function findByUsername($username, $includeDeleted = false);

    /**
     * @param string $facebookId
     *
     * @return AuthenticationBase|null
     */
    public function findByFacebookId($facebookId);
}
