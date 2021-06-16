<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use \App\Repositories\Postgres\Admin\RankRepositoryInterface;
use \App\Models\Postgres\Admin\Rank;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

class RankRepository extends SingleKeyModelRepository implements RankRepositoryInterface
{

    public function getBlankModel()
    {
        return new Rank();
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

    public function getOneArrayRankByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $dataX = $query->first();
        $data = [];

        if (!empty($dataX)){
            $data = $dataX->toArray();
        }

        return $data;
    }

    public function getOneObjectRankByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->first();

        return $data;
    }

    public function deleteAllRankByFilter($filter)
    {
        $query = $this->getBlankModel();

        $this->filter($filter, $query);

        $data = $query->delete();

        return $data;
    }

    public function getListRankByFilter($limit, $filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->paginate($limit)->toArray();

        return $data;
    }

    public function getAllRankByFilter($filter)
    {
        $query = $this->getBlankModel();
        $this->filter($filter, $query);

        $data = $query->get()->toArray();

        return $data;
    }

    private function filter($filter, &$query)
    {

        if (isset($filter['key_word'])) {
            $query = $query->search($filter['key_word']);
        }

        if (isset($filter['id'])) {
            if (is_array($filter['id'])) {
                $query = $query->whereIn('ranks.id', $filter['id']);
            } else {
                $query = $query->where('ranks.id', $filter['id']);
            }
        }

        if (isset($filter['deleted_at'])) {
            $query = $query->where('ranks.deleted_at', null);
        }

        if (isset($filter['direction']) && isset($filter['order'])) {
            $query = $query->orderBy('ranks.' . $filter['order'], $filter['direction']);
        }

        if (isset($filter['account_id'])) {
            $query = $query->where('ranks.account_id', $filter['account_id']);
        }

        if (isset($filter['project_id'])) {
            $query = $query->where('ranks.project_id', $filter['project_id']);
        }

    }

}
