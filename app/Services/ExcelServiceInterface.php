<?php namespace App\Services;

use App\Services\BaseServiceInterface;

interface ExcelServiceInterface extends BaseServiceInterface
{
    public function exportToExcel($store_account_info, $path, $fileName, $data);
}
