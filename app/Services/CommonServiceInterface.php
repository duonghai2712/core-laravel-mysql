<?php namespace App\Services;

use App\Services\BaseServiceInterface;

interface CommonServiceInterface extends BaseServiceInterface
{
    public function getDataFromDeviceId($device_id);

    public function isActiveDevice($device);

    public function dataProcessing($data);

    public function getMessageQueueRabbit($queue_name);
}
