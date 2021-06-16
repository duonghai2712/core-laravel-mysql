<?php
namespace App\Elibs;

use App\Models\Postgres\Admin\Device;
use App\Models\Postgres\Admin\Image;
use App\Models\Postgres\Admin\Store;
use App\Models\Postgres\Store\Collection;
use App\Models\Postgres\Store\LogOperation;
use App\Models\Postgres\Store\Order;
use App\Models\Postgres\Store\StoreAccount;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use DateInterval;
use DatePeriod;
use DateTime;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class eFunction
{

    const TIME_BLOCK_LOGIN = 180;
    const TIME_REMEMBER_LOGIN = 300;

    const MIN_TIME_LOGIN = 1;
    const MAX_TIME_LOGIN = 3;

    public static function arrayInteger($value)
    {
        $response = collect($value)->filter(function($q)
        {
            return is_numeric($q);
        })->values()->toArray();

        return $response;
    }

    public static function randomInt($length)
    {
        $code = '';
        for ($i=0;$i<$length;$i++) {
            $code.= mt_rand(1, 9);
        }
        return $code;
    }

    public static function generateRandomString($length = 14) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < ($length - 6); $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return date('ymd', strtotime('now')) . $randomString;
    }

    public static function getDateTimeNow()
    {
        $date = date('Y-m-d H:i:s', strtotime('now'));
        return $date;
    }

    public static function generateSlug($name, $characters)
    {
        $slug = Str::slug($name, $characters);
        return $slug;
    }

    public static function FillUp($accountInfo, &$data)
    {
        $data['project_id'] = $accountInfo['project_id'];
        $data['account_id'] = $accountInfo['id'];

        return $data;
    }

    public static function FillUpStore($accountInfo, &$data)
    {
        $data['project_id'] = $accountInfo['project_id'];
        $data['store_id'] = $accountInfo['store_id'];

        return $data;
    }

    public static function isAdminStore($storeAccountInfo)
    {
        if ((int)$storeAccountInfo['role'] === StoreAccount::ADMIN){
            return true;
        }

        return false;
    }

    public static function isAccountBooking($storeAccountInfo)
    {
        if ((int)$storeAccountInfo['make_ads'] === StoreAccount::MAKE_ADS_TRUE){
            return true;
        }

        return false;
    }

    static function setRedisWithExpiredTime($key, $value, $second)
    {
        $redis = Redis::connection();

        $redis->set($key, $value);
        $redis->expire($key, $second);

        return true;
    }

    public static function getRedisExpiredTime($key)
    {
        $redis = Redis::connection();
        $second = $redis->ttl($key);

        return $second;
    }

    public static function getRedis($key)
    {
        $redis = Redis::connection();
        $value = $redis->get($key);

        return $value;
    }

    public static function getPointCollection($type, $second, $totalTimes, $coefficient, $totalDevices)
    {
        $point = 0;
        if ((int)$type === Collection::IMAGE){
            if ((int)$second === Device::POINT_TEN){
                $point = $totalTimes*$coefficient*$totalDevices*0.5;
            }else if ((int)$second === Device::POINT_FIFTEEN){
                $point = $totalTimes*$coefficient*$totalDevices*0.7;
            }else if ((int)$second === Device::POINT_THIRTY){
                $point = $totalTimes*$coefficient*$totalDevices;
            }
        }else if ((int)$type === Collection::VIDEO){
            $naturalPart = $second/Device::POINT_THIRTY;
            $theRemainder = $second%Device::POINT_THIRTY;

            if ((int)$theRemainder <= Device::POINT_TEN){
                $point = $totalTimes*$coefficient*$totalDevices*((int)$naturalPart + 0.5);
            }else if ($theRemainder <= Device::POINT_FIFTEEN){
                $point = $totalTimes*$coefficient*$totalDevices*((int)$naturalPart + 0.7);
            }else if ($theRemainder <= Device::POINT_THIRTY){
                $point = $totalTimes*$coefficient*$totalDevices*((int)$naturalPart + 1);
            }
        }

        return $point;
    }

    public static function getPointCollectionAdmin($type, $second, $totalTimes, $devices)
    {
        $valuePoint = 0;
        if ((int)$type === Collection::IMAGE){
            if ((int)$second === Device::POINT_TEN){
                $valuePoint = 0.5;
            }else if ((int)$second === Device::POINT_FIFTEEN){
                $valuePoint = 0.7;
            }else if ((int)$second === Device::POINT_THIRTY){
                $valuePoint = 1;
            }
        }else if ((int)$type === Collection::VIDEO){
            $naturalPart = $second/Device::POINT_THIRTY;
            $theRemainder = $second%Device::POINT_THIRTY;
            $valuePoint = (int)$naturalPart;

            if ((int)$theRemainder > 0 && (int)$theRemainder <= Device::POINT_TEN){
                $valuePoint = $valuePoint + 0.5;
            }else if ((int)$theRemainder > Device::POINT_TEN && (int)$theRemainder <= Device::POINT_FIFTEEN){
                $valuePoint = $valuePoint + 0.7;
            }else if ((int)$theRemainder > Device::POINT_FIFTEEN && (int)$theRemainder <= Device::POINT_THIRTY){
                $valuePoint = $valuePoint + 1;
            }
        }

        return self::processGeneratePoint($totalTimes, $devices, $valuePoint);
    }

    public static function getPointCollectionEstimateAdmin($second, $totalTimes, $devices)
    {
        $naturalPart = $second/Device::POINT_TEN;
        $theRemainder = $second%Device::POINT_TEN;

        if ((int)$theRemainder !== 0){
            $point = self::processGeneratePoint($totalTimes, $devices, ((int)$naturalPart + 1)*0.5);
        }else{
            $point = self::processGeneratePoint($totalTimes, $devices, (int)$naturalPart*0.5);
        }

        return $point;
    }

    public static function processGeneratePoint($totalTimes, $devices, $valuePoint)
    {
        $point = 0;
        foreach ($devices as $device){
            if (!empty($device['branch']['rank']['coefficient'])){
                $point = $point + ($totalTimes*(int)$device['branch']['rank']['coefficient']*$valuePoint);
            }
        }
        return $point;
    }

    public static function getTotalTimeInOrder($timeFrames)
    {
        $totalTimes = 0;
        foreach ($timeFrames as $time){
            $timeStart = new DateTime($time['start_time']);
            $timeEnd = new DateTime($time['end_time']);

            $timeDiff = $timeStart->diff($timeEnd);
            $hour = (int)$timeDiff->format("%H");

            if ($time['end_time'] === '23:59:59'){
                $hour = $hour + 1;
            }

            $dateStart = new DateTime($time['start_date']);
            $dateEnd = new DateTime($time['end_date']);

            $dateDiff = $dateStart->diff($dateEnd);
            $totalTimes = $totalTimes + ($hour*((int)$dateDiff->format("%d") + 1)*6);
        }

        return $totalTimes;
    }

    public static function getPointStore($params)
    {
        $point = 0;

        if (!empty($params['order']['time_frames']) && is_array($params['order']['time_frames']) && !empty($params['device']['branch']['rank']['coefficient'])){
            $totalPointInCollection = 0;
            $totalHours = self::getTotalTimeInOrder($params['order']['time_frames']);

            if (!empty($params['order']['store_cross_device_collections'])){
                foreach ($params['order']['store_cross_device_collections'] as $collection){
                    if ((int)$collection['type'] === Collection::IMAGE){
                        if ((int)$collection['second'] === Device::POINT_TEN){
                            $totalPointInCollection = $totalPointInCollection + 0.5;
                        }else if ((int)$collection['second'] === Device::POINT_FIFTEEN){
                            $totalPointInCollection = $totalPointInCollection + 0.7;
                        }else if ((int)$collection['second'] === Device::POINT_THIRTY){
                            $totalPointInCollection = $totalPointInCollection + 1;
                        }
                    }else if ((int)@$collection['type'] === Collection::VIDEO){

                        $naturalPart = $collection['second']/Device::POINT_THIRTY;
                        $theRemainder = $collection['second']%Device::POINT_THIRTY;

                        $totalPointInCollection = $totalPointInCollection + (int)$naturalPart;

                        if ((int)$theRemainder > 0 && (int)$theRemainder <= Device::POINT_TEN){
                            $totalPointInCollection = $totalPointInCollection  + 0.5;
                        }else if ((int)$theRemainder > Device::POINT_TEN && (int)$theRemainder <= Device::POINT_FIFTEEN){
                            $totalPointInCollection = $totalPointInCollection  + 0.7;
                        }else if ((int)$theRemainder > Device::POINT_FIFTEEN && (int)$theRemainder <= Device::POINT_THIRTY){
                            $totalPointInCollection = $totalPointInCollection  + 1;
                        }
                    }
                }
            }else{
                if (!empty($params['order']['time_booked'])){
                    $totalTimes = $params['order']['time_booked']/Device::POINT_TEN;
                    $totalPointInCollection = ceil($totalTimes)*0.5;
                }
            }


            $point = $totalHours*$params['device']['branch']['rank']['coefficient']*$totalPointInCollection;
        }

        return $point;
    }

    public static function getThrottleLogin($account, $username, $string = 'ynhann')
    {
        if (!empty($account)){
            $valueUsername = self::getRedis($account['username'] . $string);
            $valueEmail = self::getRedis($account['email'] . $string);
            if ((!empty($valueUsername) && (int)$valueUsername === self::MAX_TIME_LOGIN) || (!empty($valueEmail) && (int)$valueEmail === self::MAX_TIME_LOGIN)){
                self::setRedisWithExpiredTime($account['username'] . $string . $string, self::MAX_TIME_LOGIN, self::TIME_BLOCK_LOGIN);
                self::setRedisWithExpiredTime($account['username'] . $string, self::MIN_TIME_LOGIN, 0);

                self::setRedisWithExpiredTime($account['email'] . $string . $string, self::MAX_TIME_LOGIN, self::TIME_BLOCK_LOGIN);
                self::setRedisWithExpiredTime($account['email'] . $string, self::MIN_TIME_LOGIN, 0);
            }

            $expiredUsername = self::getRedisExpiredTime($account['username'] . $string . $string);
            $expiredEmail = self::getRedisExpiredTime($account['email'] . $string . $string);

            if (!empty($expiredUsername) && (int)$expiredUsername > 0){
                return (int)$expiredUsername;
            }

            if (!empty($expiredEmail) && (int)$expiredEmail > 0){
                return (int)$expiredEmail;
            }

            return false;
        }

        $value = self::getRedis($username . $string);
        if (!empty($value) && (int)$value === self::MAX_TIME_LOGIN){
            self::setRedisWithExpiredTime($username . $string . $string, self::MAX_TIME_LOGIN, self::TIME_BLOCK_LOGIN);
            self::setRedisWithExpiredTime($username . $string, self::MIN_TIME_LOGIN, 0);
        }

        $expired = self::getRedisExpiredTime($username . $string . $string);
        if (!empty($expired) && (int)$expired > 0){
            return (int)$expired;
        }

        return false;
    }

    public static function setThrottleLogin($account, $username, $string = 'ynhann')
    {
        if (!empty($account)){
            $valueUser = self::getRedis($account['username'] . $string);
            $valueEmail = self::getRedis($account['email'] . $string);
            if (empty($valueUser) && empty($valueEmail)){
                self::setRedisWithExpiredTime($account['username'] . $string, self::MIN_TIME_LOGIN, self::TIME_REMEMBER_LOGIN);
                self::setRedisWithExpiredTime($account['email'] . $string, self::MIN_TIME_LOGIN, self::TIME_REMEMBER_LOGIN);
                return true;
            }

            if ((int)$valueUser < self::MAX_TIME_LOGIN || (int)$valueEmail < self::MAX_TIME_LOGIN){
                self::setRedisWithExpiredTime($account['username'] . $string, ((int)$valueUser + self::MIN_TIME_LOGIN), self::TIME_REMEMBER_LOGIN);
                self::setRedisWithExpiredTime($account['email'] . $string, ((int)$valueEmail + self::MIN_TIME_LOGIN), self::TIME_REMEMBER_LOGIN);
                return true;
            }

            return false;
        }

        $value = self::getRedis($username . $string);
        if (empty($value)){
            self::setRedisWithExpiredTime($username . $string, self::MIN_TIME_LOGIN, self::TIME_REMEMBER_LOGIN);
            return true;
        }

        if ((int)$value < self::MAX_TIME_LOGIN){
            self::setRedisWithExpiredTime($username . $string, ((int)$value + self::MIN_TIME_LOGIN), self::TIME_REMEMBER_LOGIN);
            return true;
        }

        return false;
    }

    public static function sendMessageQueue($params, $queue_name)
    {
        $connection = new AMQPStreamConnection(env('RABBITMQ_HOST', 'localhost'), env('RABBITMQ_PORT', 5672),  env('RABBITMQ_USER', 'guest'),  env('RABBITMQ_PASSWORD', 'guest'));
        $channel = $connection->channel();
        $channel->queue_declare($queue_name,false,true, false, false, false, new AMQPTable(["x-max-length" => Device::LIMIT_MESSAGE_QUEUE]));
        $channel->basic_publish(new AMQPMessage(eCrypt::encryptAES(json_encode($params)), array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)), '', $queue_name);

        $channel->close();
        $connection->close();

        return true;
    }

    public static function sendMessageQueueDemo($params, $queue_name)
    {
        $connection = new AMQPStreamConnection(env('RABBITMQ_HOST', 'localhost'), env('RABBITMQ_PORT', 5672),  env('RABBITMQ_USER', 'guest'),  env('RABBITMQ_PASSWORD', 'guest'));
        $channel = $connection->channel();
        $channel->queue_declare($queue_name,false,true, false, false);
        $channel->basic_publish(new AMQPMessage($params, array('delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT)), '', $queue_name);

        $channel->close();
        $connection->close();

        return true;
    }

    public static function getActivity($storeAccountInfo, $deviceId, $nameDescription, $key, $branchId)
    {
        $params = [
            'name' => LogOperation::ARR_NAME_LOG[$key],
            'device_id' => !empty($deviceId) ? (int)$deviceId : null,
            'description' => self::getDescription($storeAccountInfo, $nameDescription, $key),
            'store_account_id' => $storeAccountInfo['id'],
            'store_id' => $storeAccountInfo['store_id'],
            'branch_id' => $branchId,
            'project_id' =>  $storeAccountInfo['project_id'],
        ];

        return $params;
    }

    public static function getPointPlusForStore($coefficient, $totalSeconds)
    {
        $naturalPart = $totalSeconds/Device::POINT_THIRTY; //Chia lấy nguyện
        $theRemainder = $totalSeconds%Device::POINT_THIRTY; //Chia lấy dư

        $totalPointInCollection = (int)$naturalPart;
        if ((int)$theRemainder >= Device::POINT_TEN && (int)$theRemainder < Device::POINT_FIFTEEN){
            $totalPointInCollection = $totalPointInCollection  + 0.5;
        }else if ((int)$theRemainder >= Device::POINT_FIFTEEN && (int)$theRemainder < Device::POINT_THIRTY){
            $totalPointInCollection = $totalPointInCollection  + 0.7;
            if (($theRemainder - Device::POINT_FIFTEEN) >= Device::POINT_TEN){
                $totalPointInCollection = $totalPointInCollection  + 0.5;
            }
        }

        return $coefficient*$totalPointInCollection;
    }

    public static function getDescription($storeAccountInfo, $name_description, $key)
    {
        $name_account = !empty($storeAccountInfo['representative']) ? $storeAccountInfo['representative'] : $storeAccountInfo['username'];
        if (!empty($name_description)){
            return LogOperation::ARR_DESCRIPTION_TITLE . $name_account . LogOperation::ARR_DESCRIPTION_LOG[$key] . $name_description;
        }

        return LogOperation::ARR_DESCRIPTION_TITLE . $name_account . LogOperation::ARR_DESCRIPTION_LOG[$key];

    }

    public static function checkConnectDevice($arrDeviceCodes)
    {
        try{
            $urlSite = env('RABBIT_URL', 'http://192.168.1.25:15672').'/api/consumers';
            $ch = curl_init($urlSite);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_USERPWD, "admin:adtgroup.vn");

            $result = curl_exec($ch);
            curl_close($ch);
            $consumer = json_decode($result,true);
            $queue = collect($consumer)->pluck('queue')->pluck('name')->toArray();
            $device = [];
            foreach ($arrDeviceCodes as  $key => $value){
                $device[$value] = in_array($value, $queue) ? Device::CONNECT : Device::DISCONNECT;
            }
            return $device;

        }catch(\Exception $e){
            \Log::error($e->getMessage());
        }
    }

    public static function getListDeviceConnect()
    {
        try{
            $urlSite = env('RABBIT_URL', 'http://192.168.1.25:15672').'/api/consumers';
            $ch = curl_init($urlSite);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_USERPWD, "admin:adtgroup.vn");

            $result = curl_exec($ch);
            curl_close($ch);
            $consumer = json_decode($result,true);
            $device = collect($consumer)->pluck('queue')->pluck('name')->toArray();
            return $device;

        }catch(\Exception $e){
            \Log::error($e->getMessage());
        }
    }

    public static function mergeSubBrandToBrand($params)
    {
        if (!empty($params['data'])){
            foreach ($params['data'] as $key => $value){
                if (!empty($value['brands']) && !empty($value['sub_brands'])){
                    foreach ($value['brands'] as $k => $brand){
                        $subBrands = [];
                        foreach ($value['sub_brands'] as $sub_brand){
                            if (!empty($sub_brand['brand_id']) && !empty($brand['id']) && (int)$sub_brand['brand_id'] === $brand['id']){
                                $subBrands[] = $sub_brand;
                            }
                        }
                        if (!empty($subBrands)){
                            $params['data'][$key]['brands'][$k]['sub_brands'] = $subBrands;
                        }
                    }

                    unset($params['data'][$key]['sub_brands']);
                }

                if (!empty($value['account']) && !empty($value['account']['source'])){
                    $params['data'][$key]['avatar'] = asset($value['account']['source']);
                }else{
                    $params['data'][$key]['avatar'] = null;
                }

                unset($params['data'][$key]['province_id']);
                unset($params['data'][$key]['district_id']);
                unset($params['data'][$key]['account_id']);
                unset($params['data'][$key]['project_id']);
            }
        }


        return $params;
    }

    public static function mergeSubBrandToBrandInBranch($branch)
    {
        if (!empty($branch['brands']) && !empty($branch['sub_brands'])){
            foreach ($branch['brands'] as $k => $brand){
                $subBrands = [];
                foreach ($branch['sub_brands'] as $sub_brand){
                    if (!empty($sub_brand['brand_id']) && !empty($brand['id']) && (int)$sub_brand['brand_id'] === $brand['id']){
                        $subBrands[] = $sub_brand;
                    }
                }
                if (!empty($subBrands)){
                    $branch['brands'][$k]['sub_brands'] = $subBrands;
                }
            }

            unset($branch['sub_brands']);
        }

        return $branch;
    }

    public static function addFullUrlImageAndCheckDevice($images, $storeCrossCollections)
    {
        if (!empty($images['data'])){
            foreach ($images['data'] as $k => $image){
                if (!empty($image['source'])) {
                    $images['data'][$k]['source'] = asset($image['source']);
                }

                if (!empty($image['source_thumb'])) {
                    $images['data'][$k]['source_thumb'] = asset($image['source_thumb']);
                }

                $isDevice = false;
                if (!empty($image['devices'])) {
                    foreach ($image['devices'] as $device){
                        if (!empty($device['id'])){
                            $isDevice = true;
                        }
                    }
                }

                if (!empty($storeCrossCollections) && !empty($storeCrossCollections[$image['id']])){
                    $isDevice = true;
                }

                if(!empty($isDevice)) {
                    $images['data'][$k]['devices'] = Device::HAS_DEVICE;
                }else {
                    $images['data'][$k]['devices'] = Device::NO_DEVICE;
                }

            }
        }

        return $images;
    }

    public static function getStatusDevices($devices)
    {
        if (!empty($devices['data'])){
            $arrStatusDevices = [];
            $arrDeviceCodes = collect($devices['data'])->pluck('device_code')->filter()->unique()->values()->toArray();
            if (!empty($arrDeviceCodes)){
                $arrStatusDevices = eFunction::checkConnectDevice($arrDeviceCodes);
            }

            foreach ($devices['data'] as $k => $device){
                if (!empty($device['device_code']) && isset($arrStatusDevices[$device['device_code']])){
                    if (!empty($arrStatusDevices[$device['device_code']])){
                        $devices['data'][$k]['status'] = Device::VIEW_CONNECT;
                    }else{
                        $devices['data'][$k]['status'] = Device::VIEW_DISCONNECT;
                    }
                }else{
                    $devices['data'][$k]['status'] = Device::VIEW_NOT_USE;
                }
            }
        }

        return $devices;
    }

    public static function activeDevice($device)
    {
        if (isset($device['is_active']) && (int)$device['is_active'] === Device::IS_ACTIVE){
            return true;
        }

        return false;
    }

    public static function checkFileSize($image)
    {
        if (!empty($image) && isset($image['file_size']) && isset($image['type']) && (($image['file_size'] > Image::MAX_SIZE_IMAGE && $image['type'] === Image::IMAGE)
                || ($image['file_size'] > Image::MAX_SIZE_VIDEO && $image['type'] === Image::VIDEO))){
                return true;
        }

        return false;
    }

    public static function getFullUrlCollectionInDevice($deviceWithCollection)
    {
        if (!empty($deviceWithCollection['store_collection'])){
            foreach ($deviceWithCollection['store_collection'] as $k => $v){
                if (!empty($v['source'])){
                    $deviceWithCollection['store_collection'][$k]['source'] = asset($deviceWithCollection['store_collection'][$k]['source']);
                }

                if (!empty($v['source_thumb'])){
                    $deviceWithCollection['store_collection'][$k]['source_thumb'] = asset($deviceWithCollection['store_collection'][$k]['source_thumb']);
                }
            }
        }

        foreach ($deviceWithCollection['admin_image'] as $k => $v){
            if (!empty($v['source'])){
                $deviceWithCollection['admin_image'][$k]['source'] = asset($deviceWithCollection['admin_image'][$k]['source']);
            }

            if (!empty($v['source_thumb'])){
                $deviceWithCollection['admin_image'][$k]['source_thumb'] = asset($deviceWithCollection['admin_image'][$k]['source_thumb']);
            }
        }

        return $deviceWithCollection;
    }

}
