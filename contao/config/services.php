<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota
 * @license    LGPL
 * @filesource
 */

$container['avisota.subscription'] = $container->share(
	function($container) {
		return new \Avisota\Contao\SubscriptionManager();
	}
);

/**
 * Define logger defaults
 */
$container['avisota.logger.default.level'] = function($container) {
	return $container['logger.default.level'];
};

$container['avisota.logger.default.level.transport'] = function($container) {
	return $container['avisota.logger.default.level'];
};

$container['avisota.logger.default.handlers'] = new ArrayObject(
	array('avisota.logger.handler.general')
);

$container['avisota.logger.default.handlers.transport'] = new ArrayObject(
	array('avisota.logger.handler.transport', 'avisota.logger.handler')
);

/**
 * Define logger handlers
 */
$container['avisota.logger.handler.general'] = $container->share(
	function($container) {
		$factory = $container['logger.factory.handler.rotatingFile'];

		return $factory('avisota.log', $container['avisota.logger.default.level']);
	}
);

$container['avisota.logger.handler.transport'] = $container->share(
	function($container) {
		$factory = $container['logger.factory.handler.rotatingFile'];

		return $factory('avisota.transport.log', $container['avisota.logger.default.level.transport']);
	}
);

/**
 * Define loggers
 */
$container['avisota.logger'] = function($container) {
	$factory = $container['logger.factory'];
	$logger = $factory('avisota', $container['avisota.logger.default.handlers']);

	return $logger;
};

$container['avisota.logger.transport'] = function($container) {
	$factory = $container['logger.factory'];
	$logger = $factory('avisota.transport', $container['avisota.logger.default.handlers.transport']);

	return $logger;
};

/**
 * Define dynamic services
 */
foreach ($GLOBALS['AVISOTA_DYNAMICS'] as $type => $records) {
	foreach ($records as $id => $alias) {
		if (!empty($alias)) {
			// register service
			$container[sprintf('avisota.%s.%s', $type, $alias)] = function ($container) use ($type, $id) {
				throw new RuntimeException('Service factory not implemented yet!');
			};

			$container[sprintf('avisota.logger.%s.%s.level', $type, $alias)] = function($container) {
				return $container['avisota.logger.default.level'];
			};

			$container[sprintf('avisota.logger.%s.%s.handler', $type, $alias)] = $container->share(
				function($container) use ($type, $alias) {
					$factory = $container['logger.factory.handler.stream'];

					return $factory(
						sprintf('avisota.%s.%s.log', $type, $alias),
						$container[sprintf('avisota.logger.%s.%s.level', $type, $alias)]
					);
				}
			);

			$container[sprintf('avisota.logger.%s.%s', $type, $alias)] = function($container) use ($type, $alias) {
				$handlers = $container['avisota.logger.transport.default.handlers'];
				$handlers[] = sprintf('avisota.logger.%s.%s.handler', $type, $alias);

				$factory = $container['logger.factory'];
				$logger = $factory('avisota.transport', $handlers);

				return $logger;
			};
		}
	}
}
