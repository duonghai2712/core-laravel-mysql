<?php namespace Tests\Repositories\Postgres\Admin;

use App\Models\Postgres\Admin\OauthAccessToken;
use Tests\TestCase;

class OauthAccessTokenRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\OauthAccessTokenRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OauthAccessTokenRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $oauthAccessTokens = factory(OauthAccessToken::class, 3)->create();
        $oauthAccessTokenIds = $oauthAccessTokens->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\OauthAccessTokenRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OauthAccessTokenRepositoryInterface::class);
        $this->assertNotNull($repository);

        $oauthAccessTokensCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(OauthAccessToken::class, $oauthAccessTokensCheck[0]);

        $oauthAccessTokensCheck = $repository->getByIds($oauthAccessTokenIds);
        $this->assertEquals(3, count($oauthAccessTokensCheck));
    }

    public function testFind()
    {
        $oauthAccessTokens = factory(OauthAccessToken::class, 3)->create();
        $oauthAccessTokenIds = $oauthAccessTokens->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\OauthAccessTokenRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OauthAccessTokenRepositoryInterface::class);
        $this->assertNotNull($repository);

        $oauthAccessTokenCheck = $repository->find($oauthAccessTokenIds[0]);
        $this->assertEquals($oauthAccessTokenIds[0], $oauthAccessTokenCheck->id);
    }

    public function testCreate()
    {
        $oauthAccessTokenData = factory(OauthAccessToken::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\OauthAccessTokenRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OauthAccessTokenRepositoryInterface::class);
        $this->assertNotNull($repository);

        $oauthAccessTokenCheck = $repository->create($oauthAccessTokenData->toFillableArray());
        $this->assertNotNull($oauthAccessTokenCheck);
    }

    public function testUpdate()
    {
        $oauthAccessTokenData = factory(OauthAccessToken::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\OauthAccessTokenRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OauthAccessTokenRepositoryInterface::class);
        $this->assertNotNull($repository);

        $oauthAccessTokenCheck = $repository->update($oauthAccessTokenData, $oauthAccessTokenData->toFillableArray());
        $this->assertNotNull($oauthAccessTokenCheck);
    }

    public function testDelete()
    {
        $oauthAccessTokenData = factory(OauthAccessToken::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\OauthAccessTokenRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OauthAccessTokenRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($oauthAccessTokenData);

        $oauthAccessTokenCheck = $repository->find($oauthAccessTokenData->id);
        $this->assertNull($oauthAccessTokenCheck);
    }

}
