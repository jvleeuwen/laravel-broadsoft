<?php

namespace Jvleeuwen\Cspreporter\tests\CallCenterMonitoring;

use Mockery as m;
use advancedcall;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\BrowserKit\TestCase;
use Jvleeuwen\Broadsoft\BroadsoftServiceProvider;
use Jvleeuwen\Broadsoft\Facades\AdvancedCallFacade;

class AdvancedCallTest extends TestCase
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
            'advancedcall' => AdvancedCallFacade::class,
        ];
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
     * Setup the test environment.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->setUpMocks();
        $this->service_provider = new BroadsoftServiceProvider($this->application_mock);
        $this->loadLaravelMigrations(['--database' => 'testbench']);
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
        $concrete = $this->app->make('Jvleeuwen\Broadsoft\Facades\AdvancedCallFacade');
        $this->assertInstanceOf('Jvleeuwen\Broadsoft\Facades\AdvancedCallFacade', $concrete);
    }

    /**
     * @test
     */
    public function it_has_access_to_the_bindings()
    {
        $this->assertInternalType('array', $this->service_provider->provides());
        $this->assertSame('advancedcall', $this->service_provider->provides()[2]);
    }

    /**
    * @test
    */
    public function it_can_parse_getEventType()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallAnsweredEvent.xml');
        $xml = simplexml_load_string($req, null, 0, 'xsi', true);

        $event = advancedcall::getEventType($xml, $req);
        $expected = [
            'eventType' => 'CallAnsweredEvent',
            'eventID' => 'd977104c-8cca-437c-9b4e-30c03ba1f20a',
            'sequenceNumber' => 4,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callId' => 'ccallId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Originator',
            'state' => 'Active',
            'remotePartyName' => 'name',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => 'userId',
            'remotePartyUserDN' => 'userDN',
            'remotePartyCallType' => 'Group',
            'endpointAddressOfRecord' => 'addressOfRecord',
            'appearance' => 1,
            'startTime' => 1456130781929,
            'answerTime' => 1456130787729,
            'allowedRecordingControls' => 'none'
        ];
        $this->assertSame($event, $expected);
    }

    /**
    * @test
    */
    public function it_can_parse_CallAnsweredEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallAnsweredEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallAnsweredEvent($xml, $req);
        $expected = [
            'eventType' => 'CallAnsweredEvent',
            'eventID' => 'd977104c-8cca-437c-9b4e-30c03ba1f20a',
            'sequenceNumber' => 4,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callId' => 'ccallId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Originator',
            'state' => 'Active',
            'remotePartyName' => 'name',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => 'userId',
            'remotePartyUserDN' => 'userDN',
            'remotePartyCallType' => 'Group',
            'endpointAddressOfRecord' => 'addressOfRecord',
            'appearance' => 1,
            'startTime' => 1456130781929,
            'answerTime' => 1456130787729,
            'allowedRecordingControls' => 'none'
        ];
        $this->assertSame($event, $expected);
    }

    /**
    * @test
    */
    public function it_can_parse_CallBargedInEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallBargedInEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallBargedInEvent($xml, $req);
        $expected = [
            'eventType' => 'CallBargedInEvent',
            'eventID' => 'd74756d7-98f9-46c0-b832-c37b296f9b76',
            'sequenceNumber' => 40,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callId' => 'callId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Terminator',
            'state' => 'Active',
            'remotePartyName' => 'name',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => 'userId',
            'remotePartyUserDN' => 'userDN',
            'remotePartyCallType' => 'Group',
            'endpointAddressOfRecord' => 'addressOfRecord',
            'appearance' => 1,
            'startTime' => 1456929543572,
            'answerTime' => 1456929543572,
            'acdUserId' => 'acdUserId',
            'acdName' => 'acdName',
            'acdNumber' => 'acdNumber',
            'numCallsInQueue' => 0,
            'waitTime' => 0,
            'callingPartyInfoName' => 'name',
            'callingPartyInfoAddress' => 'address',
            'callingPartyInfoUserId' => 'userId',
            'callingPartyInfoUserDN' => 'userDN',
            'callingPartyInfoCallType' => 'Group',
            'allowedRecordingControls' => 'none',
            'callType' => 'Started'
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallCollectingEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallCollectingEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallCollectingEvent($xml, $req);
        $expected = [
            'eventType' => 'CallCollectingEvent',
            'eventID' => '3289a302-6c5f-4c11-b260-750b8bbd6c6c',
            'sequenceNumber' => 19,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callId' => 'callId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Originator',
            'state' => 'Active',
            'remotePartyName' => '',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => '',
            'remotePartyUserDN' => '',
            'remotePartyCallType' => 'Unknown',
            'endpointAddressOfRecord' => 'addressOfRecord',
            'appearance' => 1,
            'startTime' => 1456414590794,
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallDetachedEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallForwardedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallDetachedEvent($xml, $req);
        $expected = [
            'eventType' => 'CallDetachedEvent',
            'eventID' => '1d896790-79f2-427f-a6ac-24f332157fa5',
            'sequenceNumber' => 18,
            'subscriptionId' => 'subscriptionId',
            'callId' => 'callId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Originator',
            'state' => 'Alerting',
            'remotePartyAddress' => 'address',
            'remotePartyCallType' => 'Group',
            'endpointAddressOfRecord' => 'addressOfRecord',
            'appearance' => 1,
            'startTime' => 1456391076918,
            'answerTime' => 0,
            'detachedTime' => 0
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallForwardedEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallForwardedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallForwardedEvent($xml, $req);
        $expected = [
            'eventType' => 'CallForwardedEvent',
            'eventID' => '1d896790-79f2-427f-a6ac-24f332157fa5',
            'sequenceNumber' => 18,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'rtargetId',
            'callId' => 'callId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Originator',
            'state' => 'Alerting',
            'remotePartyName' => 'name',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => 'userId',
            'remotePartyUserDN' => 'userDN',
            'remotePartyCallType' => 'Group',
            'endpointAddressOfRecord' => 'addressOfRecord',
            'appearance' => 1,
            'startTime' => 1456391076918
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallHeldEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallHeldEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallHeldEvent($xml, $req);
        $expected = [
            'eventType' => 'CallHeldEvent',
            'eventID' => 'd43331b6-4b43-429d-abd3-3105f445b03f',
            'sequenceNumber' => 2,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callId' => 'callId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Terminator',
            'state' => 'Held',
            'remotePartyName' => 'name',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => '',
            'remotePartyUserDN' => '',
            'remotePartyCallType' => 'Network',
            'endpointAddressOfRecord' => 'addressOfRecord',
            'appearance' => 1,
            'startTime' => 1456225452558,
            'answerTime' => 1456225453732,
            'heldTime' => 1456226148778,
            'acdUserId' => 'acdUserId',
            'acdName' => 'acdName',
            'acdNumber' => 'acdNumber',
            'numCallsInQueue' => 0,
            'waitTime' => 39,
            'callingPartyInfoName' => 'name',
            'callingPartyInfoAddress' => 'address',
            'callingPartyInfoCallType' => 'Network',
            'allowedRecordingControls' => 'none',
            'recordingState' => 'Started'
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallOriginatedEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallOriginatedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallOriginatedEvent($xml, $req);
        $expected = [
            'eventType' => 'CallOriginatedEvent',
            'eventID' => 'ada3cc1f-99d2-4c92-8819-4491a4841996',
            'sequenceNumber' => 3,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callId' => 'callId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Originator',
            'state' => 'Alerting',
            'remotePartyName' => 'name',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => 'userId',
            'remotePartyUserDN' => 'userDN',
            'remotePartyCallType' => 'Group',
            'endpointAddressOfRecord' => 'addressOfRecord',
            'appearance' => 1,
            'startTime' => 1456130781929
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallOriginatingEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallOriginatingEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallOriginatingEvent($xml, $req);
        $expected = [
            'eventType' => 'CallOriginatingEvent',
            'eventID' => '7320decd-5e53-4143-8d1c-9b5808a253cf',
            'sequenceNumber' => 10,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callId' => 'callId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Click-to-Dial',
            'state' => 'Alerting',
            'remotePartyName' => '',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => '',
            'remotePartyUserDN' => '',
            'remotePartyCallType' => 'Unknown',
            'endpointAddressOfRecord' => '',
            'appearance' => 1,
            'startTime' => 1456327904666
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallPickedUpEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallPickedUpEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallPickedUpEvent($xml, $req);
        $expected = [
            'eventType' => 'CallPickedUpEvent',
            'eventID' => '1fc32a75-2f28-4257-b13c-39ca3acec6f4',
            'sequenceNumber' => 53,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callId' => 'callId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Originator',
            'state' => 'Active',
            'remotePartyName' => 'name',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => 'userId',
            'remotePartyUserDN' => 'userDN',
            'remotePartyCallType' => 'Group',
            'endpointAddressOfRecord' => 'addressOfRecord',
            'appearance' => 2,
            'startTime' => 1456388974755,
            'answerTime' => 1456388986555,
            'allowedRecordingControls' => 'none'
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallReceivedEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallReceivedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallReceivedEvent($xml, $req);
        $expected = [
            'eventType' => 'CallReceivedEvent',
            'eventID' => 'cafc759f-4268-4db8-a768-4bb83ef59752',
            'sequenceNumber' => 23,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callId' => 'callId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Terminator',
            'state' => 'Alerting',
            'remotePartyName' => 'name',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => '',
            'remotePartyUserDN' => '',
            'remotePartyCallType' => 'Network',
            'redirections' => [[
                    'name' => 'RoutIT',
                    'address' => 'address',
                    'userId' => 'userId',
                    'callType' => 'Group',
                    'reason' => 'call-center'
            ],
              [
                    'name' => 'name',
                    'address' => 'address',
                    'userId' => 'userId',
                    'callType' => 'Group',
                    'reason' => 'deflection'
              ],
                [
                    'name' => 'name',
                    'address' => 'address',
                    'userId' => 'userId',
                    'callType' => 'Group',
                    'reason' => 'deflection'
                ]
                ],
            'startTime' => 1456998245163,
            'acdUserId' => 'acdUserId',
            'acdName' => 'acdName',
            'acdNumber' => 'acdNumber',
            'numCallsInQueue' => 0,
            'waitTime' => 0,
            'callingPartyInfoName' => 'name',
            'callingPartyInfoAddress' => 'address',
            'callingPartyInfoCallType' => 'Network'
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallRecordingStartedEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallRecordingStartedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallRecordingStartedEvent($xml, $req);
        $expected = [
            'eventType' => 'CallRecordingStartedEvent',
            'eventID' => 'f920155e-1c5c-4427-99f9-3dd8263d67e9',
            'sequenceNumber' => 14,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callId' => 'callId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Terminator',
            'state' => 'Active',
            'remotePartyName' => 'name',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => '',
            'remotePartyUserDN' => '',
            'remotePartyCallType' => 'Network',
            'startTime' => 1456826772363,
            'answerTime' => 1456826785390,
            'acdUserId' => 'acdUserId',
            'acdName' => 'acdName',
            'acdNumber' => 'acdNumber',
            'numCallsInQueue' => 0,
            'waitTime' => 0,
            'callingPartyInfoName' => 'name',
            'callingPartyInfoAddress' => 'address',
            'callingPartyInfoCallType' => 'Network',
            'allowedRecordingControls' => 'none',
            'recordingState' => 'Started'
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallRecordingStoppedEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallRecordingStoppedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallRecordingStoppedEvent($xml, $req);
        $expected = [
            'eventType' => 'CallRecordingStoppedEvent',
            'eventID' => 'b073e615-de32-4909-85f6-10b40038f613',
            'sequenceNumber' => 26,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callId' => 'callId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Terminator',
            'state' => 'Alerting',
            'remotePartyName' => 'name',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => 'userId',
            'remotePartyUserDN' => 'userDN',
            'remotePartyCallType' => 'Group',
            'appearance' => 2,
            'startTime' => 1456393559310,
            'answerTime' => 0,
            'acdUserId' => '',
            'acdName' => '',
            'acdNumber' => '',
            'numCallsInQueue' => 0,
            'waitTime' => 0,
            'allowedRecordingControls' => 'none',
            'recordingState' => 'Failed',
            'reason' => 'Failure'
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallRedirectedEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallRedirectedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallRedirectedEvent($xml, $req);
        $expected = [
            'eventType' => 'CallRedirectedEvent',
            'eventID' => '77250224-881a-4092-91a4-9b6b13829d1a',
            'sequenceNumber' => 11,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'calls' => [
                [
                    'callId' => 'callId',
                    'extTrackingId' => 'extTrackingId',
                    'personality' => 'Terminator',
                    'state' => 'Detached',
                    'remotePartyName' => 'name',
                    'remotePartyAddress' => 'address',
                    'remotePartyUserId' => 'userId',
                    'remotePartyUserDN' => 'userDN',
                    'remotePartyCallType' => 'Group',
                    'redirectAddress' => 'address',
                    'redirectReason' => 'transfer',
                    'redirectTime' => 1456231463179,
                    'startTime' => 1456231434746,
                    'answerTime' => 1456231438022,
                    'totalHeldTime' => 21163,
                    'detachedTime' => 1456231463179,
                    'allowedRecordingControls' => 'none',
                ],
                [
                    'callId' => 'callId',
                    'extTrackingId' => 'extTrackingId',
                    'personality' => 'Originator',
                    'state' => 'Detached',
                    'remotePartyName' => 'name',
                    'remotePartyAddress' => 'address',
                    'remotePartyUserId' => 'userId',
                    'remotePartyUserDN' => 'userDN',
                    'remotePartyCallType' => 'Group',
                    'redirectAddress' => 'address',
                    'redirectReason' => 'transfer',
                    'redirectTime' => 1456231463179,
                    'startTime' => 1456231446571,
                    'answerTime' => 1456231453346,
                    'totalHeldTime' => 0,
                    'detachedTime' => 1456231463179,
                    'allowedRecordingControls' => 'none',
                ]
            ]
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallReleasedEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallReleasedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallReleasedEvent($xml, $req);
        $expected = [
            'eventType' => 'CallReleasedEvent',
            'eventID' => 'f611e737-753a-47db-99a3-7220f6e24ff7',
            'sequenceNumber' => 7,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callId' => 'callId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Terminator',
            'state' => 'Released',
            'releasingParty' => 'localRelease',
            'remotePartyName' => 'name',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => 'userId',
            'remotePartyUserDN' => 'userDN',
            'remotePartycallType' => 'Group',
            'startTime' => 1456130781929,
            'answerTime' => 1456130787729,
            'releaseTime' => 1456130790423,
            'allowedRecordingControls' => 'none'
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallReleasingEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallReleasingEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallReleasingEvent($xml, $req);
        $expected = [
            'eventType' => 'CallReleasingEvent',
            'eventID' => 'd0cd10b4-a109-4348-a8d0-f6bbc59fb01e',
            'sequenceNumber' => 17,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callId' => 'callId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Originator',
            'state' => 'Active',
            'releaseCauseInternalReleaseCause' => 'internalReleaseCause',
            'releaseCauseCdrTerminationCause' => 111,
            'releasingParty' => '',
            'remotePartyName' => '',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => '',
            'remotePartyUserDN' => '',
            'remotePartycallType' => 'Network',
            'addressOfRecord' => 'addressOfRecord',
            'appearance' => 1,
            'startTime' => 1456388669366
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallRetrievedEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallRetrievedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallRetrievedEvent($xml, $req);
        $expected = [
            'eventType' => 'CallRetrievedEvent',
            'eventID' => 'f007b52a-bec4-4ecb-9f65-28b0c5dfd53d',
            'sequenceNumber' => 3,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callId' => 'callId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Terminator',
            'state' => 'Active',
            'remotePartyName' => 'name',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => '',
            'remotePartyUserDN' => '',
            'remotePartycallType' => 'Network',
            'addressOfRecord' => 'addressOfRecord',
            'appearance' => 1,
            'startTime' => 1456225452558,
            'answerTime' => 1456225453732,
            'totalHeldTime' => 48994,
            'acdUserId' => 'acdUserId',
            'acdName' => 'acdName',
            'acdNumber' => 'acdNumber',
            'numCallsInQueue' => 0,
            'waitTime' => 39,
            'callingPartyInfoName' => 'name',
            'callingPartyInfoAddress' => 'address',
            'callingPartyInfoCallType' => 'Network',
            'allowedRecordingControls' => 'none',
            'recordingState' => 'Started'
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallSubscriptionEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallSubscriptionEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallSubscriptionEvent($xml, $req);
        $expected = [
            'eventType' => 'CallSubscriptionEvent',
            'eventID' => '9e35683b-40dd-49c8-a794-003c70b655c3',
            'sequenceNumber' => 1,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'hookStatus' => 'On-Hook'
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallTransferredEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallTransferredEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallTransferredEvent($xml, $req);
        $expected = [
            'eventType' => 'CallTransferredEvent',
            'eventID' => 'a8d2d7e8-e65c-4f34-b23f-fc4b4c6e1249',
            'sequenceNumber' => 101,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callId' => 'callId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Originator',
            'state' => 'Active',
            'remotePartyName' => 'name',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => 'userId',
            'remotePartyUserDN' => 'userDN',
            'remotePartycallType' => 'Group',
            'address' => 'address',
            'appearance' => 1,
            'startTime' => 1456231434746,
            'answerTime' => 1456231438022
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_CallUpdatedEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallUpdatedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::CallUpdatedEvent($xml, $req);
        $expected = [
            'eventType' => 'CallUpdatedEvent',
            'eventID' => '20d2bd3e-0d10-4f0e-b6c7-95dbfd1d10ee',
            'sequenceNumber' => 3,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'callId' => 'callId',
            'extTrackingId' => 'extTrackingId',
            'personality' => 'Terminator',
            'state' => 'Alerting',
            'remotePartyName' => 'name',
            'remotePartyAddress' => 'address',
            'remotePartyUserId' => 'userId',
            'remotePartyUserDN' => 'userDN',
            'remotePartycallType' => 'Group',
            'addressOfRecord' => 'addressOfRecord',
            'appearance' => 1,
            'startTime' => 1456130781929
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_ConferenceHeldEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/ConferenceHeldEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::ConferenceHeldEvent($xml, $req);
        $expected = [
            'eventType' => 'ConferenceHeldEvent',
            'eventID' => '0f003e06-77cd-474b-92e5-01c054bfde29',
            'sequenceNumber' => 45,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'state' => 'Held',
            'addressOfRecord' => 'addressOfRecord',
            'appearance' => 2
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_ConferenceReleasedEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/ConferenceReleasedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::ConferenceReleasedEvent($xml, $req);
        $expected = [
            'eventType' => 'ConferenceReleasedEvent',
            'eventID' => '46aeeb1e-d5c0-4c4d-9b07-38bf77139a71',
            'sequenceNumber' => 60,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'state' => 'Released'
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_ConferenceRetrievedEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/ConferenceRetrievedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::ConferenceRetrievedEvent($xml, $req);
        $expected = [
            'eventType' => 'ConferenceRetrievedEvent',
            'eventID' => 'ec67bdcd-ceee-45c1-b4f7-897d70e65397',
            'sequenceNumber' => 55,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'state' => 'Active',
            'addressOfRecord' => 'addressOfRecord',
            'appearance' => 2,
            'conferenceParticipantList' => [
                [
                    'callId' => 'callId'
                ],
                [
                    'callId' => 'callId'
                ]
            ]
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_ConferenceStartedEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/ConferenceStartedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::ConferenceStartedEvent($xml, $req);
        $expected = [
            'eventType' => 'ConferenceStartedEvent',
            'eventID' => '5ee0bbde-2ebe-4730-a96a-727aa4768b27',
            'sequenceNumber' => 56,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'state' => 'Active',
            'addressOfRecord' => 'addressOfRecord',
            'appearance' => 1,
            'conferenceType' => 'Barge-In',
            'conferenceParticipantList' => [
                [
                    'callId' => 'callId'
                ],
                [
                    'callId' => 'callId'
                ]
            ]
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_ConferenceUpdatedEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/ConferenceUpdatedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::ConferenceUpdatedEvent($xml, $req);
        $expected = [
            'eventType' => 'ConferenceUpdatedEvent',
            'eventID' => '4d039bbe-16c4-41c4-bf3a-4e6482c689aa',
            'sequenceNumber' => 54,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'state' => 'Held',
            'addressOfRecord' => 'addressOfRecord',
            'appearance' => 2,
            'conferenceParticipantList' => [
                [
                    'callId' => 'callId'
                ],
                [
                    'callId' => 'callId'
                ]
            ]
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_HookStatusEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/HookStatusEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::HookStatusEvent($xml, $req);
        $expected = [
            'eventType' => 'HookStatusEvent',
            'eventID' => 'bed6e3fe-25d4-48c0-8e0c-69837628e3f3',
            'sequenceNumber' => 4,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'state' => 'Off-Hook'
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_parse_SubscriptionTerminatedEvent()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/SubscriptionTerminatedEvent.xml');

        $xml = simplexml_load_string($req, null, 0, 'xsi', true);
        $event = advancedcall::SubscriptionTerminatedEvent($xml, $req);
        $expected = [
            'eventType' => 'SubscriptionTerminatedEvent',
            'eventID' => '91284d4a-3c3e-4380-a1cc-bb63f2f7c7d2',
            'sequenceNumber' => 46,
            'subscriptionId' => 'subscriptionId',
            'targetId' => 'targetId',
            'httpContactUri' => 'uri'
        ];
        $this->assertSame($event, $expected);

    }

    /**
    * @test
    */
    public function it_can_not_detect_the_eventType_in_getEventType()
    {
        $req = File::get('docs/XSI_messages/AdvancedCall/CallAnsweredEvent.xml');
        $xml = simplexml_load_string($req, null, 0, 'xsi', true);

        $event = advancedcall::getEventType("bogusxml", $req);
        $expected = 'Can not detect the eventType';
        $this->assertSame($event, $expected);
    }

    /**
    * @test
    */
    public function it_can_not_get_the_getEventType()
    {
        $req = '<?xml version="1.0" encoding="UTF-8"?>
                <xsi:Event xmlns:xsi="http://schema.broadsoft.com/xsi" xmlns:xsi1="http://www.w3.org/2001/XMLSchema-instance" xsi1:type="xsi:SubscriptionEvent">
                <xsi:eventID>91284d4a-3c3e-4380-a1cc-bb63f2f7c7d2</xsi:eventID>
                <xsi:sequenceNumber>46</xsi:sequenceNumber>
                <xsi:userId>userId</xsi:userId>
                <xsi:externalApplicationId>externalApplicationId</xsi:externalApplicationId>
                <xsi:subscriptionId>subscriptionId</xsi:subscriptionId>
                <xsi:httpContact>
                    <xsi:uri>uri</xsi:uri>
                </xsi:httpContact>
                <xsi:targetId>targetId</xsi:targetId>
                <xsi:eventData xsi1:type="xsi:UWillNeverFindMe"/>
                </xsi:Event>';
        $xml = simplexml_load_string($req, null, 0, 'xsi', true);

        $event = advancedcall::getEventType($xml, $req);
        $expected = 'Function does not exist';
        $this->assertSame($event, $expected);
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallAnsweredEvent()
    {
        $event = advancedcall::CallAnsweredEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallAnsweredEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallBargedInEvent()
    {
        $event = advancedcall::CallBargedInEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallBargedInEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallCollectingEvent()
    {
        $event = advancedcall::CallCollectingEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallCollectingEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallDetachedEvent()
    {
        $event = advancedcall::CallDetachedEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallDetachedEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallForwardedEvent()
    {
        $event = advancedcall::CallForwardedEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallForwardedEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallHeldEvent()
    {
        $event = advancedcall::CallHeldEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallHeldEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallOriginatedEvent()
    {
        $event = advancedcall::CallOriginatedEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallOriginatedEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallOriginatingEvent()
    {
        $event = advancedcall::CallOriginatingEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallOriginatingEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallPickedUpEvent()
    {
        $event = advancedcall::CallPickedUpEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallPickedUpEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallReceivedEvent()
    {
        $event = advancedcall::CallReceivedEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallReceivedEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallRecordingStartedEvent()
    {
        $event = advancedcall::CallRecordingStartedEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallRecordingStartedEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallRecordingStoppedEvent()
    {
        $event = advancedcall::CallRecordingStoppedEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallRecordingStoppedEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallRedirectedEvent()
    {
        $event = advancedcall::CallRedirectedEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallRedirectedEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallReleasedEvent()
    {
        $event = advancedcall::CallReleasedEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallReleasedEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallReleasingEventt()
    {
        $event = advancedcall::CallReleasingEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallReleasingEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallRetrievedEvent()
    {
        $event = advancedcall::CallRetrievedEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallRetrievedEvent');
    }

    /**
    * @test
    */
    public function it_can_run_a_full_cycle()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <xsi:Event xmlns:xsi="http://schema.broadsoft.com/xsi" xmlns:xsi1="http://www.w3.org/2001/XMLSchema-instance" xsi1:type="xsi:SubscriptionEvent">
        <xsi:eventID>d977104c-8cca-437c-9b4e-30c03ba1f20a</xsi:eventID>
        <xsi:sequenceNumber>4</xsi:sequenceNumber>
        <xsi:userId>userId</xsi:userId>
        <xsi:externalApplicationId>externalApplicationId</xsi:externalApplicationId>
        <xsi:subscriptionId>subscriptionId</xsi:subscriptionId>
        <xsi:httpContact>
            <xsi:uri>uri</xsi:uri>
        </xsi:httpContact>
        <xsi:targetId>targetId</xsi:targetId>
        <xsi:eventData xsi1:type="xsi:CallAnsweredEvent">
            <xsi:call>
            <xsi:callId>ccallId</xsi:callId>
            <xsi:extTrackingId>extTrackingId</xsi:extTrackingId>
            <xsi:personality>Originator</xsi:personality>
            <xsi:state>Active</xsi:state>
            <xsi:remoteParty>
                <xsi:name>name</xsi:name>
                <xsi:address>address</xsi:address>
                <xsi:userId>userId</xsi:userId>
                <xsi:userDN countryCode="31">userDN</xsi:userDN>
                <xsi:callType>Group</xsi:callType>
            </xsi:remoteParty>
            <xsi:endpoint xsi1:type="xsi:AccessEndpoint">
                <xsi:addressOfRecord>addressOfRecord</xsi:addressOfRecord>
            </xsi:endpoint>
            <xsi:appearance>1</xsi:appearance>
            <xsi:startTime>1456130781929</xsi:startTime>
            <xsi:answerTime>1456130787729</xsi:answerTime>
            <xsi:allowedRecordingControls>none</xsi:allowedRecordingControls>
            </xsi:call>
        </xsi:eventData>
        </xsi:Event>';

        $response = $this->call('POST', 'bs/AdvancedCall', [], [], [], [], $xml);
        $this->assertSame(200, $response->status());

    }

    /**
    * @test
    */
    public function it_can_not_parse_CallSubscriptionEvent()
    {
        $event = advancedcall::CallSubscriptionEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallSubscriptionEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallTransferredEvent()
    {
        $event = advancedcall::CallTransferredEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallTransferredEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_CallUpdatedEvent()
    {
        $event = advancedcall::CallUpdatedEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: CallUpdatedEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_ConferenceHeldEvent()
    {
        $event = advancedcall::ConferenceHeldEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: ConferenceHeldEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_ConferenceReleasedEvent()
    {
        $event = advancedcall::ConferenceReleasedEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: ConferenceReleasedEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_ConferenceRetrievedEvent()
    {
        $event = advancedcall::ConferenceRetrievedEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: ConferenceRetrievedEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_ConferenceStartedEvent()
    {
        $event = advancedcall::ConferenceStartedEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: ConferenceStartedEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_ConferenceUpdatedEvent()
    {
        $event = advancedcall::ConferenceUpdatedEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: ConferenceUpdatedEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_HookStatusEvent()
    {
        $event = advancedcall::HookStatusEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: HookStatusEvent');
    }

    /**
    * @test
    */
    public function it_can_not_parse_SubscriptionTerminatedEvent()
    {
        $event = advancedcall::SubscriptionTerminatedEvent("bogus xml");
        $this->assertSame($event, 'can not parse event: SubscriptionTerminatedEvent');
    }
}
