<?php

namespace App\Http\Controllers\Api\Postgres\Admin;

use App\Elibs\eFunction;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use App\Repositories\Postgres\Admin\DeviceRepositoryInterface;
use App\Repositories\Postgres\DistrictRepositoryInterface;
use App\Repositories\Postgres\ProvinceRepositoryInterface;
use App\Services\CommonServiceInterface;
use Illuminate\Support\Str;
use App\Http\Requests\Api\Request;
use  DB;


class ApiProvisionController extends Controller
{
    protected $deviceRepository;
    protected $commonService;
    protected $provinceRepository;
    protected $districtRepository;

    public function __construct(
        ProvinceRepositoryInterface $provinceRepository,
        DeviceRepositoryInterface $deviceRepository,
        CommonServiceInterface $commonService,
        DistrictRepositoryInterface $districtRepository
    )
    {
        $this->deviceRepository = $deviceRepository;
        $this->commonService = $commonService;
        $this->provinceRepository = $provinceRepository;
        $this->districtRepository = $districtRepository;
    }

    public function index()
    {

    }

    public function allProvinces(Request $request)
    {
        $filter = [];
        $this->makeFilter($request, $filter);
        $filter['deleted_at'] = true;

        $provinces = $this->provinceRepository->getAllProvinceByFilter($filter);

        return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $provinces);
    }

    public function GetQueueRabbit()
    {
        $this->commonService->getMessageQueueRabbit('report');
        return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
    }

    public function SendQueueRabbit()
    {
        $xx = '[{"id":26,"device_id":"002cf012bdb56865","data":"e5\/tB0cRVgZ5UXNuVxNCiBV9MG2Ts1i\/oMCWGWT+8ThxxR9Vn4hW1qzUokXvesFG176DM09FpoZ\/\nxsekAxh2YoDC8gIO0+3s+GmrmRviLr9z4jIrR7uyHOxg9NbZVkmswkNfFHRMrPq0B3umwzbXKFc7\nTTVL+OZ1i4YoIH7QvB8WVbNC9mqtxubNqdldcaQlJE\/fFyQeNMvTtYetFbUmyOVrxZo9\/cJNoH62\nRrrohusT1389xGxv0dzIVXiKMJpsZnS3wzjaZiYUn2p6xOxiPqqF1rIu7X5IMo7UBdER5xeRHmu\/\n+BvH8pl4MsbHYMhiFyHhGOnbONMKzfr3KBXFzSKLeEVapfQr\/wdmfC5sFPXlOP+Pur7SGzdhjL2F\nKoYxd9SPcS0AGOPYhCEVcNKVJG7ZLC8qpyhVHdzAthIb5feXUF6ZavI\/8xZ1G5tYOHVBePHefFl0\nFRH0WkkJJarP41A1I35LoTp19\/J6N0UQkH6P\/+8xdiSWmORxsZLlXb\/GSqr1oO2Mlr1W1zJ7ybHm\naF6cZodloda9MWuWAC0KR5L9\/mcRXFpye2YxqEB0+Ow7SXt2iyMd4J6gP8oJtzrvZK2xIUggwnP4\nwuk5rS29jk4BmEQXg5bcMbD7m4lEN6ZGMaHTEdsnKXjfN9L4jMEO1A4b41vJ13\/q+5qVjvxSI1wV\nIiVoTBuB5YO35XCjALLx4AsnOAip2V4taFjt2uum3\/nIf6Jv2s4vX\/Ktg8AlYeUxWGFX3jnUFzTM\n2SzJkfEfHhiTMyCwavkUkGLbh+Oynyd0vnLhYptCFz3\/x4uYNNJ6fvEO9XePg7lg1qOX3GtHtNun\nxVC+pcPJxkOLrcHRLjncUdX9uhe+ZGT7ws6c\/a4lBU1bzwITL890zYbNvlZZI7j\/WhOaPL7PL2My\nTsVN9fzZXcIEXwU5IVIQ13DL48ROKZGTlMxNYrtmIL3mMA3WM6ybgBLN50JtD4UEctA3tt22gml\/\nm1jJbhzzRluldw+CZWwNn1Aokiyb\/ayjwlYNHhRYQbWZBNM3ExTgackFHfepNi4f6nJ37bLIHDsL\nhLGe9r7AKF2WOFB7C8Tld+BC33OwtaRZilbGrtgOTzZFmMTwUk8S4w9EhtcPvo8r0e2P6VwJwWUv\niDy4m7e\/IOiOBR8Lp8sZUQikBzOzRfyBaA9R7hzYWjoPyIA\/LlwkMT2QKaW8zbRhFY2OqFKUNljx\n2bmDAsIPF5ykb2Lo0ApXjaGazZysykq4pZ3j2TpH3pl6\/NCoyaiZ7\/2m0C3Zi9WRfdWruoE4D+ad\nM1aUieWl7HqLGrSeuBwJFviLmHedHscA5BSmV+9tHbLjorDl1kTZQa4tUNSZyMMszp5Uf0qFiNQi\noqEzYvxRLXrVFMheKABe5ThKgy\/\/3AVGzWFivC3dUgBTjTGOpLcMsbwHzhT8SuXrBEFRF\/eVXeRo\nT1+0IoqqdV0sibKLrkKgpWx\/Y\/GByiZ5sID4Kgm6YUEsr2Ogh+MQT6tRZNTv0slPpaJdUI1gie8R\n58JyCDnUuB\/qPiv5GSzbbZifVO5gQWnQlNrT54GluMMcsi2GRSjNiZm+5o1Bi+QntuarrdEgnhWZ\ns7ZO0Ur0aW8q8+GtZTh52qBibzAVPUXjly1RUSevLYVVzVBkGDJjINeiGmG2su+5nKlPZTwUecOV\nmKkQb0e7VMKKVv8D4A0P76FgAD7nzwhNLSIc7hc1UM2Zjm4fWdm04YrlAfRWa1SRSBIIk3ZsNqAH\nYATFBm4oehrUxaBd3tzpaV5ObN2js5K4+Q3DCaZ5fbLpuW01JvM5NaumLf4JA93mmQunWaZWerYN\nrLZBFCIjQvjvM8TlcXmv3EY7TR3cJAVSgphuns4dzNUFzNf78eNMVcSsLCYoQYpnKv1ktAhLi8\/N\ndQJhFuBueg\/SKIdVPvOk92OT8gL4F+gfvu+LNklaoVXIhUk5cPGywNIAIf8D43I=\n","date_at":"2021-05-11"}]';
        eFunction::sendMessageQueueDemo($xx, 'report');

        return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
    }

    public function allDistricts(Request $request)
    {
        $filter = [];
        $this->makeFilter($request, $filter);
        $filter['deleted_at'] = true;

        $districts = $this->districtRepository->getAllDistrictByFilter($filter);

        return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $districts);

    }

    public function detailProvince(Request $request)
    {
        $id = $request->get('id', '');
        if (empty($id)){
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }

        $filter = [];
        $this->makeFilter($request, $filter);
        $filter['deleted_at'] = true;
        $filter['province_id'] = (int)$id;

        $districts = $this->districtRepository->getAllDistrictByFilter($filter);
        if (!empty($districts)){
            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $districts);
        }

        return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
    }

    public function addProvincesAndDistricts()
    {
        try{

            DB::beginTransaction();

            $this->provinceRepository->deleteAllProvinceByFilter(['deleted_at' => true]);
            $this->districtRepository->deleteAllDistrictByFilter(['deleted_at' => true]);

            $json = file_get_contents(public_path('ProvincesAndDistricts.json'));
            $provinces = json_decode($json, true);
            $arrFilterProvinces = ['Tỉnh '];
            $arrFilterDistricts = ['Tỉnh ', 'Huyện ', 'Quận '];
            $arrProvinces = [];
            $arrDistrict = [];

            if (!empty($provinces)){
                foreach ($provinces as $province){
                    $nameProvince = str_replace($arrFilterProvinces, '', $province['Name']);
                    $arrProvinces[] = [
                        'name' => $nameProvince,
                        'slug' => eFunction::generateSlug($nameProvince, '-'),
                        'id_api' => $province['Id'],
                        'created_at' => eFunction::getDateTimeNow(),
                        'updated_at' => eFunction::getDateTimeNow()
                    ];

                    if (!empty($province['Districts']) && is_array($province['Districts'])){
                        foreach ($province['Districts'] as $district){
                            $nameDistrict = str_replace($arrFilterDistricts, '', $district['Name']);
                            $arrDistrict[] = [
                                'name' => $nameDistrict,
                                'slug' => eFunction::generateSlug($nameDistrict, '-'),
                                'id_api' => $province['Id'],
                                'created_at' => eFunction::getDateTimeNow(),
                                'updated_at' => eFunction::getDateTimeNow()
                            ];
                        }
                    }
                }
            }

            if (!empty($arrProvinces)){
                $this->provinceRepository->createMulti($arrProvinces);

                $allProvinces = $this->provinceRepository->getAllProvinceByFilter(['deleted_at' => true]);
                if (!empty($allProvinces) && !empty($arrDistrict)){
                    foreach ($allProvinces as $province){
                        foreach ($arrDistrict as $k => $district){
                            if ((int)$province['id_api'] === (int)$district['id_api']){
                                $arrDistrict[$k]['province_id'] = $province['id'];
                            }
                        }
                    }
                }

                if (!empty($arrDistrict)){
                    $this->districtRepository->createMulti($arrDistrict);
                }
            }


            DB::commit();

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-create-success'), []);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            DB::rollback();
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    private function curlProvision($url)
    {
        //https://thongtindoanhnghiep.co/rest-api
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $result = curl_exec($ch);
        $params = json_decode($result);

        return $params;
    }

    private function makeFilter($request, &$filter)
    {
        if ($request->has('key_word')) {
            $filter['key_word'] = $request->get('key_word');
        }
    }
}
