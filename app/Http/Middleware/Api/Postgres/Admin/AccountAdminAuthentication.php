<?php

namespace App\Http\Middleware\Api\Postgres\Admin;

use App\Elibs\eFunction;
use App\Repositories\Postgres\Admin\AccountRepositoryInterface;
use App\Elibs\eResponse;
use Closure;

class AccountAdminAuthentication
{
    protected $accountRepository;

    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
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
            $account = $this->accountRepository->getOneArrayAccountByFilter(['api_access_token' => $token]);
            if (!empty($account) && !empty($account['project_id']) && !empty($account['id'])) {
                $accountInfo = [
                    'id' => $account['id'],
                    'project_id' => $account['project_id'],
                    'rule' => $account['rule'],
                ];

                $request->merge(compact('accountInfo'));

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
