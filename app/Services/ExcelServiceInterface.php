<?php namespace App\Services;

use App\Services\BaseServiceInterface;

interface ExcelServiceInterface extends BaseServiceInterface
{
    public function exportToExcel($storeAccountInfo, $path, $fileName, $data);
}
