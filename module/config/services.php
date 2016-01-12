<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Define logger defaults levels
 */

// avisota log default level
$container['avisota.logger.default.level'] = function ($container) {
    return $container['logger.default.level'];
};

// queue log default level
$container['avisota.logger.default.level.queue'] = function ($container) {
    return $container['logger.default.level'];
};

// transport log default level
$container['avisota.logger.default.level.transport'] = function ($container) {
    return $container['avisota.logger.default.level'];
};

/**
 * Definer logger default handlers
 */

// avisota log default handlers
$container['avisota.logger.default.handlers'] = new ArrayObject(
    array('avisota.logger.handler.general')
);

// queue log default handlers
$container['avisota.logger.default.handlers.queue'] = new ArrayObject(
    array('avisota.logger.handler.queue', 'avisota.logger.handler.general')
);

// transport log default handlers
$container['avisota.logger.default.handlers.transport'] = new ArrayObject(
    array('avisota.logger.handler.transport', 'avisota.logger.handler.general')
);

/**
 * Define logger handlers
 */

// avisota log handler
$container['avisota.logger.handler.general'] = $container->share(
    function ($container) {
        $factory = $container['logger.factory.handler.rotatingFile'];

        return $factory('avisota.log', $container['avisota.logger.default.level']);
    }
);

// queue log handler
$container['avisota.logger.handler.queue'] = $container->share(
    function ($container) {
        $factory = $container['logger.factory.handler.stream'];

        return $factory('avisota.queue.log', $container['avisota.logger.default.level.queue']);
    }
);

// transport log handler
$container['avisota.logger.handler.transport'] = $container->share(
    function ($container) {
        $factory = $container['logger.factory.handler.rotatingFile'];

        return $factory('avisota.transport.log', $container['avisota.logger.default.level.transport']);
    }
);

/**
 * Define loggers
 */

// avisota log
$container['avisota.logger'] = function ($container) {
    $factory = $container['logger.factory'];
    $logger  = $factory('avisota', $container['avisota.logger.default.handlers']);

    return $logger;
};

// queue log
$container['avisota.logger.queue'] = function ($container) {
    $factory = $container['logger.factory'];
    $logger  = $factory('avisota.queue', $container['avisota.logger.default.handlers.queue']);

    return $logger;
};

// transport log
$container['avisota.logger.transport'] = function ($container) {
    $factory = $container['logger.factory'];
    $logger  = $factory('avisota.transport', $container['avisota.logger.default.handlers.transport']);

    return $logger;
};

/**
 * Define services
 */
$container['avisota.core.options-builder'] = $container->share(
    function () {
        return new Avisota\Contao\Core\DataContainer\OptionsBuilder();
    }
);

$container['avisota.service-factory'] = $container->share(
    function () {
        return new \Avisota\Contao\Core\ServiceFactory();
    }
);

/**
 * Define transport renderer
 */
$container['avisota.transport.renderer'] = $container->share(
    function () {
        $chain = new \Avisota\Renderer\MessageRendererChain();

        foreach ($GLOBALS['AVISOTA_TRANSPORT_RENDERER'] as $renderer) {
            $priority = 0;

            // priority support
            if (is_array($renderer) && count($renderer) == 2 && is_int($renderer[1])) {
                list($renderer, $priority) = $renderer;
            }

            // factory support
            if (is_callable($renderer)) {
                $renderer = call_user_func($renderer);
            }

            // instanciate class
            if (is_string($renderer)) {
                $renderer = new $renderer();
            }

            $chain->addRenderer($renderer, $priority);
        }

        return $chain;
    }
);

/**
 * Define recipient synonym fields service
 */
$container['avisota.recipient.synonymizer'] = $container->share(
    function () {
        return new \Avisota\Contao\Core\Recipient\SynonymizerService();
    }
);
