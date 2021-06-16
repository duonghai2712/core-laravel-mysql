<?php namespace App\Services\Postgres\Store\Production;

use App\Elibs\eFunction;
use App\Models\Postgres\Store\Collection;
use App\Repositories\Postgres\Store\CollectionRepositoryInterface;
use App\Services\Postgres\Store\CollectionServiceInterface;
use \App\Services\Postgres\Store\FileUploadCollectionServiceInterface;

use App\Services\Production\BaseService;
use Illuminate\Support\Arr;

class FileUploadCollectionService extends BaseService implements FileUploadCollectionServiceInterface
{

    protected $collectionRepository;
    protected $collectionService;

    public function __construct(CollectionRepositoryInterface $collectionRepository, CollectionServiceInterface $collectionService)
    {
        $this->collectionRepository = $collectionRepository;
        $this->collectionService = $collectionService;
    }


    public function upload($configKey, $file, $storeAccount, $type)
    {
        $conf = config('file.categories.' . $configKey);
        if (empty($conf)) {
            return null;
        }
        $acceptableFileList = config('file.acceptable.' . $conf['type']);
        $mediaType = $file->getClientMimeType();
        if (!array_key_exists($mediaType, $acceptableFileList)) {
            return null;
        }

        $file_name = time() . '-' . $file->getClientOriginalName();

        if ($mediaType === Collection::GIF){
            $fileUploaded = $this->uploadGif($file, $configKey, $mediaType, $file_name, $storeAccount);

        }else if(in_array($mediaType, [Collection::JPG, Collection::JPEG, Collection::PNG])){
            $fileUploaded = $this->uploadImage($file, $type, $configKey, $mediaType, $file_name, $storeAccount);

        }else if ($mediaType === Collection::MP4){
            $fileUploaded = $this->uploadFile($file, $configKey, $mediaType, $file_name, $storeAccount);
        }else{
            return [];
        }

        if (empty($fileUploaded)){
            return [];
        }

        return $fileUploaded;
    }

    private function uploadImage($file, $type, $configKey, $mediaType, $file_name, $storeAccount)
    {

        $input = [
            'name' => $file_name,
            'mimes' => $mediaType,
            'type' => Collection::IMAGE,
            'project_id' => $storeAccount['project_id'],
            'store_account_id' => $storeAccount['id'],
            'store_id' => $storeAccount['store_id']
        ];

        $seed = Arr::get(config('file.categories.' . $configKey), 'seed_prefix', '') . time() . rand();

        $fileName = $this->generateFileName($seed, null, 'jpg');

        $localPath = 'static' . '/' . Collection::FILEPATH . $storeAccount['store_id'];
        if (!is_dir($localPath)) {
            mkdir($localPath, 0777, true);
        }

        $fileUploadedPath = $localPath . '/' . $fileName;
        $realPath = $file->getRealPath();
        move_uploaded_file($realPath, $fileUploadedPath);
        $dimension = $this->getDetailImage(public_path($fileUploadedPath));

        $localPathThumb = 'static' . '/' . Collection::FILEPATH . $storeAccount['store_id'] . '/thumb';
        if (!is_dir($localPathThumb)) {
            mkdir($localPathThumb, 0777, true);
        }

        $fileUploadedPathThumb = $localPathThumb . '/' . $fileName;
        $image = \Intervention\Image\Facades\Image::make(public_path($fileUploadedPath));
        $image->fit(320, 180);
        $image->orientate();
        $image->save($fileUploadedPathThumb);

        $input['source_thumb'] = $fileUploadedPathThumb;
        $input['source'] = $fileUploadedPath;
        $input['file_size'] = filesize($fileUploadedPath);
        $input['width'] = getimagesize($fileUploadedPath)[0];
        $input['height'] = getimagesize($fileUploadedPath)[1];
        $input['md5_file'] = md5_file($fileUploadedPath);
        $input['dimension']  = !empty($dimension['dimension']) ? $dimension['dimension'] : '';
        $input['created_at']  = eFunction::getDateTimeNow();
        $input['updated_at']  = eFunction::getDateTimeNow();
        $input['level']  = $type;

        if (empty($type)){
            $collection = $this->collectionRepository->create($input);
            if(!empty($collection)){
                $collection = $collection->toArray();
                return $collection;
            }
        }

        return $input;

    }

    private function uploadGif($file, $configKey, $mediaType, $file_name, $storeAccount)
    {
        $input = [
            'name' => $file_name,
            'mimes' => $mediaType,
            'type' => Collection::IMAGE,
            'project_id' => $storeAccount['project_id'],
            'store_account_id' => $storeAccount['id'],
            'store_id' => $storeAccount['store_id']
        ];

        $seed = Arr::get(config('file.categories.' . $configKey), 'seed_prefix', '') . time() . rand();

        $fileName = $this->generateFileName($seed, null, 'gif');

        $localPath = 'static' . '/' . Collection::FILEPATH . $storeAccount['store_id'];
        if (!is_dir($localPath)) {
            mkdir($localPath, 0777, true);
        }

        $fileUploadedPath = $localPath . '/' . $fileName;
        $realPath = $file->getRealPath();
        move_uploaded_file($realPath, $fileUploadedPath);
        $dimension = $this->getDetailImage(public_path($fileUploadedPath));

        $localPathThumb = 'static' . '/' . Collection::FILEPATH . $storeAccount['store_id'] . '/thumb';
        if (!is_dir($localPathThumb)) {
            mkdir($localPathThumb, 0777, true);
        }

        $fileNameImageGif = $this->generateFileName($seed, null, 'jpg');
        $fileUploadedPathThumb = $localPathThumb . '/' . $fileNameImageGif;
        $image = \Intervention\Image\Facades\Image::make(public_path($fileUploadedPath));
        $image->fit(320, 180);
        $image->orientate();
        $image->save($fileUploadedPathThumb);

        $input['source_thumb'] = $fileUploadedPathThumb;
        $input['source'] = $fileUploadedPath;
        $input['file_size'] = filesize($fileUploadedPath);
        $input['width'] = getimagesize($fileUploadedPath)[0];
        $input['height'] = getimagesize($fileUploadedPath)[1];
        $input['md5_file'] = md5_file($fileUploadedPath);
        $input['dimension']  = !empty($dimension['dimension']) ? $dimension['dimension'] : '';
        $input['created_at']  = eFunction::getDateTimeNow();
        $input['updated_at']  = eFunction::getDateTimeNow();
        $input['level']  = Collection::COLLECTION;

        return $input;
    }

    private function uploadFile($file, $configKey, $mediaType, $file_name, $storeAccount)
    {
        $input = [
            'name' => $file_name,
            'mimes' => $mediaType,
            'type' => Collection::VIDEO,
            'project_id' => $storeAccount['project_id'],
            'store_account_id' => $storeAccount['id'],
            'store_id' => $storeAccount['store_id']
        ];

        $seed = Arr::get(config('file.categories.' . $configKey), 'seed_prefix', '') . time() . rand();

        $fileName = $this->generateFileName($seed, null, 'mp4');

        $localPath = 'static' . '/' . Collection::FILEPATH . $storeAccount['store_id'];
        if (!is_dir($localPath)) {
            mkdir($localPath, 0777, true);
        }

        $fileUploadedPath = $localPath . '/' . $fileName;
        $realPath = $file->getRealPath();
        move_uploaded_file($realPath, $fileUploadedPath);
        $dimension = $this->getDetailVideo(public_path($fileUploadedPath));

        $localPathThumb = 'static' . '/' . Collection::FILEPATH . $storeAccount['store_id'] . '/thumb';
        if (!is_dir($localPathThumb)) {
            mkdir($localPathThumb, 0777, true);
        }

        $fileNameImageMp4 = $this->generateFileName($seed, null, 'jpg');
        $fileUploadedPathThumb = $localPathThumb . '/' . $fileNameImageMp4;

        $this->handleThumbVideo($mediaType, $fileUploadedPath, public_path($fileUploadedPathThumb));

        $input['source_thumb'] = $fileUploadedPathThumb;
        $input['source'] = $fileUploadedPath;
        $input['file_size'] = filesize($fileUploadedPath);
        $input['md5_file'] = md5_file($fileUploadedPath);
        $input['dimension']  = !empty($dimension['dimension']) ? $dimension['dimension'] : '';
        $input['duration'] = !empty($dimension['duration']) ? $dimension['duration'] : '';
        $input['created_at']  = eFunction::getDateTimeNow();
        $input['updated_at']  = eFunction::getDateTimeNow();
        $input['level']  = Collection::COLLECTION;

        return $input;
    }

    private function handleThumbVideo($mimes, $file_video,$file_thumb){
        if ($mimes == 'video/webm'){
            $command = "ffmpeg -i ".$file_video . ' -an -y -f mjpeg -ss 2 -s 160x92 -vframes 1 ' . $file_thumb;
        }else{
            $command = "ffmpeg -y -i ".$file_video." -ss 00:00:2.000 -vframes 1 -vf scale=400:300 ".$file_thumb." -hide_banner  -loglevel error 2>&1";
        }

        exec($command);
    }

    private function generateFileName($seed, $postFix, $ext)
    {
        $filename = md5($seed);
        if (!empty($postFix)) {
            $filename .= '_' . $postFix;
        }
        if (!empty($ext)) {
            $filename .= '.' . $ext;
        }

        return $filename;
    }

    private function getDetailImage($file){
        $detail = [];
        $shell = shell_exec("ffprobe -v error -select_streams v:0 -show_entries stream=width,height,duration -of default=noprint_wrappers=1 -print_format json -show_format ".$file."");
        $data = json_decode($shell,true);
        $stream = current($data['streams']);
        if(isset($stream)){
            $detail['dimension'] = $stream['width'].'x'.$stream['height'];
        }

        if (!empty($detail['dimension'])){
            return $detail['dimension'];
        }else{
            return false;
        }
    }

    public function getDetailVideo($file){
        $detail = [];
        $shell = shell_exec("ffprobe -v error -select_streams v:0 -show_entries stream=width,height,duration -of default=noprint_wrappers=1 -print_format json -show_format ".$file."");
        $data = json_decode($shell,true);
        $stream = current($data['streams']);
        if(isset($stream)){
            $detail['duration'] = !empty($stream['duration']) ? gmdate('H:i:s', round($stream['duration'])) : '';
            $detail['dimension'] = $stream['width'].'x'.$stream['height'];
        }
        return $detail;
    }
}
