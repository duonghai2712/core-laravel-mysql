<?php namespace App\Repositories\Eloquent;

use \App\Repositories\LogRepositoryInterface;
use \App\Models\Log;

class LogRepository extends SingleKeyModelRepository implements LogRepositoryInterface
{

    public function getBlankModel()
    {
        return new Log();
    }

    public function rules()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
        ];
    }

}
