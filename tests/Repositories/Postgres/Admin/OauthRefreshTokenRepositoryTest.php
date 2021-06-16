<?php namespace Tests\Repositories\Postgres\Admin;

use App\Models\Postgres\Admin\OauthRefreshToken;
use Tests\TestCase;

class OauthRefreshTokenRepositoryTest extends TestCase
{
    protected $useDatabase = true;

    public function testGetInstance()
    {
        /** @var  \App\Repositories\Postgres\Admin\OauthRefreshTokenRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OauthRefreshTokenRepositoryInterface::class);
        $this->assertNotNull($repository);
    }

    public function testGetList()
    {
        $oauthRefreshTokens = factory(OauthRefreshToken::class, 3)->create();
        $oauthRefreshTokenIds = $oauthRefreshTokens->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\OauthRefreshTokenRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OauthRefreshTokenRepositoryInterface::class);
        $this->assertNotNull($repository);

        $oauthRefreshTokensCheck = $repository->get('id', 'asc', 0, 1);
        $this->assertInstanceOf(OauthRefreshToken::class, $oauthRefreshTokensCheck[0]);

        $oauthRefreshTokensCheck = $repository->getByIds($oauthRefreshTokenIds);
        $this->assertEquals(3, count($oauthRefreshTokensCheck));
    }

    public function testFind()
    {
        $oauthRefreshTokens = factory(OauthRefreshToken::class, 3)->create();
        $oauthRefreshTokenIds = $oauthRefreshTokens->pluck('id')->toArray();

        /** @var  \App\Repositories\Postgres\Admin\OauthRefreshTokenRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OauthRefreshTokenRepositoryInterface::class);
        $this->assertNotNull($repository);

        $oauthRefreshTokenCheck = $repository->find($oauthRefreshTokenIds[0]);
        $this->assertEquals($oauthRefreshTokenIds[0], $oauthRefreshTokenCheck->id);
    }

    public function testCreate()
    {
        $oauthRefreshTokenData = factory(OauthRefreshToken::class)->make();

        /** @var  \App\Repositories\Postgres\Admin\OauthRefreshTokenRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OauthRefreshTokenRepositoryInterface::class);
        $this->assertNotNull($repository);

        $oauthRefreshTokenCheck = $repository->create($oauthRefreshTokenData->toFillableArray());
        $this->assertNotNull($oauthRefreshTokenCheck);
    }

    public function testUpdate()
    {
        $oauthRefreshTokenData = factory(OauthRefreshToken::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\OauthRefreshTokenRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OauthRefreshTokenRepositoryInterface::class);
        $this->assertNotNull($repository);

        $oauthRefreshTokenCheck = $repository->update($oauthRefreshTokenData, $oauthRefreshTokenData->toFillableArray());
        $this->assertNotNull($oauthRefreshTokenCheck);
    }

    public function testDelete()
    {
        $oauthRefreshTokenData = factory(OauthRefreshToken::class)->create();

        /** @var  \App\Repositories\Postgres\Admin\OauthRefreshTokenRepositoryInterface $repository */
        $repository = \App::make(\App\Repositories\Postgres\Admin\OauthRefreshTokenRepositoryInterface::class);
        $this->assertNotNull($repository);

        $repository->delete($oauthRefreshTokenData);

        $oauthRefreshTokenCheck = $repository->find($oauthRefreshTokenData->id);
        $this->assertNull($oauthRefreshTokenCheck);
    }

}
