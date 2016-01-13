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

/**
 * Class CoreEvents
 *
 * @package Avisota\Contao\Core
 */
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

    /**
     * The CREATE_RECIPIENT_SOURCE event occurs when a recipient source service is created.
     *
     * The event listener method receives a Avisota\Contao\Core\Event\CreateRecipientSourceEvent instance.
     *
     * @var string
     *
     * @api
     */
    const CREATE_RECIPIENT_SOURCE = 'avisota.create-recipient-source';

    /**
     * The CREATE_FAKE_RECIPIENT event occurs when a fake recipient must be created.
     *
     * The event listener method receives a Avisota\Contao\Core\Event\CreateFakeRecipientEvent instance.
     *
     * @var string
     *
     * @api
     */
    const CREATE_FAKE_RECIPIENT = 'avisota.create-fake-recipient';

    /**
     * The CREATE_PUBLIC_EMPTY_RECIPIENT event occurs when a public empty recipient must be created.
     *
     * The event listener method receives a Avisota\Contao\Core\Event\CreatePublicEmptyRecipientEvent instance.
     *
     * @var string
     *
     * @api
     */
    const CREATE_PUBLIC_EMPTY_RECIPIENT = 'avisota.create-public-empty-recipient';
}
