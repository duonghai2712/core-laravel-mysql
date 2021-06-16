<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Models\Postgres\Admin\OauthAccessToken;
use App\Repositories\Postgres\Admin\OauthAccessTokenRepositoryInterface;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

/**
 * @method \App\Models\Postgres\Admin\OauthAccessToken[] getEmptyList()
 * @method \App\Models\Postgres\Admin\OauthAccessToken[]|\Traversable|array all($order = null, $direction = null)
 * @method \App\Models\Postgres\Admin\OauthAccessToken[]|\Traversable|array get($order, $direction, $offset, $limit)
 * @method \App\Models\Postgres\Admin\OauthAccessToken create($value)
 * @method \App\Models\Postgres\Admin\OauthAccessToken find($id)
 * @method \App\Models\Postgres\Admin\OauthAccessToken[]|\Traversable|array allByIds($ids, $order = null, $direction = null, $reorder = false)
 * @method \App\Models\Postgres\Admin\OauthAccessToken[]|\Traversable|array getByIds($ids, $order = null, $direction = null, $offset = null, $limit = null);
 * @method \App\Models\Postgres\Admin\OauthAccessToken update($model, $input)
 * @method \App\Models\Postgres\Admin\OauthAccessToken save($model);
 */
class OauthAccessTokenRepository extends SingleKeyModelRepository implements OauthAccessTokenRepositoryInterface
{
    public function getBlankModel()
    {
        return new OauthAccessToken();
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

    public function updateOldTokenRevoke($id, $userId, $clientId)
    {
        $model = $this->getBlankModel();
        $model->where('id', '<>', $id)
            ->where('user_id', $userId)
            ->where('client_id', $clientId)
            ->update(['revoked' => true]);

        return true;
    }
}
