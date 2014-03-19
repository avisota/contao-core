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

namespace Avisota\Contao\Core;

use Avisota\Contao\Entity\MessageCategory;
use Contao\Doctrine\ORM\EntityHelper;
use Doctrine\ORM\EntityRepository;

class CoreEvents
{
	/**
	 * The CREATE_MAILING_LIST_OPTIONS event occurs when an option list for mailing list records must generated.
	 *
	 * The event listener method receives
	 * a ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent instance.
	 *
	 * @var string
	 *
	 * @api
	 */
	const CREATE_MAILING_LIST_OPTIONS = 'avisota.create-mailing-list-options';

	/**
	 * The CREATE_RECIPIENT_SOURCE_OPTIONS event occurs when an option list for recipient source records must generated.
	 *
	 * The event listener method receives
	 * a ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent instance.
	 *
	 * @var string
	 *
	 * @api
	 */
	const CREATE_RECIPIENT_SOURCE_OPTIONS = 'avisota.create-recipient-source-options';

	/**
	 * The CREATE_QUEUE_OPTIONS event occurs when an option list for queue records must generated.
	 *
	 * The event listener method receives
	 * a ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent instance.
	 *
	 * @var string
	 *
	 * @api
	 */
	const CREATE_QUEUE_OPTIONS = 'avisota.create-queue-options';

	/**
	 * The CREATE_TRANSPORT_OPTIONS event occurs when an option list for transport records must generated.
	 *
	 * The event listener method receives
	 * a ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEvent instance.
	 *
	 * @var string
	 *
	 * @api
	 */
	const CREATE_TRANSPORT_OPTIONS = 'avisota.create-transport-options';
}