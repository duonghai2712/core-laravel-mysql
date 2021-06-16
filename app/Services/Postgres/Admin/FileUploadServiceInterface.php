<?php

namespace App\Services\Postgres\Admin;

use App\Services\BaseServiceInterface;

interface FileUploadServiceInterface extends BaseServiceInterface
{
    public function upload($configKey, $file, $adminUser, $type, $ownerId);
}
