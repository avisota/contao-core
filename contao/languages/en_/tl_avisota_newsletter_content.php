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


/**
 * Fields
 */
$GLOBALS['TL_LANG']['orm_avisota_message_content']['invisible']       = array(
	'Invisible ',
	' The element is not shown.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['type']            = array(
	'Element Type ',
	' Please choose the type of content element.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['cell']            = array(
	'Area',
	'Please choose the cell the content element should be showed in.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['headline']        = array(
	'Heading ',
	' Here you can add a title to the content item.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['text']            = array(
	'Text ',
	' You can use HTML tags to format your text.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['definePlain']     = array(
	'Plain Text Over-Ride',
	'Enter the plain text, rather than let it automatically create HTML from the text.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['plain']           = array(
	'Plain-text ',
	'Here you can specify the plain text.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['personalize']     = array(
	'Personalize ',
	'Here you can choose whether this item should be personalized.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['addImage']        = array(
	'Add Image ',
	' Add an image to the content element.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['singleSRC']       = array(
	'Source file ',
	' Please select a file or folder from the file browser.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['alt']             = array(
	'Alternative text ',
	' An accessible website should always include alternate text for images and movies with a brief description of their contents.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['size']            = array(
	'Image width and height ',
	'Here you can specify the dimensions of the image and the scaling mode.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['imagemargin']     = array(
	'Image Margin ',
	'Here you can enter the top, right, down, and the left margins.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['imageUrl']        = array(
	'Image Link Location ',
	' Image link address overwrites the Lightbox-Link, so that the image can not be shown in full view.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['floating']        = array(
	'Image Orientation ',
	' Please define how the image should be aligned.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['caption']         = array(
	'Caption ',
	'Here you can enter a short line of text that appears below the picture.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['listType']        = array(
	'List Type ',
	' Please choose the type of list.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['listItems']       = array(
	'List Items ',
	'If JavaScript is disabled, you must save your changes before you change the order.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['tableitems']      = array(
	'Table Entries ',
	'If JavaScript is disabled, you must save your changes until you change the order.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['summary']         = array(
	'Summary ',
	' Please enter a brief summary of the content and structure of the table.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['thead']           = array(
	'Headers ',
	' The first row of the table to use as the header.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['tfoot']           = array(
	'Add Footer ',
	' The last row of the table to use as a footer.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['linkTitle']       = array(
	'Link Text ',
	'The link text is displayed instead of the link address.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['embed']           = array(
	'Embeded Link',
	' Use the placeholder "%s" to embed the link in a line of text. (<em> For more information, please visit %s </ em>).'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['multiSRC']        = array(
	'Source Files ',
	' Please select one or more files or folders from the File Browser. If you select a folder, allo of the files from the folder are automatically inserted.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['perRow']          = array(
	'Thumbnails Per Row ',
	' The number of pictures per row.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['sortBy']          = array(
	'Sort By ',
	' Please select a sort order.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['galleryHtmlTpl']  = array(
	'HTML Template Gallery ',
	'Here you can select the HTML Template Gallery.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['galleryPlainTpl'] = array(
	'Plain Template Gallery ',
	'Here you can choose the Plain gallery template.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['protected']       = array(
	'Protect Item ',
	' Show content item only to certain groups.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['groups']          = array(
	'Allowed Member Groups ',
	' Only these groups can see the content element.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['guests']          = array(
	'Show Guests Only ',
	'The content item is not shown to persons who are logged in.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['cssID']           = array(
	'CSS ID/Class ',
	'Here you can enter an ID and any number of classes.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['space']           = array(
	'Space Before and After ',
	' Here you can enter the space before and after the content element in pixels. You should avoid inline styles, but if possible, you may add the spacing in a stylesheet.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['source']          = array(
	'Import CSV File ',
	' Please choose to import CSV files from the File Browser.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['events']          = array(
	'Events',
	'Choose events to include its teaser in the newletter.'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['news']            = array(
	'News',
	'Choose news to include its teaser in the newletter.'
);


/**
 * Legends
 */
$GLOBALS['TL_LANG']['orm_avisota_message_content']['type_legend']      = 'Element Tyoe';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['text_legend']      = 'Text';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['image_legend']     = 'Image Settings';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['list_legend']      = 'Item List';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['table_legend']     = 'Table Entries';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['tconfig_legend']   = 'Table Settings';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['sortable_legend']  = 'Sorting Options';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['link_legend']      = 'Link Settings';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['imglink_legend']   = 'Image Link Settings';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['template_legend']  = 'Template Settings';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['include_legend']   = 'Include Settings';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['protected_legend'] = 'Access Protection';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['expert_legend']    = 'Expert Settings';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['events_legend']    = 'Events';


/**
 * Reference
 */
$GLOBALS['TL_LANG']['orm_avisota_message_content']['anonymous']      = 'Anonymous personalize, if no personal data is available';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['private']        = 'Personal personalize hides the element, if no personal data is available';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['cell']['body']   = 'Content';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['cell']['header'] = 'Header';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['cell']['footer'] = 'Footer';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['cell']['left']   = 'Left column';
$GLOBALS['TL_LANG']['orm_avisota_message_content']['cell']['right']  = 'Right column';


/**
 * Buttons
 */
$GLOBALS['TL_LANG']['orm_avisota_message_content']['new']         = array('New Element ', 'Create a new element');
$GLOBALS['TL_LANG']['orm_avisota_message_content']['show']        = array(
	'Item Details ',
	' Details of the content item ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['cut']         = array(
	'Move Element ',
	' Move content element ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['copy']        = array(
	'Copy Element',
	'Copy content element  ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['delete']      = array(
	'Delete Element,',
	'Delete content element ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['edit']        = array(
	'Edit Element',
	'Edit content element ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['pasteafter']  = array(
	'Insert At Top ',
	' Insert after the article ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['pastenew']    = array(
	'Create New Article At Top ',
	' New article to create content ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['toggle']      = array(
	'Visibility Change ',
	' Change the visibility of the content item ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['editalias']   = array(
	'Source Edit Item ',
	' Edit source item ID %s'
);
$GLOBALS['TL_LANG']['orm_avisota_message_content']['editarticle'] = array('Edit Article ', ' Edit article ID %s');
