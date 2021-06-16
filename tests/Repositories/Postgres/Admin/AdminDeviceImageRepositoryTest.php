<?php namespace App\Repositories\Postgres\Admin\Eloquent;

use App\Models\Postgres\Admin\AdminDeviceImage;
use Tests\TestCase;

class AdminDeviceImageRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $adminDeviceImages = factory(AdminDeviceImage::class, 3)->create();
        $adminDeviceImageIds = $adminDeviceImages->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface::class);
        $this->assertNotNull($repository);

        $adminDeviceImagesCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(AdminDeviceImage::class, $adminDeviceImagesCheck[0]);

        $adminDeviceImagesCheck = $repository->getByIds($adminDeviceImageIds);
        $this->assertEquals(3, count($adminDeviceImagesCheck));
    }

    public function testFind()
    {
        $adminDeviceImages = factory(AdminDeviceImage::class, 3)->create();
        $adminDeviceImageIds = $adminDeviceImages->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface::class);
        $this->assertNotNull($repository);

        $adminDeviceImageCheck = $repository->find($adminDeviceImageIds[0]);
        $this->assertEquals($adminDeviceImageIds[0], $adminDeviceImageCheck->id);
    }

    public function testCreate()
    {
        $adminDeviceImageData = factory(AdminDeviceImage::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface::class);
        $this->assertNotNull($repository);

        $adminDeviceImageCheck = $repository->create($adminDeviceImageData->toFillableArray());
        $this->assertNotNull($adminDeviceImageCheck);
    }

    public function testUpdate()
    {
        $adminDeviceImageData = factory(AdminDeviceImage::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface::class);
        $this->assertNotNull($repository);

        $adminDeviceImageCheck = $repository->update($adminDeviceImageData, $adminDeviceImageData->toFillableArray());
        $this->assertNotNull($adminDeviceImageCheck);
    }

    public function testDelete()
    {
        $adminDeviceImageData = factory(AdminDeviceImage::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\AdminDeviceImageRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($adminDeviceImageData);

        $adminDeviceImageCheck = $repository->find($adminDeviceImageData->id);
        $this->assertNull($adminDeviceImageCheck);
    }

}
