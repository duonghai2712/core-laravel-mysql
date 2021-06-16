<?php namespace App\Services\Postgres\Store;

use App\Services\BaseServiceInterface;

interface FileUploadCollectionServiceInterface extends BaseServiceInterface
{
    public function upload($configKey, $file, $storeAccount, $type);
}
