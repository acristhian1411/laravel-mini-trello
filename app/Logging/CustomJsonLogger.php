<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\JsonFormatter;

class CustomJsonLogger
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        // Ruta del archivo
        $path = $config['path'] ?? storage_path('logs/custom-json.log');

        // Crear logger
        $logger = new Logger('custom');

        // Handler + formatter JSON
        $handler = new StreamHandler($path, Logger::DEBUG);
        $handler->setFormatter(new JsonFormatter());

        $logger->pushHandler($handler);

        // Agregar processor personalizado
        $logger->pushProcessor(function ($record) {
            if (auth()->check()) {
                $record['extra']['user_id'] = auth()->id();
                $record['extra']['email'] = auth()->user()->email;
            }

            $record['extra']['ip'] = request()->ip();
            $record['extra']['user_agent'] = request()->userAgent();

            $route = request()->route();
            if ($route) {
                $record['extra']['route_name'] = $route->getName();
                $record['extra']['uri'] = $route->uri();
                $record['extra']['action'] = $route->getActionName();
            }

            return $record;
        });

        return $logger;
    }
}
