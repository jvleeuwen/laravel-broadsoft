<?php

namespace Jvleeuwen\Cspreporter\tests;

use Mockery as m;
use callcentermonitoring;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Event;
use Jvleeuwen\Broadsoft\BroadsoftServiceProvider;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Jvleeuwen\Broadsoft\Facades\CallCenterMonitoringFacade;
use Jvleeuwen\Broadsoft\Controllers\CallCenterMonitoringController;

class CallCenterMonitoringTest extends TestCase
{

    /**
     * @var Mockery\Mock
     */
    protected $application_mock;

    /**
     * @var  ServiceProvider
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

    /**
     * @test
     */
    public function it_registers_the_service()
    {
        $concrete = $this->app->make('Jvleeuwen\Broadsoft\Facades\CallCenterMonitoringFacade');
        $this->assertInstanceOf('Jvleeuwen\Broadsoft\Facades\CallCenterMonitoringFacade', $concrete);
    }

    /**
     * @test
     */
    public function it_has_access_to_the_bindings()
    {
        $this->assertInternalType('array', $this->service_provider->provides());
        $this->assertSame('callcentermonitoring', $this->service_provider->provides()[0]);
    }

    /**
    * @test
    */
    public function it_can_parse_call_center_monitoring_event()
    {
        $req = File::get('docs/XSI_messages/CallCenterMonitoring/CallCenterMonitoringEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = callcentermonitoring::GetEventType($xml, $req);
        $this->assertSame($event, 'blaat');
    }

    // /**
    // * @test
    // */
    // public function it_can_get_the_event_type_from_xml()
    // {
    //     $this->markTestIncomplete('This test has not been implemented yet.');
    // }

    // /**
    // * @test
    // */
    // public function it_can_parse_subscripiont_terminated_event()
    // {
    //     $this->markTestIncomplete('This test has not been implemented yet.');
    // }

    // /**
    //  * @test
    //  */
    // public function it_provides_cspreporter()
    // {
    //     $this->assertInternalType('array', $this->service_provider->provides());
    //     $this->assertSame('cspreporter', $this->service_provider->provides()[0]);
    // }

    // /**
    //  * @test
    //  */
    // public function it_can_reached_the_test_function()
    // {
    //     $this->assertSame(cspreporter::test(), 'u have reached the test function');
    // }

    // /**
    //  * *@test
    //  */
    // public function it_can_load_rss()
    // {
    //     $this->assertInternalType('array', cspreporter::uri($this->file));
    // }

    // /**
    //  * *@test
    //  */
    // public function it_can_get_actual_test_xml_from_tests_dir()
    // {
    //     $this->assertCount(1, cspreporter::file($this->file));
    // }

    // /**
    //  * *@test
    //  */
    // public function it_can_parse_rss()
    // {
    //     $this->assertInternalType('array', cspreporter::ParseRss(cspreporter::file($this->file)));
    // }

    // /**
    //  * @test
    //  */
    // public function it_can_parse_all_feed_item_fields()
    // {
    //     $feed = cspreporter::uri($this->file);
    //     foreach ($feed as $item) {
    //         $this->assertInternalType('integer', $item['id']);
    //         $this->assertInternalType('string', $item['title']);
    //         $this->assertInternalType('string', $item['description']);
    //         $this->assertInternalType('string', $item['pubDate']);
    //         $this->assertInternalType('string', $item['startDate']);
    //         $this->assertInternalType('string', $item['endDate']);
    //         $this->assertInternalType('string', $item['category']);
    //         $this->assertInternalType('string', $item['link']);
    //         $this->assertInternalType('array', $item['services']);
    //     }
    // }

    // /**
    //  * *@test
    //  */
    // public function it_can_run_the_full_cycle()
    // {
    //     $this->assertInternalType('array', cspreporter::uri($this->file));
    // }

    // /**
    //  * *@test
    //  */
    // public function it_can_not_parse_uri_feed()
    // {
    //     $this->assertSame('invalid XML', cspreporter::uri('i dont exist'));
    // }
}
