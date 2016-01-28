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
 * Table orm_avisota_queue
 * Entity Avisota\Contao:Queue
 */
$GLOBALS['TL_DCA']['orm_avisota_queue'] = array
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
                'source' => 'orm_avisota_queue'
            )
        ),
    ),
    // List
    'list'            => array
    (
        'sorting'           => array
        (
            'mode'   => 1,
            'flag'   => 11,
            'fields' => array('title')
        ),
        'label'             => array
        (
            'fields' => array('title', 'type'),
            'format' => '%s <span style="color:#b3b3b3; padding-left:3px;">(%s)</span><br>'
        ),
        'global_operations' => array
        (/*
			'all' => array
			(
				'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'       => 'act=select',
				'class'      => 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
			*/
        ),
        'operations'        => array
        (
            'edit'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['orm_avisota_queue']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ),
            // TODO alert box description
            'delete' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['orm_avisota_queue']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' =>
                    'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; ' .
                    'Backend.getScrollOffset();"'
            ),
            // TODO alert box description
            'clear'  => array
            (
                'label'      => &$GLOBALS['TL_LANG']['orm_avisota_queue']['clear'],
                'href'       => 'act=clear',
                'icon'       => 'assets/avisota/core/images/clear.png',
                'attributes' =>
                    'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['orm_avisota_queue']['clearConfirm'] . '\')) ' .
                    'return false; Backend.getScrollOffset();"'
            ),
            'show'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['orm_avisota_queue']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif'
            )
        ),
    ),
    // Palettes
    'palettes'        => array(
        '__selector__' => array('type')
    ),
    // Meta Palettes
    'metapalettes'    => array
    (
        'default'        => array(
            'queue' => array('type'),
        ),
        'simpleDatabase' => array(
            'queue'     => array('type', 'title', 'alias'),
            'transport' => array(
                'transport',
                'maxSendTime',
                'maxSendCount',
                'cyclePause',
            ),
            'config'    => array('simpleDatabaseQueueTable'),
            'send'      => array('allowManualSending')
        ),
    ),
    'metasubpalettes' => array(),
    // Fields
    'fields'          => array
    (
        'id'                       => array(
            'field' => array(
                'id'      => true,
                'type'    => 'string',
                'length'  => '36',
                'options' => array('fixed' => true),
            )
        ),
        'createdAt'                => array(
            'field' => array(
                'type'          => 'datetime',
                'nullable'      => true,
                'timestampable' => array('on' => 'create'),
            )
        ),
        'updatedAt'                => array(
            'field' => array(
                'type'          => 'datetime',
                'nullable'      => true,
                'timestampable' => array('on' => 'update'),
            )
        ),
        'type'                     => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_queue']['type'],
            'inputType' => 'select',
            'options'   => array_keys($GLOBALS['AVISOTA_QUEUE']),
            'reference' => &$GLOBALS['TL_LANG']['orm_avisota_queue'],
            'filter'    => true,
            'eval'      => array(
                'mandatory'          => true,
                'submitOnChange'     => true,
                'includeBlankOption' => true,
            )
        ),
        'title'                    => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_queue']['title'],
            'inputType' => 'text',
            'search'    => true,
            'flag'      => 1,
            'eval'      => array(
                'mandatory' => true,
                'maxlength' => 255,
                'tl_class'  => 'w50'
            )
        ),
        'alias'                    => array
        (
            'label'           => &$GLOBALS['TL_LANG']['orm_avisota_queue']['alias'],
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
            'load_callback'   => array
            (
                array('Avisota\Contao\Core\DataContainer\Queue', 'rememberAlias')
            ),
            'setter_callback' => array
            (
                array('Contao\Doctrine\ORM\Helper', 'generateAlias')
            )
        ),
        'transport'                => array
        (
            'label'            => &$GLOBALS['TL_LANG']['orm_avisota_queue']['transport'],
            'inputType'        => 'select',
            'eval'             => array(
                'mandatory'          => true,
                'includeBlankOption' => true,
                'tl_class'           => 'w50'
            ),
            'manyToOne'        => array(
                'targetEntity' => 'Avisota\Contao\Entity\Transport',
                'joinColumns'  => array(
                    array(
                        'name'                 => 'transport',
                        'referencedColumnName' => 'id',
                    ),
                ),
            ),
            'options_callback' =>
                \ContaoCommunityAlliance\Contao\Events\CreateOptions\CreateOptionsEventCallbackFactory::createCallback(
                    'avisota.create-transport-options',
                    'Avisota\Contao\Core\Event\CreateOptionsEvent'
                ),
        ),
        'maxSendTime'              => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_queue']['maxSendTime'],
            'default'   => ini_get('max_execution_time') > 0
                ? floor(0.85 * ini_get('max_execution_time'))
                : 60,
            'inputType' => 'text',
            'eval'      => array(
                'mandatory' => true,
                'rgxp'      => 'digit',
                'tl_class'  => 'w50'
            )
        ),
        'maxSendCount'             => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_queue']['maxSendCount'],
            'default'   => 100,
            'inputType' => 'text',
            'eval'      => array(
                'mandatory' => true,
                'rgxp'      => 'digit',
                'tl_class'  => 'w50'
            )
        ),
        'cyclePause'               => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_queue']['cyclePause'],
            'default'   => 10,
            'inputType' => 'text',
            'eval'      => array(
                'mandatory' => true,
                'rgxp'      => 'digit',
                'tl_class'  => 'w50'
            )
        ),
        'simpleDatabaseQueueTable' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_queue']['simpleDatabaseQueueTable'],
            'inputType' => 'text',
            'eval'      => array(
                'mandatory' => true,
                'maxlength' => 255,
                'tl_class'  => 'm12 w50'
            )
        ),
        'allowManualSending'       => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_queue']['allowManualSending'],
            'default'   => true,
            'inputType' => 'checkbox',
            'eval'      => array(
                'mandatory' => true,
                'tl_class'  => 'm12 w50'
            )
        ),
    )
);
