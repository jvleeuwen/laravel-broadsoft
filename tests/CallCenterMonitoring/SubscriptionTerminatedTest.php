<?php

namespace Jvleeuwen\Cspreporter\tests\CallCenterMonitoring;

use Mockery as m;
use callcentermonitoring;
use xml;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Event;
use Jvleeuwen\Broadsoft\BroadsoftServiceProvider;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Jvleeuwen\Broadsoft\Facades\CallCenterMonitoringFacade;
use Jvleeuwen\Broadsoft\Controllers\CallCenterMonitoringController;
use Jvleeuwen\Broadsoft\Facades\XmlFacade;

class SubscriptionTerminatedTest extends TestCase
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
            'xml' => XmlFacade::class,
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
    public function it_can_parse_subscription_terminated_event()
    {
        $req = File::get('docs/XSI_messages/CallCenterMonitoring/SubscriptionTerminatedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = callcentermonitoring::SubscriptionTerminatedEvent($xml);
        $expected = [
            'eventType' => 'SubscriptionTerminatedEvent',
            'eventID' => '12345abc-12ab-12ab-12av-12345abcdefg',
            'sequenceNumber' => 1,
            'userId' => 'userId',
            'subscriptionId' => '12345abc-12ab-12ab-12av-12345abcdefg',
            'externalApplicationId' => 'CallCenterMonitoring',
            'httpContact' => 'uri',
        ];
        $this->assertSame($event, $expected);
    }

    /**
    * @test
    */
    public function it_can_not_parse_subscription_terminated_event()
    {
        $event = callcentermonitoring::SubscriptionTerminatedEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: SubscriptionTerminatedEvent');
    }

    /**
    * @test
    */
    public function it_can__not_get_the_event_type()
    {
        // $req = File::get('tests/broken.xml');

        // $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = callcentermonitoring::CallCenterMonitoringEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallCenterMonitoringEvent');
        // $this->expectException(ErrorException::class);
    }

    /**
    * @test
    */
    public function it_can_parse_xml()
    {
        $file = File::get('docs/XSI_messages/CallCenterMonitoring/SubscriptionTerminatedEvent.xml');
        // $this->assertSame(xml::parse($file),'bogus');
        $this->assertInternalType('object', xml::parse($file));
    }

    /**
    * @test
    */
    public function it_can_not_parse_xml()
    {
        $this->expectException(\ErrorException::class);
        xml::parse("bogus xml");
    }
}
