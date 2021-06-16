<?php namespace Tests\Repositories\Postgres\Admin;

use App\Models\Postgres\Admin\Image;
use Tests\TestCase;

class ImageRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\ImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ImageRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $images = factory(Image::class, 3)->create();
        $imageIds = $images->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\ImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ImageRepositoryInterface::class);
        $this->assertNotNull($repository);

        $imagesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(Image::class, $imagesCheck[0]);

        $imagesCheck = $repository->getByIds($imageIds);
        $this->assertEquals(3, count($imagesCheck));
    }

    public function testFind()
    {
        $images = factory(Image::class, 3)->create();
        $imageIds = $images->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\ImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ImageRepositoryInterface::class);
        $this->assertNotNull($repository);

        $imageCheck = $repository->find($imageIds[0]);
        $this->assertEquals($imageIds[0], $imageCheck->id);
    }

    public function testCreate()
    {
        $imageData = factory(Image::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\ImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ImageRepositoryInterface::class);
        $this->assertNotNull($repository);

        $imageCheck = $repository->create($imageData->toFillableArray());
        $this->assertNotNull($imageCheck);
    }

    public function testUpdate()
    {
        $imageData = factory(Image::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\ImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ImageRepositoryInterface::class);
        $this->assertNotNull($repository);

        $imageCheck = $repository->update($imageData, $imageData->toFillableArray());
        $this->assertNotNull($imageCheck);
    }

    public function testDelete()
    {
        $imageData = factory(Image::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\ImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\ImageRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($imageData);

        $imageCheck = $repository->find($imageData->id);
        $this->assertNull($imageCheck);
    }

}
