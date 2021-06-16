<?php

namespace App\Http\Middleware\Api\Postgres\App;

use App\Repositories\Postgres\Admin\AccountRepositoryInterface;
use App\Elibs\eResponse;
use App\Repositories\Postgres\Admin\DeviceRepositoryInterface;
use Closure;

class AppAuthentication
{
    protected $deviceRepository;

    public function __construct(DeviceRepositoryInterface $deviceRepository)
    {
        $this->deviceRepository = $deviceRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!empty($token)){
            $device = $this->deviceRepository->getOneObjectDeviceByFilter(['device_token' => $token, 'deleted_at' => true]);
            if (!empty($device) && !empty($device->project_id) && !empty($device->id)) {
                $device_info = [
                    'id' => $device->id,
                    'store_id' => $device->store_id,
                    'branch_id' => $device->branch_id,
                    'project_id' => $device->project_id,
                ];

                $request->merge(compact('device_info'));
                return $next($request);
            }
        }

        return eResponse::response(STATUS_API_TOKEN_FALSE, __('notification.system.token-not-found'), []);
    }
}
