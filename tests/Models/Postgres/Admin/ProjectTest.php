<?php namespace Tests\Models\Postgres\Admin;

use App\Models\Postgres\Admin\Project;
use Tests\TestCase;

class ProjectTest extends TestCase
{

    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Models\Postgres\Admin\Project $project */
        $project = new Project();
        $this->assertNotNull($project);
    }

    public function testStoreNew()
    {
        /** @var  \App\Models\Postgres\Admin\Project $project */
        $projectModel = new Project();

        $projectData = factory(Project::class)->make();
        foreach( $projectData->toFillableArray() as $key => $value ) {
            $projectModel->$key = $value;
        }
        $projectModel->save();

        $this->assertNotNull(Project::find($projectModel->id));
    }

}
