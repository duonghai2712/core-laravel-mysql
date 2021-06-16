<?php

namespace App\Http\Middleware\Api\Postgres\Store;

use App\Elibs\eFunction;
use App\Elibs\eResponse;
use App\Models\Postgres\Store\Permission;
use App\Models\Postgres\Store\StoreAccount;
use App\Repositories\Postgres\Store\GroupStoreAccountPermissionRepositoryInterface;
use App\Repositories\Postgres\Store\PermissionRepositoryInterface;
use App\Repositories\Postgres\Store\StoreAccountRepositoryInterface;
use Illuminate\Support\Facades\Route;
use Closure;

class AccountStoreAuthentication
{
    protected $storeAccountRepository;
    protected $groupStoreAccountPermissionRepository;
    protected $permissionRepository;

    public function __construct(StoreAccountRepositoryInterface $storeAccountRepository, PermissionRepositoryInterface $permissionRepository, GroupStoreAccountPermissionRepositoryInterface $groupStoreAccountPermissionRepository)
    {
        $this->storeAccountRepository = $storeAccountRepository;
        $this->permissionRepository = $permissionRepository;
        $this->groupStoreAccountPermissionRepository = $groupStoreAccountPermissionRepository;
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
            $storeAccount = $this->storeAccountRepository->getOneArrayStoreAccountForLoginByFilter(['api_access_token' => $token]);
            if (!empty($storeAccount) && !empty($storeAccount['role'])&& !empty($storeAccount['username']) && !empty($storeAccount['project_id']) && !empty($storeAccount['id']) && !empty($storeAccount['store_id']) && isset($storeAccount['store']['total_point']) && isset($storeAccount['store']['current_point'])) {
                $hasPermission = false;
                $makeAds = StoreAccount::MAKE_ADS_FALSE;
                $router = $request->route()->getActionName();
                $nameAction = explode('@', $router);

                //Check quyền cho tài khoản
                if (!empty($storeAccount['role']) && !empty($nameAction[0]) && !empty($nameAction[1])){
                    if ((int)$storeAccount['role'] === StoreAccount::ADMIN){
                        $hasPermission = true;
                        $makeAds = StoreAccount::MAKE_ADS_TRUE;
                    }

                    if (in_array($nameAction[1], Permission::LIST_WITHOUT)){
                        $hasPermission = true;
                    }

                    if ((int)$storeAccount['role'] === StoreAccount::SUB){
                        if (!empty($storeAccount['branch']['make_ads']) && (int)$storeAccount['branch']['make_ads'] === StoreAccount::MAKE_ADS_TRUE){
                            $makeAds = StoreAccount::MAKE_ADS_TRUE;
                        }

                        if (!empty($storeAccount['group_store_account_id'])){
                            $groupStoreAccountPermissions = $this->groupStoreAccountPermissionRepository->getAllGroupStoreAccountPermissionsByFilter(['group_store_account_id' => (int)$storeAccount['group_store_account_id']]);
                            if (!empty($groupStoreAccountPermissions)){
                                $groupStoreAccountPermissions = collect($groupStoreAccountPermissions)->keyBy('permission_id')->toArray();
                                $AllPermissions = $this->permissionRepository->getAllPermissionsByFilter(['deleted_at' => true]);

                                if (!empty($AllPermissions)){
                                    foreach ($AllPermissions as $permission){
                                        if (!empty(Permission::LIST_PERMISSION[$nameAction[0]]) && $permission['key'] === Permission::LIST_PERMISSION[$nameAction[0]] && !empty($groupStoreAccountPermissions[$permission['id']])){
                                            if(in_array($nameAction[1], Permission::LIST_VIEW)){
                                                if (!empty($groupStoreAccountPermissions[$permission['id']]['view'])){
                                                    $hasPermission = true;
                                                }
                                            }else if(in_array($nameAction[1], Permission::LIST_ADD)){
                                                if (!empty($groupStoreAccountPermissions[$permission['id']]['add'])){
                                                    $hasPermission = true;
                                                }
                                            }else if(in_array($nameAction[1], Permission::LIST_UPDATE)){
                                                if (!empty($groupStoreAccountPermissions[$permission['id']]['update'])){
                                                    $hasPermission = true;
                                                }
                                            }else if(in_array($nameAction[1], Permission::LIST_DELETE)){
                                                if (!empty($groupStoreAccountPermissions[$permission['id']]['delete'])){
                                                    $hasPermission = true;
                                                }
                                            }

                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if (empty($hasPermission)){
                    return eResponse::response(STATUS_API_FALSE, __('notification.system.not-have-access'));
                }

                $storeAccountInfo = [
                    'id' => $storeAccount['id'],
                    'representative' =>  @$storeAccount['representative'],
                    'username' =>  $storeAccount['username'],
                    'role' => $storeAccount['role'],
                    'group_store_account_id' => !empty($storeAccount['group_store_account_id']) ? $storeAccount['group_store_account_id'] : null,
                    'branch_id' => !empty($storeAccount['branch_id']) ? $storeAccount['branch_id'] : null,
                    'store_id' => $storeAccount['store_id'],
                    'project_id' => $storeAccount['project_id'],
                    'make_ads' => $makeAds,
                    'slug' => $storeAccount['store']['slug'],
                    'total_point' => $storeAccount['role'] === StoreAccount::SUB && $makeAds === StoreAccount::MAKE_ADS_TRUE  ? $storeAccount['branch']['total_point'] : $storeAccount['store']['total_point'],
                    'current_point' => $storeAccount['role'] === StoreAccount::SUB && $makeAds === StoreAccount::MAKE_ADS_TRUE  ? $storeAccount['branch']['current_point'] : $storeAccount['store']['current_point'],
                ];

                $request->merge(compact('storeAccountInfo'));
                if ($request->has('name')){
                    $slug = eFunction::generateSlug($request->get('name'), '-');
                    $request->merge(compact('slug'));
                }

                if ($request->has('username')){
                    $username = strtolower($request->get('username'));
                    $request->merge(compact('username'));
                }

                if ($request->has('email')){
                    $email = strtolower($request->get('email'));
                    $request->merge(compact('email'));
                }

                return $next($request);
            }
        }

        return eResponse::response(STATUS_API_TOKEN_FALSE, __('notification.system.token-not-found'), []);
    }
}
