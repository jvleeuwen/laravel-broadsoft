<?php

namespace Jvleeuwen\Cspreporter\tests\CallCenterMonitoring;

use Mockery as m;
use callcentermonitoring;
// use Orchestra\Testbench\TestCase;
use Orchestra\Testbench\BrowserKit\TestCase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Event;
use Jvleeuwen\Broadsoft\BroadsoftServiceProvider;
use Jvleeuwen\Broadsoft\Facades\CallCenterMonitoringFacade;

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
        $event = callcentermonitoring::CallCenterMonitoringEvent($xml, $req);
        $expected = [
            'eventType' => 'CallCenterMonitoringEvent',
            'eventID' => '12345abc-12ab-12ab-12av-12345abcdefg',
            'sequenceNumber' => 1,
            'subscriptionId' => '12345abc-12ab-12ab-12av-12345abcdefg',
            'targetId' => 'targetId',
            'averageHandlingTime' => 1,
            'expectedWaitTime' => 2,
            'averageSpeedOfAnswer' => 3,
            'longestWaitTime' => 4,
            'numCallsInQueue' => 5,
            'numAgentsAssigned' => 6,
            'numAgentsStaffed' => 7,
            'numStaffedAgentsIdle' => 8,
            'numStaffedAgentsUnavailable' => 9
        ];
        $this->assertSame($event, $expected);
    }

    /**
    * @test
    */
    public function it_can__not_parse_call_center_monitoring_event()
    {
        $req = File::get('tests/broken.xml');
        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = callcentermonitoring::GetEventType($xml, $req);
        $this->assertSame($event, 'class CallCenterMonitoringEventBLAAT not found');
    }

    /**
    * @test
    */
    public function it_can__not_get_the_event_type()
    {
        $event = callcentermonitoring::CallCenterMonitoringEvent('bogus xml');
        $this->assertSame($event, 'can not parse event: CallCenterMonitoringEvent');
    }

    /**
     * @test
     */
    public function it_can_parse_incomming_requests()
    {
        // $crawler = $this->post('bs/CallCenterMonitoring', [
        //     'content' => 'First comment',
        // ])->seeJson([
        //     'content' => 'First comment',
        // ]);

        $xml = '<?xml version="1.0" encoding="UTF-8"?>
<xsi:Event xmlns:xsi="http://schema.broadsoft.com/xsi" xmlns:xsi1="http://www.w3.org/2001/XMLSchema-instance" xsi1:type="xsi:SubscriptionEvent">
    <xsi:eventID>12345abc-12ab-12ab-12av-12345abcdefg</xsi:eventID>
  <xsi:sequenceNumber>1</xsi:sequenceNumber>
  <xsi:userId>userId</xsi:userId>
  <xsi:externalApplicationId>CallCenterMonitoring</xsi:externalApplicationId>
  <xsi:subscriptionId>12345abc-12ab-12ab-12av-12345abcdefg</xsi:subscriptionId>
  <xsi:httpContact>
    <xsi:uri>uri</xsi:uri>
  </xsi:httpContact>
  <xsi:targetId>targetId</xsi:targetId>
  <xsi:eventData xsi1:type="xsi:CallCenterMonitoringEvent">
    <xsi:monitoringStatus>
      <xsi:averageHandlingTime>
        <xsi:value>1</xsi:value>
      </xsi:averageHandlingTime>
      <xsi:expectedWaitTime>
        <xsi:value>2</xsi:value>
      </xsi:expectedWaitTime>
      <xsi:averageSpeedOfAnswer>
        <xsi:value>3</xsi:value>
      </xsi:averageSpeedOfAnswer>
      <xsi:longestWaitTime>
        <xsi:value>4</xsi:value>
      </xsi:longestWaitTime>
      <xsi:numCallsInQueue>
        <xsi:value>5</xsi:value>
      </xsi:numCallsInQueue>
      <xsi:numAgentsAssigned>6</xsi:numAgentsAssigned>
      <xsi:numAgentsStaffed>7</xsi:numAgentsStaffed>
      <xsi:numStaffedAgentsIdle>11</xsi:numStaffedAgentsIdle>
      <xsi:numStaffedAgentsUnavailable>9</xsi:numStaffedAgentsUnavailable>
    </xsi:monitoringStatus>
  </xsi:eventData>
</xsi:Event>';

        // $response = $this->call($method, $uri, $parameters, $cookies, $files, $server, $content);
        $response = $this->call('POST', 'bs/CallCenterMonitoring', [], [], [], [], $xml);
        $this->assertContains('{"eventType":"CallCenterMonitoringEvent","eventID":"12345abc-12ab-12ab-12av-12345abcdefg","sequenceNumber":1,"subscriptionId":"12345abc-12ab-12ab-12av-12345abcdefg","targetId":"targetId","averageHandlingTime":1,"expectedWaitTime":2,"averageSpeedOfAnswer":3,"longestWaitTime":4,"numCallsInQueue":5,"numAgentsAssigned":6,"numAgentsStaffed":7,"numStaffedAgentsIdle":11,"numStaffedAgentsUnavailable":9}', $response->getContent());
    }

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
    //  * *@test
    //  */
    // public function it_can_load_rss()
    // {
    //     $this->assertInternalType('array', cspreporter::uri($this->file));
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
}
