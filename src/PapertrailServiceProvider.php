<?php

namespace Silex\Provider;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\SyslogUdpHandler;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class PapertrailServiceProvider
 *
 * @package Silex\Provider
 */
class PapertrailServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['papertrailHandler'] = $app->share(
            function () use ($app) {

                if (!isset($app['host'])) {
                    throw new \Exception("Host undefined");
                }

                if (!isset($app['port'])) {
                    throw new \Exception("Port undefined");
                }

                if (!isset($app['prefix'])) {
                    $app['prefix'] = '';
                } else {
                    $app['prefix'] = sprintf(
                        '[%s]', $app['prefix']
                    );
                }

                // Set the format of message
                $output = $app['prefix']
                    . "%channel%.%level_name%: %message%";
                $formatter = new LineFormatter($output);

                // Setup the logger handler
                $papertrailHandler = new SyslogUdpHandler(
                    sprintf("%s.papertrailapp.com", $app['host']),
                    $app['port']
                );

                $papertrailHandler->setFormatter($formatter);

                return $papertrailHandler;
            }
        );
    }

    public function boot(Application $app)
    {
    }
}
