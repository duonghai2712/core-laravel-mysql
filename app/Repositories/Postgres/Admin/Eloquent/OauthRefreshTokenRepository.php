<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Models\Postgres\Admin\OauthRefreshToken;
use App\Repositories\Postgres\Admin\OauthRefreshTokenRepositoryInterface;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

/**
 * @method \App\Models\Postgres\Admin\OauthRefreshToken[] getEmptyList()
 * @method \App\Models\Postgres\Admin\OauthRefreshToken[]|\Traversable|array all($order = null, $direction = null)
 * @method \App\Models\Postgres\Admin\OauthRefreshToken[]|\Traversable|array get($order, $direction, $offset, $limit)
 * @method \App\Models\Postgres\Admin\OauthRefreshToken create($value)
 * @method \App\Models\Postgres\Admin\OauthRefreshToken find($id)
 * @method \App\Models\Postgres\Admin\OauthRefreshToken[]|\Traversable|array allByIds($ids, $order = null, $direction = null, $reorder = false)
 * @method \App\Models\Postgres\Admin\OauthRefreshToken[]|\Traversable|array getByIds($ids, $order = null, $direction = null, $offset = null, $limit = null);
 * @method \App\Models\Postgres\Admin\OauthRefreshToken update($model, $input)
 * @method \App\Models\Postgres\Admin\OauthRefreshToken save($model);
 */
class OauthRefreshTokenRepository extends SingleKeyModelRepository implements OauthRefreshTokenRepositoryInterface
{
    public function getBlankModel()
    {
        return new OauthRefreshToken();
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

    public function updateOldAccessTokenRevoke($id, $accessTokenId)
    {
        $model = $this->getBlankModel();
        $model->where('id', '<>', $id)
            ->where('access_token_id', '<>', $accessTokenId)->first();
        $model->delete();

        return true;
    }
}
