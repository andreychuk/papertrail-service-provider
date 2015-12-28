<?php
namespace Silex\Tests\Provider;
use Silex\Application;
use Silex\Provider\PapertrailServiceProvider;
/**
 * Class FanoutServiceProviderTest
 *
 * @package Silex\Tests\Provider
 */
class FanoutServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    /*
     * testRegister
     */
    public function testRegister()
    {
        $host = 'host';
        $port = '123';
        $prefix = '-';
        $app = new Application();
        $app->register(new PapertrailServiceProvider(), array(
            "host" => $host,
            "port" => $port,
            "prefix" => $prefix
        ));
        $tp = $app['papertrailHandler'];

        $this->assertInstanceOf("\\Monolog\\Handler\\SyslogUdpHandler", $tp);
    }
}