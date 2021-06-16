<?php

namespace App\Repositories\Eloquent;

use App\Repositories\AuthenticationRepositoryInterface;
use App\Models\AuthenticationBase;

class AuthenticationRepository extends SingleKeyModelRepository implements AuthenticationRepositoryInterface
{
    public function getBlankModel()
    {
        return new AuthenticationBase();
    }

    public function findByEmail($email, $includeDeleted = false)
    {
        $className = $this->getModelClassName();

        if( $includeDeleted ) {
            return $className::withTrashed()->where('email', $email)->first();
        } else {
            return $className::whereEmail($email)->first();
        }
    }

    public function findByUsername($username, $includeDeleted = false)
    {
        $className = $this->getModelClassName();

        if( $includeDeleted ) {
            return $className::withTrashed()->where('username', $username)->first();
        } else {
            return $className::whereUsername($username)->first();
        }
    }

    public function findByFacebookId($facebookId)
    {
        $className = $this->getModelClassName();

        return $className::whereFacebookId($facebookId)->first();
    }
}
