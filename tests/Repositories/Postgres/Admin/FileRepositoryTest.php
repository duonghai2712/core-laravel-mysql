<?php namespace Tests\Repositories\Postgres\Admin;

use App\Models\Postgres\File;
use Tests\TestCase;

class FileRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\FileRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\FileRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $files = factory(File::class, 3)->create();
        $fileIds = $files->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\FileRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\FileRepositoryInterface::class);
        $this->assertNotNull($repository);

        $filesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(File::class, $filesCheck[0]);

        $filesCheck = $repository->getByIds($fileIds);
        $this->assertEquals(3, count($filesCheck));
    }

    public function testFind()
    {
        $files = factory(File::class, 3)->create();
        $fileIds = $files->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\FileRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\FileRepositoryInterface::class);
        $this->assertNotNull($repository);

        $fileCheck = $repository->find($fileIds[0]);
        $this->assertEquals($fileIds[0], $fileCheck->id);
    }

    public function testCreate()
    {
        $fileData = factory(File::class)->make();

        /** @var  \App\Repositories\Postgres\FileRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\FileRepositoryInterface::class);
        $this->assertNotNull($repository);

        $fileCheck = $repository->create($fileData->toFillableArray());
        $this->assertNotNull($fileCheck);
    }

    public function testUpdate()
    {
        $fileData = factory(File::class)->create();

        /** @var  \App\Repositories\Postgres\FileRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\FileRepositoryInterface::class);
        $this->assertNotNull($repository);

        $fileCheck = $repository->update($fileData, $fileData->toFillableArray());
        $this->assertNotNull($fileCheck);
    }

    public function testDelete()
    {
        $fileData = factory(File::class)->create();

        /** @var  \App\Repositories\Postgres\FileRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\FileRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($fileData);

        $fileCheck = $repository->find($fileData->id);
        $this->assertNull($fileCheck);
    }

}
