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
 * Table orm_avisota_mailing_list
 * Entity Avisota\Contao:MailingList
 */
$GLOBALS['TL_DCA']['orm_avisota_mailing_list'] = array
(
    // Entity
    'entity'       => array(
        'idGenerator' => \Doctrine\ORM\Mapping\ClassMetadataInfo::GENERATOR_TYPE_UUID
    ),
    // Config
    'config'       => array
    (
        'dataContainer'    => 'General',
        'enableVersioning' => true,
    ),
    // DataContainer
    'dca_config'   => array
    (
        'data_provider' => array
        (
            'default' => array
            (
                'class'  => 'Contao\Doctrine\ORM\DataContainer\General\EntityDataProvider',
                'source' => 'orm_avisota_mailing_list'
            )
        ),
    ),
    // List
    'list'         => array
    (
        'sorting'           => array
        (
            'mode'        => 1,
            'flag'        => 1,
            'fields'      => array('title'),
            'panelLayout' => 'search,limit'
        ),
        'label'             => array
        (
            'fields'         => array('title'),
            'format'         => '%s',
            'label_callback' => array('Avisota\Contao\Core\DataContainer\MailingList', 'getLabel')
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
                'label' => &$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif',
            ),
            'copy'   => array
            (
                'label'      => &$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['copy'],
                'icon'       => 'copy.gif',
                'attributes' => 'onclick="Backend.getScrollOffset();"',
            ),
            // TODO description for alert box
            'delete' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' =>
                    'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; ' .
                    'Backend.getScrollOffset();"',
            ),
            'show'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif'
            )
        ),
    ),
    // Palettes
    'metapalettes' => array
    (
        'default' => array
        (
            'list' => array('title', 'alias'),
        )
    ),
    // Fields
    'fields'       => array
    (
        'id'        => array(
            'field' => array(
                'id'      => true,
                'type'    => 'string',
                'length'  => '36',
                'options' => array('fixed' => true),
            )
        ),
        'createdAt' => array(
            'field' => array(
                'type'          => 'datetime',
                'nullable'      => true,
                'timestampable' => array('on' => 'create')
            )
        ),
        'updatedAt' => array(
            'field' => array(
                'type'          => 'datetime',
                'nullable'      => true,
                'timestampable' => array('on' => 'update')
            )
        ),
        'title'     => array
        (
            'label'     => &$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['title'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array(
                'mandatory' => true,
                'maxlength' => 255,
                'tl_class'  => 'w50'
            )
        ),
        'alias'     => array
        (
            'label'           => &$GLOBALS['TL_LANG']['orm_avisota_mailing_list']['alias'],
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
            )
        )
    )
);
