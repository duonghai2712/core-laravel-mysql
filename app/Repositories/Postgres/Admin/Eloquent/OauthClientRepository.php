<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Models\Postgres\Admin\OauthClient;
use App\Repositories\Postgres\Admin\OauthClientRepositoryInterface;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

/**
 * @method \App\Models\Postgres\Admin\OauthClient[] getEmptyList()
 * @method \App\Models\Postgres\Admin\OauthClient[]|\Traversable|array all($order = null, $direction = null)
 * @method \App\Models\Postgres\Admin\OauthClient[]|\Traversable|array get($order, $direction, $offset, $limit)
 * @method \App\Models\Postgres\Admin\OauthClient create($value)
 * @method \App\Models\Postgres\Admin\OauthClient find($id)
 * @method \App\Models\Postgres\Admin\OauthClient[]|\Traversable|array allByIds($ids, $order = null, $direction = null, $reorder = false)
 * @method \App\Models\Postgres\Admin\OauthClient[]|\Traversable|array getByIds($ids, $order = null, $direction = null, $offset = null, $limit = null);
 * @method \App\Models\Postgres\Admin\OauthClient update($model, $input)
 * @method \App\Models\Postgres\Admin\OauthClient save($model);
 */
class OauthClientRepository extends SingleKeyModelRepository implements OauthClientRepositoryInterface
{
    protected $querySearchTargets = ['name', 'secret', 'redirect'];

    public function getBlankModel()
    {
        return new OauthClient();
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
