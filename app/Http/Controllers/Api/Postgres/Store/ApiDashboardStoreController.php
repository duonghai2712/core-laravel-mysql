<?php

namespace App\Http\Controllers\Api\Postgres\Store;


use App\Elibs\eCrypt;
use App\Elibs\eFunction;
use App\Http\Controllers\Controller;
use App\Elibs\eResponse;
use App\Http\Requests\Api\PaginationRequest;
use App\Http\Requests\Api\Postgres\Store\Dashboard\GetListBranchRequest;
use App\Http\Requests\BaseRequest;
use App\Models\Postgres\Store\OrderBranch;
use App\Models\Postgres\Store\OrderStore;
use App\Repositories\Postgres\Admin\BranchRepositoryInterface;
use App\Repositories\Postgres\Admin\StoreRepositoryInterface;
use App\Repositories\Postgres\Store\CollectionRepositoryInterface;
use App\Repositories\Postgres\Store\OrderBranchRepositoryInterface;
use App\Repositories\Postgres\Store\OrderDeviceRepositoryInterface;
use App\Repositories\Postgres\Store\OrderRepositoryInterface;
use App\Repositories\Postgres\Store\OrderStoreRepositoryInterface;
use App\Repositories\Postgres\Store\StoreCrossDeviceCollectionRepositoryInterface;
use App\Repositories\Postgres\Store\StoreCrossDeviceStatisticRepositoryInterface;
use App\Repositories\Postgres\Store\StoreDeviceCollectionRepositoryInterface;
use App\Repositories\Postgres\Store\StoreDeviceStatisticRepositoryInterface;
use App\Repositories\Postgres\Store\TimeFrameRepositoryInterface;
use  DB;

class ApiDashboardStoreController extends Controller
{

    protected $orderRepository;
    protected $branchRepository;
    protected $storeRepository;
    protected $orderStoreRepository;
    protected $orderBranchRepository;
    protected $orderDeviceRepository;
    protected $collectionRepository;
    protected $timeFrameRepository;
    protected $storeDeviceCollectionRepository;
    protected $storeDeviceStatisticRepository;
    protected $storeCrossDeviceCollectionRepository;
    protected $storeCrossDeviceStatisticRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        BranchRepositoryInterface $branchRepository,
        StoreRepositoryInterface $storeRepository,
        StoreDeviceCollectionRepositoryInterface $storeDeviceCollectionRepository,
        StoreDeviceStatisticRepositoryInterface $storeDeviceStatisticRepository,
        StoreCrossDeviceStatisticRepositoryInterface $storeCrossDeviceStatisticRepository,
        StoreCrossDeviceCollectionRepositoryInterface $storeCrossDeviceCollectionRepository,
        TimeFrameRepositoryInterface $timeFrameRepository,
        CollectionRepositoryInterface $collectionRepository,
        OrderStoreRepositoryInterface $orderStoreRepository,
        OrderBranchRepositoryInterface $orderBranchRepository,
        OrderDeviceRepositoryInterface $orderDeviceRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->storeRepository = $storeRepository;
        $this->storeCrossDeviceStatisticRepository = $storeCrossDeviceStatisticRepository;
        $this->storeCrossDeviceCollectionRepository = $storeCrossDeviceCollectionRepository;
        $this->timeFrameRepository = $timeFrameRepository;
        $this->collectionRepository = $collectionRepository;
        $this->orderStoreRepository = $orderStoreRepository;
        $this->storeDeviceCollectionRepository = $storeDeviceCollectionRepository;
        $this->storeDeviceStatisticRepository = $storeDeviceStatisticRepository;
        $this->orderBranchRepository = $orderBranchRepository;
        $this->orderDeviceRepository = $orderDeviceRepository;
        $this->branchRepository = $branchRepository;
    }

    public function index(BaseRequest $request)
    {
        try{
            $storeAccountInfo = $request->get('storeAccountInfo');
            $isAccountBooking = eFunction::isAccountBooking($storeAccountInfo);
            $isAdmin = eFunction::isAdminStore($storeAccountInfo);
            $totalTimeCrossPlay = $timeUsedCross = 0;
            //thời lượng cửa hàng sử dụng
            $filterStoreDeviceStatistic['project_id'] = $storeAccountInfo['project_id'];
            $filterStoreDeviceStatistic['store_id'] = $storeAccountInfo['store_id'];
            if (!empty($isAccountBooking) && empty($isAdmin)){
                $filterStoreDeviceStatistic['branch_id'] = $storeAccountInfo['branch_id'];
            }

            $storeDeviceStatistics = $this->storeDeviceStatisticRepository->countTotalTimeStoreUsedByFilter($filterStoreDeviceStatistic);

            //Phát quảng cáo chéo cho cửa hàng khác (Là bên cửa hàng khác đặt vào mình)
            if (!empty($isAccountBooking) && empty($isAdmin)){
                $listOrderInCross = $this->orderBranchRepository->getAllOrderBranchByFilter(['branch_id' => $storeAccountInfo['branch_id'], 'status' => OrderBranch::STATUS_USING]);
            }else{
                $listOrderInCross = $this->orderStoreRepository->getAllOrderStoreByFilter(['store_id' => $storeAccountInfo['store_id'], 'status' => OrderStore::STATUS_USING]);
            }

            if (!empty($listOrderInCross)){
                $idsOrder = collect($listOrderInCross)->pluck('order_id')->filter()->unique()->values()->toArray();
                $storeCrossDeviceStatistics = $this->storeCrossDeviceStatisticRepository->countTotalTimePlayCrossUsedByFilter(['order_id' => $idsOrder, 'project_id' => $storeAccountInfo['project_id']]);
                if (!empty($storeCrossDeviceStatistics)){
                    $timeUsedCross = collect($storeCrossDeviceStatistics)->sum('total_time');
                }
            }

            //Đã sử dụng quảng cáo chéo ở cửa hàng khác (Là mình đi đặt bên khác)
            $filterOrder['store_id'] = $storeAccountInfo['store_id'];
            $filterOrder['project_id'] = $storeAccountInfo['project_id'];
            $filterOrder['isDelete'] = true;
            if (!empty($isAccountBooking) && empty($isAdmin)){
                $filterOrder['branch_id'] = $storeAccountInfo['branch_id'];
            }

            $orders = $this->orderRepository->getAllOrderByFilter($filterOrder);
            if (!empty($orders)){
                $idsOrder = collect($orders)->pluck('id')->values()->toArray();
                $storeCrossPlayDeviceStatistics = $this->storeCrossDeviceStatisticRepository->countTotalTimePlayCrossUsedByFilter(['order_id' => $idsOrder, 'project_id' => $storeAccountInfo['project_id']]);
                if (!empty($storeCrossPlayDeviceStatistics)){
                    $totalTimeCrossPlay = collect($storeCrossPlayDeviceStatistics)->sum('total_time');
                }
            }

            //Thống kê chung
            $output['sidebar'] = [
                'total_time' => !empty($storeDeviceStatistics) ? collect($storeDeviceStatistics)->sum('total_time') : 0,
                'time_used_cross' => $timeUsedCross,
                'time_cross_other_store' => $totalTimeCrossPlay,
                'current_point' => $storeAccountInfo['current_point'],
                'total_point' => $storeAccountInfo['total_point'],
            ];

            //Biểu đồ thể hiện tổng số lượt phát 10 ngày gần nhất
            $start_date = date('Y-m-d', strtotime('now'));
            $end_date = date('Y-m-d', strtotime('now + 10 days'));
            $filterChart['store_id'] = $storeAccountInfo['store_id'];
            $filterChart['start_date'] = $start_date;
            $filterChart['end_date'] = $end_date;
            $filterChart['project_id'] = $storeAccountInfo['project_id'];
            if (!empty($isAccountBooking) && empty($isAdmin)){
                $filterChart['branch_id'] = $storeAccountInfo['branch_id'];
            }

            $dataCharts = $this->storeDeviceStatisticRepository->getDataForChart($filterChart);
            $output['chart'] = !empty($dataCharts) ? collect($dataCharts)->keyBy('date_at')->toArray() : [];

            //Thống kê media
            $totalMediaPlaying = 0;
            $collections = $this->collectionRepository->getDataForStatisticMedia(['store_id' => $storeAccountInfo['store_id'], 'deleted_at' => true, 'project_id' => $storeAccountInfo['project_id']]);
            $storeDeviceCollections = $this->storeDeviceCollectionRepository->getAllStoreDeviceCollectionByFilter((['store_id' => $storeAccountInfo['store_id'], 'project_id' => $storeAccountInfo['project_id']]));
            $orders = $this->orderRepository->getAllOrderByFilter(['store_id' => $storeAccountInfo['store_id'], 'deleted_at' => true, 'project_id' => $storeAccountInfo['project_id']]);
            if (!empty($orders)){
                $idsOrder = collect($orders)->pluck('id')->values()->toArray();
                $storeCrossDeviceCollections = $this->storeCrossDeviceCollectionRepository->getAllStoreCrossDeviceCollectionForDashboardByFilter(['order_id' => $idsOrder, 'project_id' => $storeAccountInfo['project_id']]);
                $totalMediaPlaying = $totalMediaPlaying + count($storeCrossDeviceCollections);
            }

            $totalMediaPlaying = $totalMediaPlaying + count($storeDeviceCollections);

            $output['statistic'] = [
                'images' => !empty($collections[0]['total_image']) ? $collections[0]['total_image'] : 0,
                'videos' => !empty($collections[0]['total_video']) ? $collections[0]['total_video'] : 0,
                'playing' => $totalMediaPlaying,
            ];

            return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $output);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function mediaPlaybackStatistics(PaginationRequest $request)
    {
        try{
            $filter = $filterOrder = $filterStoreCrossStatistic = [];
            $limit = $request->limit();
            $storeAccountInfo = $request->get('storeAccountInfo');
            $isAccountBooking = eFunction::isAccountBooking($storeAccountInfo);
            $this->makeFilter($request, $filter);
            $filterOrder['store_id'] = $storeAccountInfo['store_id'];
            $filterOrder['project_id'] = $storeAccountInfo['project_id'];
            $filterOrder['isDelete'] = true;

            $isAdminStore = eFunction::isAdminStore($storeAccountInfo);
            if (empty($isAdminStore)){
                $filterOrder['store_account_id'] = $storeAccountInfo['id'];
            }

            $orders = $this->orderRepository->getAllOrderByFilter($filterOrder);
            if (empty($orders)){
                return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
            }

            $orderIds = collect($orders)->pluck('id')->values()->toArray();

            //Nếu có lọc theo chi nhánh thì lấy tất cả các id của đơn hàng trong chi nhánh đó
            if ((!empty($isAccountBooking) && empty($isAdminStore)) || empty($isAccountBooking)){
                $filter['branch_id'] = $isAccountBooking['branch_id'];
            }

            //Nếu có lọc theo cửa hàng thì lấy tất cả các id của đơn hàng trong cửa hàng đó
            if (!empty($filter['store_id']) && !empty($isAdminStore)){
                $orderStores = $this->orderStoreRepository->getAllOrderStoreByFilter(['store_id' => (int)$filter['store_id'], 'order_id' => $orderIds, 'status' => OrderStore::STATUS_USING, 'project_id' => $storeAccountInfo['project_id']]);
                if (!empty($orderStores)){
                    $orderInOrderStoreIds = collect($orderStores)->pluck('order_id')->filter()->unique()->values()->toArray();
                    $orderIds = array_values(array_intersect($orderIds, $orderInOrderStoreIds));
                }
            }

            if (!empty($filter['branch_id'])){
                $orderBranches = $this->orderBranchRepository->getAllOrderBranchByFilter(['branch_id' => (int)$filter['branch_id'], 'order_id' => $orderIds, 'project_id' => $storeAccountInfo['project_id'], 'status' => OrderBranch::STATUS_USING]);
                if (!empty($orderBranches)){
                    $orderInOrderBranchesIds = collect($orderBranches)->pluck('order_id')->filter()->unique()->values()->toArray();
                    $orderIds = array_values(array_intersect($orderIds, $orderInOrderBranchesIds));
                }
            }

            //Nếu có lọc theo khoảng thời gian thì lấy tất cả các id của đơn hàng trong khoảng thời gian đó
            if (!empty($filter['start_date']) && !empty($filter['end_date'])){
                $timeFrames = $this->timeFrameRepository->getAllTimeFramesByFilter(['start_date' => $filter['start_date'], 'end_date' => $filter['end_date'], 'order_id' => $orderIds]);
                if (!empty($timeFrames)){
                    $orderInTimeFrameIds = collect($timeFrames)->pluck('order_id')->filter()->unique()->values()->toArray();
                    $orderIds = array_values(array_intersect($orderIds, $orderInTimeFrameIds));
                }
            }

            //Nếu mà không có id của đơn hàng nào thì
            if (empty($orderIds)){
                return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
            }

            $filterStoreCrossStatistic['order_id'] = $orderIds;
            $filterStoreCrossStatistic['project_id'] = $storeAccountInfo['project_id'];

            if (!empty($filter['key_word'])){
                $collections = $this->collectionRepository->getAllCollectionByFilter(['key_word' => $filter['key_word'], 'project_id' => $storeAccountInfo['project_id']]);
                if (empty($collections)){
                    return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), []);
                }

                $filterStoreCrossStatistic['collection_id'] = collect($collections)->pluck('id')->values()->toArray();
            }

            $storeCrossDeviceStatistics = $this->storeCrossDeviceStatisticRepository->getListStoreCrossDeviceStatisticByFilter($limit, $filterStoreCrossStatistic);
            if (!empty($storeCrossDeviceStatistics['data'])){
                foreach ($storeCrossDeviceStatistics['data'] as $k => $storeCrossDevice){
                    if (!empty($storeCrossDevice['collection']['source'])) {
                        $storeCrossDeviceStatistics['data'][$k]['collection']['source'] = asset($storeCrossDevice['collection']['source']);
                    }

                    if (!empty($storeCrossDevice['collection']['source_thumb'])) {
                        $storeCrossDeviceStatistics['data'][$k]['collection']['source_thumb'] = asset($storeCrossDevice['collection']['source_thumb']);
                    }
                }
            }

            return eResponse::responsePagination(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), $storeCrossDeviceStatistics);

        }catch(\Exception $e){
            \Log::info($e->getMessage());
            return eResponse::response(STATUS_API_ERROR, __('notification.system.errors'), []);
        }
    }

    public function getListStorePlayAds(BaseRequest $request)
    {
        $stores = [];
        $storeAccountInfo = $request->get('storeAccountInfo');
        $orders = $this->orderRepository->getAllOrderByFilter(['store_id' => $storeAccountInfo['store_id'], 'isDelete' => true, 'project_id' => $storeAccountInfo['project_id']]);
        if (!empty($orders)){
            $idsOrder = collect($orders)->pluck('id')->values()->toArray();
            $orderStores = $this->orderStoreRepository->getAllOrderStoreByFilter(['order_id' => $idsOrder, 'status' => OrderStore::STATUS_USING]);
            if (!empty($orderStores)){
                $storeIds = collect($orderStores)->pluck('store_id')->filter()->unique()->values()->toArray();
                $storeCrossStatistics = $this->storeCrossDeviceStatisticRepository->getAllStoreCrossDeviceStatisticByFilter(['store_id' => $storeIds, 'project_id' => $storeAccountInfo['project_id']]);
                if (!empty($storeCrossStatistics)){
                    $storeIds = collect($storeCrossStatistics)->pluck('store_id')->filter()->unique()->values()->toArray();
                    if (!empty($storeIds)){
                        $stores = $this->storeRepository->getAllStoreByFilter(['id' => $storeIds, 'isDelete' => true, 'project_id' => $storeAccountInfo['project_id']]);
                    }
                }
            }
        }

        return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), ['stores' => $stores]);
    }

    public function getListBranchPlayAds(GetListBranchRequest $request)
    {
        $branches = [];
        $storeAccountInfo = $request->get('storeAccountInfo');
        $storeId = $request->get('store_id');
        $orders = $this->orderRepository->getAllOrderByFilter(['store_id' => $storeAccountInfo['store_id'], 'isDelete' => true, 'project_id' => $storeAccountInfo['project_id']]);
        if (!empty($orders)){
            $orderIds = collect($orders)->pluck('id')->values()->toArray();
            $orderStores = $this->orderStoreRepository->getAllOrderStoreByFilter(['store_id' => (int)$storeId, 'order_id' => $orderIds, 'project_id' => $storeAccountInfo['project_id'], 'status' => OrderStore::STATUS_USING]);
            if (!empty($orderStores)){
                $orderStoreIds = collect($orderStores)->pluck('id')->filter()->unique()->values()->toArray();
                if (!empty($orderStoreIds)){
                    $orderBranches = $this->orderBranchRepository->getAllOrderBranchByFilter(['order_store_id' => $orderStoreIds, 'oder_id' => $orderIds, 'project_id' => $storeAccountInfo['project_id'], 'status' => OrderBranch::STATUS_USING]);
                    if (!empty($orderBranches)){
                        $branchIds = collect($orderBranches)->pluck('branch_id')->filter()->unique()->values()->toArray();
                        $storeCrossStatistics = $this->storeCrossDeviceStatisticRepository->getAllStoreCrossDeviceStatisticByFilter(['branch_id' => $branchIds, 'project_id' => $storeAccountInfo['project_id']]);
                        if (!empty($storeCrossStatistics)){
                            $branchIds = collect($storeCrossStatistics)->pluck('branch_id')->filter()->unique()->values()->toArray();
                            if (!empty($branchIds)){
                                $branches = $this->branchRepository->getAllBranchForStoreByFilter(['id' => $branchIds, 'isDelete' => true, 'project_id' => $storeAccountInfo['project_id']]);
                            }
                        }

                    }
                }
            }
        }

        return eResponse::response(STATUS_API_SUCCESS, __('notification.api-form-get-data-success'), ['branches' => $branches]);
    }

    private function makeFilter($request, &$filter)
    {
        if ($request->has('key_word')) {
            $filter['key_word'] = $request->get('key_word');
        }

        if ($request->has('branch_id')) {
            $filter['branch_id'] = $request->get('branch_id');
        }

        if ($request->has('store_id')) {
            $filter['store_id'] = $request->get('store_id');
        }

        if ($request->has('start_date')) {
            $filter['start_date'] = $request->get('start_date');
        }

        if ($request->has('end_date')) {
            $filter['end_date'] = $request->get('end_date');
        }
    }
}
