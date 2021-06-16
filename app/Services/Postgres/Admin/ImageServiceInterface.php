<?php

namespace App\Services\Postgres\Admin;

use App\Services\BaseServiceInterface;

interface ImageServiceInterface extends BaseServiceInterface
{
    /**
     * resize image as config
     *
     * @params  string  $path path to tmp upload image
     *          array   $config[width, height]
     *          string  $fileUploadedPath like public/static/common/images/products/product1.png
     *
     * @return  boolean
     */
    public function resizeImage($path, $config, $fileUploadedPath);
    public function doUpload($path, $fileUploadedPath);
    public function resizeImageWithHeight($path,$width,$height);
}
