<?php namespace App\Services;

use App\Services\BaseServiceInterface;

interface CommonServiceInterface extends BaseServiceInterface
{
    public function getDataFromDeviceId($device_id);

    public function isActiveDevice($device);

    public function dataProcessing($data);

    public function changeTimeOfDeviceAdmin($device, $totalTimeAdmin, $totalTimeEmpty);

    public function changeTimeOfDeviceStore($device, $totalTimeStore, $totalTimeEmpty);

    public function getMessageQueueRabbit($queue_name);
}
