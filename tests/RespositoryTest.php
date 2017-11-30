<?php

namespace Jvleeuwen\Cspreporter\tests;

use Mockery as m;
use callcenterrepository;
use callcentermonitoring;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\File;
use Jvleeuwen\Broadsoft\Facades\XmlFacade;
use Jvleeuwen\Broadsoft\BroadsoftServiceProvider;
use Jvleeuwen\Broadsoft\Facades\CallCenterMonitoringFacade;
use jvleeuwen\broadsoft\Repositories\BsCallCenterRepository;
use Jvleeuwen\Broadsoft\Services\CallCenterMonitoringService;
use Jvleeuwen\Broadsoft\Repositories\CallCenterMonitoringRepository;
use \Jvleeuwen\Broadsoft\Models\CallCenterMonitoring as CallCenterMonitoringModel;

class RepositoryTest extends TestCase
{
    /**
     * @var Mockery\Mock
     */
    protected $application_mock;

    /**
     * @var  ServiceProvider
     * use xml;
     */
    protected $service_provider;

    protected function getPackageProviders($app)
    {
        return [BroadsoftServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'callcentermonitoring' => CallCenterMonitoringFacade::class,
            'callcenterrepository' => CallCenterMonitoringRepository::class,
        ];
    }

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->setUpMocks();
        $this->service_provider = new BroadsoftServiceProvider($this->application_mock);
        // $this->CallCenterMonitoringContract = m::mock('Jvleeuwen\Broadsoft\Contracts\CallCenterMonitoringContract');
    }

    /**
     * Teardown mocks
     */
    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    protected function setUpMocks()
    {
        $this->application_mock = m::mock(Application::class);
    }

    // /**
    // * @test
    // */
    // public function it_can_save_data_to_the_database()
    // {
    //     m::mock(CallCenterMonitoringService::class)->shouldReceive('GetEventType')->once()->andReturn(true);
    // }
}
