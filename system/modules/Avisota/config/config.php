<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


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
				'tables'     => array('tl_avisota_recipient_list', 'tl_avisota_recipient', 'tl_avisota_recipient_import'),
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
			'avisota_newsletter_draft' => array
			(
				'tables'     => array('tl_avisota_newsletter_draft', 'tl_avisota_newsletter_draft_content'),
				'icon'       => 'system/modules/Avisota/html/newsletter_draft.png',
				'stylesheet' => 'system/modules/Avisota/html/stylesheet.css'
			),
			'avisota_recipient_source' => array
			(
				'tables'     => array('tl_avisota_recipient_source'),
				'icon'       => 'system/modules/Avisota/html/recipient_source.png',
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
			// 'news'      => 'NewsletterNews',
			'events'    => 'NewsletterEvent',
			//'article'   => 'NewsletterArticle'
		) 
	),
	is_array($GLOBALS['TL_NLE']) ? $GLOBALS['TL_NLE'] : array()
);


/**
 * Widgets
 */
$GLOBALS['BE_FFL']['eventchooser'] = 'WidgetEventchooser';


/**
 * Recipient sources
 */
$GLOBALS['TL_AVISOTA_RECIPIENT_SOURCE'] = array_merge_recursive(
	array
	(
		'integrated'     => 'IntegratedAvisotaRecipientSource',
		'member_groups'  => 'MemberGroupRecipientSource',
		'csv_file'       => 'CSVFileRecipientSource'
	),
	is_array($GLOBALS['TL_AVISOTA_RECIPIENT_SOURCE']) ? $GLOBALS['TL_AVISOTA_RECIPIENT_SOURCE'] : array()
);


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = array('AvisotaBackend', 'hookOutputBackendTemplate');
$GLOBALS['TL_HOOKS']['replaceInsertTags'][] = array('AvisotaInsertTag', 'replaceNewsletterInsertTags');
$GLOBALS['TL_HOOKS']['getEditorStylesLayout'][] = array('AvisotaEditorStyle', 'getEditorStylesLayout');

?>