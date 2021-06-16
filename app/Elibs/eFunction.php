<?php
namespace App\Elibs;

use App\Models\Postgres\Admin\Device;
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

    public static function FillUp($account_info, &$data)
    {
        $data['project_id'] = $account_info['project_id'];
        $data['account_id'] = $account_info['id'];

        return $data;
    }

    public static function FillUpStore($account_info, &$data)
    {
        $data['project_id'] = $account_info['project_id'];
        $data['store_id'] = $account_info['store_id'];

        return $data;
    }

    public static function isAdminStore($store_account_info)
    {
        if ((int)$store_account_info['role'] === StoreAccount::ADMIN){
            return true;
        }

        return false;
    }

    public static function isMakeAds($store_account_info)
    {
        if ((int)$store_account_info['make_ads'] === StoreAccount::MAKE_ADS_TRUE){
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

            $dateStart = new DateTime($time['start_date']);
            $dateEnd = new DateTime($time['end_date']);

            $dateDiff = $dateStart->diff($dateEnd);
            $totalTimes = $totalTimes + ((int)$timeDiff->format("%H")*((int)$dateDiff->format("%d") + 1)*6);
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

    public static function getActivity($store_account_info, $device_id, $name_description, $key)
    {
        $params = [
            'name' => LogOperation::ARR_NAME_LOG[$key],
            'device_id' => !empty($device_id) ? (int)$device_id : null,
            'description' => self::getDescription($store_account_info, $name_description, $key),
            'store_account_id' => $store_account_info['id'],
            'store_id' => $store_account_info['store_id'],
            'project_id' =>  $store_account_info['project_id'],
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

    public static function getDescription($store_account_info, $name_description, $key)
    {
        $name_account = !empty($store_account_info['representative']) ? $store_account_info['representative'] : $store_account_info['username'];
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
}
