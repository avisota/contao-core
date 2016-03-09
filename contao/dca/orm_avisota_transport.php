<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Table orm_avisota_transport
 * Entity Avisota\Contao:Transport
 */
$GLOBALS['TL_DCA']['orm_avisota_transport'] = array
(
    // Entity
    'entity'          => array(
        'idGenerator' => \Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_UUID
    ),
    // Config
    'config'          => array
    (
        'dataContainer'    => 'General',
        'enableVersioning' => true,
    ),
    // DataContainer
    'dca_config'      => array
    (
        'data_provider' => array
        (
            'default' => array
            (
                'class'  => 'Contao\Doctrine\ORM\DataContainer\General\EntityDataProvider',
                'source' => 'orm_avisota_transport'
            )
        ),
    ),
    // List
    'list'            => array
    (
        'sorting'           => array
        (
            'mode'   => 1,
            'flag'   => 1,
            'fields' => array('title', 'type')
        ),
        'label'             => array
        (
            'fields' => array('title', 'type'),
            'format' => '%s <span style="color:#b3b3b3; padding-left:3px;">(%s)</span>'
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
            )
        ),
        'operations'        => array
        (
            'edit'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['orm_avisota_transport']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ),
            // TODO alert box description
            'delete' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['orm_avisota_transport']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' =>
                    'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; ' .
                    'Backend.getScrollOffset();"'
            ),
            'show'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['orm_avisota_transport']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif'
            )
        ),
    ),
    // Palettes
    'palettes'        => array(
        '__selector__' => array('type', 'swiftUseSmtp')
    ),
    // Meta Palettes
    'metapalettes'    => array
    (
        'default'           => array(
            'transport' => array('type')
        ),
        'swift'             => array(
            'transport' => array('title', 'alias', 'type'),
            'contact'   => array('fromAddress', 'fromName', 'setSender', 'setReplyTo'),
            'swift'     => array('swiftUseSmtp')
        ),
        'swift|swiftSmtpOn' => array(
            'transport' => array('title', 'alias', 'type'),
            'contact'   => array('fromAddress', 'fromName', 'setSender', 'setReplyTo'),
            'swift'     => array(
                'swiftUseSmtp',
                'swiftSmtpHost',
                'swiftSmtpUser',
                'swiftSmtpPass',
                'swiftSmtpEnc',
                'swiftSmtpPort'
            ),
        ),
        'service'           => array(
            'transport' => array('title', 'alias', 'type'),
            'service'   => array('serviceName')
        ),
    ),
    'metasubpalettes' => array(
        'setSender'  => array('senderAddress', 'senderName'),
        'setReplyTo' => array('replyToAddress', 'replyToName'),
    ),
    // Fields
    'fields'          => array
    (
        'id'             => array(
            'field' => array(
                'id'      => true,
                'type'    => 'string',
                'length'  => '36',
                'options' => array('fixed' => true),
            )
        ),
        'createdAt'      => array(
            'field' => array(
                'type'          => 'datetime',
                'nullable'      => true,
                'timestampable' => array('on' => 'create')
            )
        ),
        'updatedAt'      => array(
            'field' => array(
                'type'          => 'datetime',
                'nullable'      => true,
                'timestampable' => array('on' => 'update')
            )
        ),
        'type'           => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['type'],
            'inputType' => 'select',
            'options'   => array_keys($GLOBALS['AVISOTA_TRANSPORT']),
            'reference' => &$GLOBALS['TL_LANG']['orm_avisota_transport'],
            'eval'      => array(
                'mandatory'          => true,
                'submitOnChange'     => true,
                'includeBlankOption' => true,
                'tl_class'           => 'w50'
            )
        ),
        'title'          => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['title'],
            'inputType' => 'text',
            'eval'      => array(
                'mandatory' => true,
                'maxlength' => 255,
                'tl_class'  => 'w50'
            ),
        ),
        'alias'          => array
        (
            'label'           => &$GLOBALS['TL_LANG']['orm_avisota_transport']['alias'],
            'exclude'         => true,
            'search'          => true,
            'inputType'       => 'text',
            'eval'            => array(
                'rgxp'              => 'alnum',
                'unique'            => true,
                'spaceToUnderscore' => true,
                'maxlength'         => 128,
                'tl_class'          => 'w50'
            ),
            'setter_callback' => array
            (
                array('Contao\Doctrine\ORM\Helper', 'generateAlias')
            ),
        ),
        'fromAddress'    => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['fromAddress'],
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'           => 'email',
                'maxlength'      => 128,
                'decodeEntities' => true,
                'mandatory'      => true,
                'tl_class'       => 'w50'
            ),
            'field'     => array(),
        ),
        'fromName'       => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['fromName'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'flag'      => 11,
            'inputType' => 'text',
            'eval'      => array(
                'decodeEntities' => true,
                'maxlength'      => 128,
                'tl_class'       => 'w50'
            ),
            'field'     => array(),
        ),
        'setSender'      => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['setSender'],
            'inputType' => 'checkbox',
            'eval'      => array('tl_class' => 'w50 clr m12', 'submitOnChange' => true)
        ),
        'senderAddress'  => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['senderAddress'],
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'           => 'email',
                'maxlength'      => 128,
                'decodeEntities' => true,
                'mandatory'      => true,
                'tl_class'       => 'clr w50'
            ),
            'field'     => array(),
        ),
        'senderName'     => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['senderName'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'flag'      => 11,
            'inputType' => 'text',
            'eval'      => array(
                'decodeEntities' => true,
                'maxlength'      => 128,
                'tl_class'       => 'w50'
            ),
            'field'     => array(),
        ),
        'setReplyTo'     => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['setReplyTo'],
            'inputType' => 'checkbox',
            'eval'      => array('tl_class' => 'w50 clr m12', 'submitOnChange' => true)
        ),
        'replyToAddress' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['replyToAddress'],
            'exclude'   => true,
            'search'    => true,
            'filter'    => true,
            'inputType' => 'text',
            'eval'      => array(
                'rgxp'           => 'email',
                'maxlength'      => 128,
                'decodeEntities' => true,
                'mandatory'      => true,
                'tl_class'       => 'clr w50'
            ),
            'field'     => array(),
        ),
        'replyToName'    => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['replyToName'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'flag'      => 11,
            'inputType' => 'text',
            'eval'      => array(
                'decodeEntities' => true,
                'maxlength'      => 128,
                'tl_class'       => 'w50'
            ),
            'field'     => array(),
        ),
        // swift mailer
        'swiftUseSmtp'   => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['swiftUseSmtp'],
            'default'   => 'swiftSmtpSystemSettings',
            'exclude'   => true,
            'inputType' => 'select',
            'options'   => array('swiftSmtpSystemSettings', 'swiftSmtpOn', 'swiftSmtpOff'),
            'reference' => &$GLOBALS['TL_LANG']['orm_avisota_transport'],
            'eval'      => array(
                'submitOnChange' => true,
                'tl_class'       => 'w50'
            )
        ),
        'swiftSmtpHost'  => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['swiftSmtpHost'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'mandatory' => true,
                'maxlength' => 64,
                'nospace'   => true,
                'doNotShow' => true,
                'tl_class'  => 'w50'
            ),
            'field'     => array(),
        ),
        'swiftSmtpUser'  => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['swiftSmtpUser'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'decodeEntities' => true,
                'maxlength'      => 128,
                'doNotShow'      => true,
                'tl_class'       => 'w50'
            ),
            'field'     => array(),
        ),
        'swiftSmtpPass'  => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['swiftSmtpPass'],
            'exclude'   => true,
            'inputType' => 'textStore',
            'eval'      => array(
                'decodeEntities' => true,
                'maxlength'      => 32,
                'doNotShow'      => true,
                'tl_class'       => 'w50'
            ),
            'field'     => array(),
        ),
        'swiftSmtpEnc'   => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['swiftSmtpEnc'],
            'exclude'   => true,
            'inputType' => 'select',
            'options'   => array(
                'ssl' => 'SSL',
                'tls' => 'TLS'
            ),
            'eval'      => array(
                'includeBlankOption' => true,
                'doNotShow'          => true,
                'tl_class'           => 'w50'
            ),
            'field'     => array(),
        ),
        'swiftSmtpPort'  => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['swiftSmtpPort'],
            'default'   => 25,
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array(
                'mandatory' => true,
                'rgxp'      => 'digit',
                'nospace'   => true,
                'doNotShow' => true,
                'tl_class'  => 'w50'
            )
        ),
        'serviceName'    => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_transport']['serviceName'],
            'inputType' => 'text',
            'eval'      => array(
                'mandatory' => true,
                'maxlength' => 255,
                'tl_class'  => 'w50'
            ),
            'field'     => array(),
        ),
    )
);
