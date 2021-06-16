<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use \App\Repositories\Postgres\Admin\ProjectRepositoryInterface;
use \App\Models\Postgres\Admin\Project;
use \App\Repositories\Eloquent\SingleKeyModelRepository;

class ProjectRepository extends SingleKeyModelRepository implements ProjectRepositoryInterface
{

    public function getBlankModel()
    {
        return new Project();
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

    public function getOneProjectByFilter($filter)
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

    private function filter($filter, &$query)
    {
        if (isset($filter['slug'])) {
            $query = $query->where('projects.slug', $filter['slug']);
        }
    }
}
