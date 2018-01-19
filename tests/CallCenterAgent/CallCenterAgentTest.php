<?php

namespace Jvleeuwen\Cspreporter\tests\CallCenterMonitoring;

use Mockery as m;
use callcenteragent;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\BrowserKit\TestCase;
use Jvleeuwen\Broadsoft\BroadsoftServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Jvleeuwen\Broadsoft\Facades\CallCenterAgentFacade;

class CallCenterAgentTest extends TestCase
{
    use RefreshDatabase;

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
            'callcenteragent' => CallCenterAgentFacade::class,
        ];
    }

    // /**
    //  * Define environment setup.
    //  *
    //  * @param  \Illuminate\Foundation\Application  $app
    //  * @return void
    //  */
    // protected function getEnvironmentSetUp($app)
    // {
    //     // Setup default database to use sqlite :memory:
    //     $app['config']->set('database.default', 'testbench');
    //     $app['config']->set('database.connections.testbench', [
    //         'driver'   => 'sqlite',
    //         'database' => ':memory:',
    //         'prefix'   => '',
    //     ]);
    // }

    /**
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->setUpMocks();
        $this->loadLaravelMigrations(['--database' => 'testbench']);
        $this->service_provider = new BroadsoftServiceProvider($this->application_mock);
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
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    /**
     * @test
     */
    public function it_registers_the_service()
    {
        $concrete = $this->app->make('Jvleeuwen\Broadsoft\Facades\CallCenterAgentFacade');
        $this->assertInstanceOf('Jvleeuwen\Broadsoft\Facades\CallCenterAgentFacade', $concrete);
    }

    /**
     * @test
     */
    public function it_has_access_to_the_bindings()
    {
        $this->assertInternalType('array', $this->service_provider->provides());
        $this->assertSame('callcenteragent', $this->service_provider->provides()[1]);
    }

    /**
    * @test
    */
    public function it_can_get_the_eventType()
    {
        $req = File::get('docs/XSI_messages/CallCenterAgent/ACDAgentJoinUpdateEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = callcenteragent::getEventType($xml, $req);
        $this->assertSame($event['eventType'], 'ACDAgentJoinUpdateEvent');
    }

    /**
    * @test
    */
    public function it_can_not_get_the_eventType()
    {
        $req = File::get('tests/broken.xml');
        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = callcenteragent::GetEventType($xml, $req);
        $this->assertSame($event, 'class CallCenterMonitoringEventBLAAT not found');
    }

    /**
    * @test
    */
    public function it_can_parse_ACDAgentJoinUpdateEvent()
    {
        $req = File::get('docs/XSI_messages/CallCenterAgent/ACDAgentJoinUpdateEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = callcenteragent::ACDAgentJoinUpdateEvent($xml, $req);
        $expected = [
            'eventType' => 'ACDAgentJoinUpdateEvent',
            'eventID' => '0e4857ea-9e94-41d7-838a-dfb40576b05f',
            'sequenceNumber' => 2,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'userId' => 'userId',
            'acdUserId' => 'acdUserId',
            'skillLevel' => 1
        ];
        $this->assertSame($event, $expected);
    }

    /**
    * @test
    */
    public function it_can_not_parse_ACDAgentJoinUpdateEvent()
    {
        $req = '<?xml version="1.0" encoding="UTF-8"?>
                <xsi:Event xmlns:xsi="http://schema.broadsoft.com/xsi" xmlns:xsi1="http://www.w3.org/2001/XMLSchema-instance" xsi1:type="xsi:SubscriptionEvent">
                <xsi:eventData xsi1:type="xsi:ACDAgentJoinUpdateEvent">
                </xsi:eventData>
                </xsi:Event>
                ';

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = callcenteragent::ACDAgentJoinUpdateEvent($xml, $req);
        $this->assertSame($event, 'can not parse event: ACDAgentJoinUpdateEvent');
    }

    /**
    * @test
    */
    public function it_can_parse_AgentStateEvent()
    {
        $req = File::get('docs/XSI_messages/CallCenterAgent/AgentStateEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        // $event = callcentermonitoring::CallCenterMonitoringEvent($xml, $req);
        $event = callcenteragent::AgentStateEvent($xml, $req);
        $expected = [
            'eventType' => 'AgentStateEvent',
            'eventID' => '01c3bb60-cc07-4043-9c9d-9981ac480e3a',
            'sequenceNumber' => 27,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'state' => 'Unavailable',
            'stateTimestamp' => 1505395898803,
            'unavailableCode' => 1007,
            'signInTimestamp' => 1505395341023,
            'totalAvailableTime' => 277,
            'averageWrapUpTime' => 0
        ];
        $this->assertSame($event, $expected);
    }

    /**
    * @test
    */
    public function it_can_not_parse_AgentStateEvent()
    {
        $req = '<?xml version="1.0" encoding="UTF-8"?>
                <xsi:Event xmlns:xsi="http://schema.broadsoft.com/xsi" xmlns:xsi1="http://www.w3.org/2001/XMLSchema-instance" xsi1:type="xsi:SubscriptionEvent">
                <xsi:eventData xsi1:type="xsi:AgentStateEvent">
                </xsi:eventData>
                </xsi:Event>
                ';

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = callcenteragent::AgentStateEvent($xml, $req);
        $this->assertSame($event, 'can not parse event: AgentStateEvent');
    }

    /**
    * @test
    */
    public function it_can_parse_AgentSubscriptionEvent()
    {
        $req = File::get('docs/XSI_messages/CallCenterAgent/AgentSubscriptionEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = callcenteragent::AgentSubscriptionEvent($xml, $req);
        $expected = [
            'eventType' => 'AgentSubscriptionEvent',
            'eventID' => '513ca841-4b0d-4a27-8c7f-89216a337edc',
            'sequenceNumber' => 1,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callcenters' => [
                    [
                        'acdUserId' => 'acdUserId1'
                    ]
            ],
            'stateInfo' => 'Sign-Out',
            'stateIstateTimestampnfo' => 1498714691974
        ];
        $this->assertSame($event, $expected);
    }

    /**
    * @test
    */
    public function it_can_not_parse_AgentSubscriptionEvent()
    {
        $req = '<?xml version="1.0" encoding="UTF-8"?>
                <xsi:Event xmlns:xsi="http://schema.broadsoft.com/xsi" xmlns:xsi1="http://www.w3.org/2001/XMLSchema-instance" xsi1:type="xsi:SubscriptionEvent">
                <xsi:eventData xsi1:type="xsi:AgentSubscriptionEvent">
                </xsi:eventData>
                </xsi:Event>';

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = callcenteragent::AgentSubscriptionEvent($xml, $req);
        $this->assertSame($event, 'can not parse event: AgentSubscriptionEvent');
    }

    /**
    * @test
    */
    public function it_can_parse_SubscriptionTerminatedEvent()
    {
        $req = File::get('docs/XSI_messages/CallCenterAgent/SubscriptionTerminatedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        // $event = callcentermonitoring::CallCenterMonitoringEvent($xml, $req);
        $event = callcenteragent::SubscriptionTerminatedEvent($xml, $req);
        $expected = [
            'eventType' => 'SubscriptionTerminatedEvent',
            'eventID' => '46fa8141-a828-434f-aea3-dc4785d36e9c',
            'sequenceNumber' => 18,
            'userId' => 'userId',
            'subscriptionId' => 'subscriptionId',
            'externalApplicationId' => 'appId',
            'httpContact' => 'uri'
        ];
        $this->assertSame($event, $expected);
    }

    // /**
    // * @test
    // */
    // public function it_can_not_parse_SubscriptionTerminatedEvent()
    // {
    //     $this->markTestIncomplete('This test has not been implemented yet.');
    // }

    /**
    * @test
    */
    public function it_can_not_parse_subscription_terminated_event()
    {
        $event = callcenteragent::SubscriptionTerminatedEvent('bogus xml');
        $this->assertSame($event, 'can not parse event: SubscriptionTerminatedEvent');
    }

    /**
     * @test
     */
    public function it_can_parse_incomming_requests()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
                <xsi:Event xmlns:xsi="http://schema.broadsoft.com/xsi" xmlns:xsi1="http://www.w3.org/2001/XMLSchema-instance" xsi1:type="xsi:SubscriptionEvent">
                <xsi:eventID>01c3bb60-cc07-4043-9c9d-9981ac480e3a</xsi:eventID>
                <xsi:sequenceNumber>27</xsi:sequenceNumber>
                <xsi:userId>userId</xsi:userId>
                <xsi:externalApplicationId>CallCenterAgent</xsi:externalApplicationId>
                <xsi:subscriptionId>subscriptionId</xsi:subscriptionId>
                <xsi:httpContact>
                    <xsi:uri>uri</xsi:uri>
                </xsi:httpContact>
                <xsi:targetId>targetId</xsi:targetId>
                <xsi:eventData xsi1:type="xsi:AgentStateEvent">
                    <xsi:agentStateInfo>
                    <xsi:state>Unavailable</xsi:state>
                    <xsi:stateTimestamp>
                        <xsi:value>1505395898803</xsi:value>
                    </xsi:stateTimestamp>
                    <xsi:unavailableCode>1007</xsi:unavailableCode>
                    <xsi:signInTimestamp>1505395341023</xsi:signInTimestamp>
                    <xsi:totalAvailableTime>277</xsi:totalAvailableTime>
                    <xsi:averageWrapUpTime>
                        <xsi:value>0</xsi:value>
                    </xsi:averageWrapUpTime>
                    </xsi:agentStateInfo>
                </xsi:eventData>
                </xsi:Event>';

        $response = $this->call('POST', 'bs/CallCenterAgent', [], [], [], [], $xml);
        $this->assertSame(200, $response->status());
    }
}
