<?php

namespace Jvleeuwen\Cspreporter\tests\Services;

use xml;
use Mockery as m;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\File;
use Jvleeuwen\Broadsoft\Facades\XmlFacade;
use Jvleeuwen\Broadsoft\BroadsoftServiceProvider;
use Jvleeuwen\Broadsoft\Facades\CallCenterMonitoringFacade;


class xmlTest extends TestCase
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


    /**
    * @test
    */
    public function it_can_detect_the_xml_type()
    {
        $file = File::get('docs/XSI_messages/CallCenterMonitoring/SubscriptionTerminatedEvent.xml');
        $xml = xml::parse($file);
        $this->assertSame(xml::type($xml), 'SubscriptionTerminatedEvent');
    }

    /**
    * @test
    */
    public function it_can_not_detect_the_xml_type()
    {
        $this->expectException(\ErrorException::class);
        xml::type("crap xml");
    }
}
