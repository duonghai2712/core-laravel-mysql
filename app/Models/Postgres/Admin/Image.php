<?php namespace App\Models\Postgres\Admin;

use App\Models\Base;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;
use Illuminate\Notifications\Notifiable;
use Nicolaslopezj\Searchable\SearchableTrait;

class Image extends Base
{

    use SoftDeletes;
    use Notifiable;
    use SearchableTrait;

    const MAX_SIZE_IMAGE = 5000000;
    const MAX_SIZE_VIDEO = 50000000;

    const IMAGE = 1;
    const VIDEO = 2;

    const AVATAR = 0;
    const COLLECTION = 1;

    const OTHER = 0;
    const ANT = 1;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'images';

    protected $searchable = [
        'columns' => [
            'images.name' => 10,
        ]
    ];

    const JPG = 'image/jpg';
    const PNG = 'image/png';
    const JPEG = 'image/jpeg';
    const GIF = 'image/gif';
    const MP4 = 'video/mp4';

    const FILEPATH = 'files/admin/company_';

    const ANTS_MEDIA = 'Ants media';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'md5_file',
        'source_thumb',
        'source',
        'project_id',
        'file_size',
        'width',
        'level',
        'type',
        'height',
        'account_id',
        'mimes',
        'owner_id',
        'duration',
        'dimension',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['laravel_through_key'];

    protected $dates = ['deleted_at'];

    protected $presenter = \App\Presenters\Postgres\Admin\ImagePresenter::class;

    public static function boot()
    {
        parent::boot();
        parent::observe(new \App\Observers\Postgres\Admin\ImageObserver);
    }

    // Relations
    public function createdBy()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id');
    }

    public function devices(){
        return $this->hasManyThrough(Device::class, AdminDeviceImage::class,'image_id','id','id','device_id');
    }

    // Utility Functions
    public function getUrl()
    {
        if (config('app.offline_mode', false)) {
            return \URL::to('static/img/local/local.png');
        }

        return !empty($this->url) ? $this->url : 'https://placehold.jp/1440x900.jpg';
    }

    /**
     * @param int $width
     * @param int $height
     *
     * @return string
     */
    public function getThumbnailUrl($width, $height)
    {
        if (config('app.offline_mode', false)) {
            return \URL::to('static/img/local/local.png');
        }

        if (empty($this->url)) {
            if ($height == 0) {
                $height = intval($width / 4 * 3);
            }

            return 'https://placehold.jp/' . $width . 'x' . $height . '.jpg';
        }

        $categoryType = $this->file_category_type;
        $confList = config('file.categories');

        $conf = Arr::get($confList, $categoryType);

        if (empty($conf)) {
            return $this->getUrl();
        }

        $size = Arr::get($conf, 'size');
        if ($width === $size[0] && $height === $size[1]) {
            return $this->getUrl();
        }

        if (preg_match(' /^(.+?)\.([^\.]+)$/', $this->url, $match)) {
            $base = $match[1];
            $ext = $match[2];

            foreach (Arr::get($conf, 'thumbnails', []) as $thumbnail) {
                if ($width === $thumbnail[0] && $height === $thumbnail[1]) {
                    return $base . '_' . $thumbnail[0] . '_' . $thumbnail[1] . '.' . $ext;
                }
                if ($thumbnail[1] == 0 && $height == 0 && $width <= $thumbnail[0]) {
                    return $base . '_' . $thumbnail[0] . '_' . $thumbnail[1] . '.' . $ext;
                }
                if ($thumbnail[1] == 0 && $height != 0 && $size[1] != 0) {
                    if (floor($width / $height * 1000) === floor($size[0] / $size[1] * 1000) && $width <= $thumbnail[0]) {
                        return $base . '_' . $thumbnail[0] . '_' . $thumbnail[1] . '.' . $ext;
                    }
                }
                if ($thumbnail[1] > 0 && $height > 0) {
                    if (floor($width / $height * 1000) === floor($thumbnail[0] / $thumbnail[1] * 1000) && $width <= $thumbnail[0]) {
                        return $base . '_' . $thumbnail[0] . '_' . $thumbnail[1] . '.' . $ext;
                    }
                }
            }
        }

        return $this->getUrl();
    }

    /*
     * API Presentation
     */
    public function toAPIArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'owner_id' => $this->owner_id,
            'md5_file' => $this->md5_file,
            'file_size' => $this->file_size,
            'width' => $this->width,
            'height' => $this->height,
            'is_enabled' => $this->is_enabled,
        ];
    }

}
