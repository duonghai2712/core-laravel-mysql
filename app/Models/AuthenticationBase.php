<?php

namespace App\Models;

use App\Models\Postgres\District;
use App\Models\Postgres\Admin\Image;
use App\Models\Postgres\Province;
use App\Models\Postgres\Store\Collection;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticationContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class AuthenticationBase extends LocaleStorableBase implements AuthenticationContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    public function setPasswordAttribute($password)
    {
        if (empty($password)) {
            $this->attributes['password'] = '';
        } else {
            $this->attributes['password'] = \Hash::make($password);
        }
    }

    public function setAPIAccessToken()
    {
        $user = null;
        do {
            $code = md5(\Hash::make($this->id.$this->email.$this->password.time().mt_rand()));
            $user = static::whereApiAccessToken($code)->first();
        } while (isset($user));
        $this->api_access_token = $code;

        return $code;
    }

    // Relation

    public function profileImage()
    {
        return $this->belongsTo(Image::class, 'profile_image_id', 'id');
    }

    public function profileStoreImage()
    {
        return $this->belongsTo(Collection::class, 'profile_collection_id', 'id');
    }

    public function province(){
        return $this->belongsTo(Province::class,'province_id','id');
    }

    public function district(){
        return $this->belongsTo(District::class,'district_id','id');
    }


    public function getProfileImageUrl($width = 0, $height = 0)
    {
        if ($this->profile_image_id == 0) {
            return \URLHelper::asset('img/user.png', 'common');
        }
        if ($width == 0 && $height == 0) {
            return $this->profileImage->url;
        } else {
            return $this->profileImage->url;
        }
    }
}
