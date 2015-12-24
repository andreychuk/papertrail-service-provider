<?php

namespace Silex\Provider;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\SyslogUdpHandler;
use Silex\Application;
use Silex\ServiceProviderInterface;

class PapertrailServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['papertrailHandler'] = $app->share(
            function () use ($app) {

                if (!isset($app['papertrail.host'])) {
                    throw new \Exception("papertrail.host undefined");
                }

                if (!isset($app['papertrail.port'])) {
                    throw new \Exception("papertrail.port undefined");
                }

                if (!isset($app['papertrail.prefix'])) {
                    $app['papertrail.prefix'] = '';
                } else {
                    $app['papertrail.prefix'] = sprintf(
                        '[%s]', $app['papertrail.prefix']
                    );
                }

                // Set the format
                $output = $app['papertrail.prefix']
                    . "%channel%.%level_name%: %message%";
                $formatter = new LineFormatter($output);

                // Setup the logger
                $papertrailHandler = new SyslogUdpHandler(
                    sprintf("%s.papertrailapp.com", $app['papertrail.host']),
                    $app['papertrail.port']
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
