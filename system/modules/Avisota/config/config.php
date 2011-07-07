<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2010,2011 Tristan Lins
 *
 * Extension for:
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 * 
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  InfinitySoft 2010,2011
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @author     Oliver Hoff <oliver@hofff.com>
 * @package    Avisota
 * @license    LGPL
 * @filesource
 */


/**
 * Constants
 */
define('NL_HTML', 'html');
define('NL_PLAIN', 'plain');


/**
 * Page types
 */
$GLOBALS['TL_PTY']['avisota'] = 'PageAvisotaNewsletter';


/**
 * Form fields
 */
$GLOBALS['BE_FFL']['upload']                 = 'UploadField';
$GLOBALS['BE_FFL']['columnAssignmentWizard'] = 'ColumnAssignmentWizard';


/**
 * Back end modules
 */
$i = array_search('design', array_keys($GLOBALS['BE_MOD']));
$GLOBALS['BE_MOD'] = array_merge(
	array_slice($GLOBALS['BE_MOD'], 0, $i),
	array
	(
		'avisota' => array
		(
			'avisota_recipients' => array
			(
				'tables'     => array('tl_avisota_recipient_list', 'tl_avisota_recipient', 'tl_avisota_recipient_migrate', 'tl_avisota_recipient_import', 'tl_avisota_recipient_remove'),
				'icon'       => 'system/modules/Avisota/html/recipients.png',
				'stylesheet' => 'system/modules/Avisota/html/stylesheet.css'
			),
			'avisota_newsletter' => array
			(
				'tables'     => array('tl_avisota_newsletter_category', 'tl_avisota_newsletter', 'tl_avisota_newsletter_content'),
				'preview'    => array('Avisota', 'preview'),
				'send'       => array('Avisota', 'send'),
				'icon'       => 'system/modules/Avisota/html/newsletter.png',
				'stylesheet' => 'system/modules/Avisota/html/stylesheet.css'
			),
			'avisota_outbox' => array
			(
				'callback'   => 'Avisota',
				'icon'       => 'system/modules/Avisota/html/outbox.png',
				'stylesheet' => 'system/modules/Avisota/html/stylesheet.css'
			),
			'avisota_translation' => array
			(
				'tables'     => array('tl_avisota_translation'),
				'icon'       => 'system/modules/Avisota/html/translation.png',
				'stylesheet' => 'system/modules/Avisota/html/stylesheet.css'
			)
		)
	),
	array_slice($GLOBALS['BE_MOD'], $i)
);


/**
 * Front end modules
 */
$GLOBALS['FE_MOD']['avisota']['avisota_subscription'] = 'ModuleAvisotaSubscription';
$GLOBALS['FE_MOD']['avisota']['avisota_registration'] = 'ModuleAvisotaRegistration';


/**
 * Newsletter elements
 */
$GLOBALS['TL_NLE'] = array_merge_recursive(
	array
	(
		'texts' => array
		(
			'headline'  => 'NewsletterHeadline',
			'text'      => 'NewsletterText',
			'list'      => 'NewsletterList',
			'table'     => 'NewsletterTable'
		),
		'links' => array
		(
			'hyperlink' => 'NewsletterHyperlink'
		),
		'images' => array
		(
			'image'     => 'NewsletterImage',
			'gallery'   => 'NewsletterGallery'
		) ,
		'includes' => array
		(
			'news'      => 'NewsletterNews',
			'events'    => 'NewsletterEvent',
			'article'   => 'NewsletterArticleTeaser'
		) 
	),
	is_array($GLOBALS['TL_NLE']) ? $GLOBALS['TL_NLE'] : array()
);


/**
 * Widgets
 */
$GLOBALS['BE_FFL']['eventchooser'] = 'WidgetEventchooser';
$GLOBALS['BE_FFL']['newschooser'] = 'WidgetNewschooser';


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = array('AvisotaBackend', 'hookOutputBackendTemplate');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][]     = array('AvisotaInsertTag', 'replaceNewsletterInsertTags');
$GLOBALS['TL_HOOKS']['getEditorStylesLayout'][] = array('AvisotaEditorStyle', 'getEditorStylesLayout');
$GLOBALS['TL_HOOKS']['loadDataContainer'][]     = array('AvisotaRegistrationDCA', 'loadDataContainer');
$GLOBALS['TL_HOOKS']['createNewUser'][]         = array('AvisotaRegistrationDCA', 'createNewUser');
$GLOBALS['TL_HOOKS']['activateAccount'][]       = array('AvisotaRegistrationDCA', 'activateAccount');


/**
 * Custom user permissions.
 */
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_recipient_lists';
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_recipient_list_permissions';
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_recipient_permissions';
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_newsletter_categories';
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_newsletter_category_permissions';
$GLOBALS['TL_PERMISSIONS'][] = 'avisota_newsletter_permissions';


/**
 * Cron
 */
$GLOBALS['TL_CRON']['daily'][] = array('AvisotaBackend', 'cronCleanupRecipientList');


/**
 * folderurl support
 */
$GLOBALS['URL_KEYWORDS'][] = 'item';


/**
 * Hack: Fix ajax load import source tree.
 */
if (($_GET['table'] == 'tl_avisota_recipient_import' || $_GET['table'] == 'tl_avisota_recipient_remove') && ($_GET['isAjax'] || $_POST['isAjax']))
{
	unset($_GET['table']);
}


/**
 * JavaScript inject
 */
if (TL_MODE == 'BE' && $_GET['do'] == 'avisota_recipients' && $_GET['table'] == 'tl_avisota_recipient')
{
	$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/Avisota/html/tl_avisota_recipient.js.php';
}

?>
