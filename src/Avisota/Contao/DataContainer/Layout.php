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
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\DataContainer;

use Avisota\Contao\Event\CollectStylesheetsEvent;
use DcGeneral\DC_General;
use Symfony\Component\EventDispatcher\EventDispatcher;

class Layout
{
	/**
	 * Add the type of content element
	 *
	 * @param array
	 *
	 * @return string
	 */
	static public function addElement($contentData)
	{
		return sprintf(
			'<div>%s</div>' . "\n",
			$contentData['title']
		);
	}

	/**
	 * @param DC_General|\Avisota\Contao\Entity\Layout $layout
	 */
	static public function getCellContentOptions($layout)
	{
		$options = array();

		if ($layout instanceof DC_General) {
			$layout = $layout->getEnvironment()->getCurrentModel()->getEntity();
		}

		list($group, $mailChimpTemplate) = explode(':', $layout->getMailchimpTemplate());
		if (isset($GLOBALS['AVISOTA_MAILCHIMP_TEMPLATE'][$group][$mailChimpTemplate])) {
			$config = $GLOBALS['AVISOTA_MAILCHIMP_TEMPLATE'][$group][$mailChimpTemplate];

			if (isset($config['cells'])) {
				foreach ($config['cells'] as $cellName => $cellConfig) {
					if (!isset($cellConfig['content'])) {
						foreach ($GLOBALS['TL_MCE'] as $elementGroup => $elements) {
							if (isset($GLOBALS['TL_LANG']['MCE'][$elementGroup])) {
								$elementGroupLabel = $GLOBALS['TL_LANG']['MCE'][$elementGroup];
							}
							else {
								$elementGroupLabel = $elementGroup;
							}
							foreach ($elements as $elementType) {
								if (isset($GLOBALS['TL_LANG']['MCE'][$elementType])) {
									$elementLabel = $GLOBALS['TL_LANG']['MCE'][$elementType][0];
								}
								else {
									$elementLabel = $elementType;
								}

								$options[$cellName][$cellName . ':' . $elementType] = sprintf(
									'[%s] %s',
									$elementGroupLabel,
									$elementLabel
								);
							}
						}
					}
				}
			}
		}

		return $options;
	}

	/**
	 * @param DC_General|\Avisota\Contao\Entity\Layout $layout
	 *
	 * @return array
	 */
	static public function getDefaultSelectedCellContentElements($layout)
	{
		$value = array();

		if ($layout instanceof DC_General) {
			$layout = $layout->getEnvironment()->getCurrentModel()->getEntity();
		}

		list($group, $mailChimpTemplate) = explode(':', $layout->getMailchimpTemplate());
		if (isset($GLOBALS['AVISOTA_MAILCHIMP_TEMPLATE'][$group][$mailChimpTemplate])) {
			$config = $GLOBALS['AVISOTA_MAILCHIMP_TEMPLATE'][$group][$mailChimpTemplate];

			if (isset($config['cells'])) {
				foreach ($config['cells'] as $cellName => $cellConfig) {
					if (isset($cellConfig['preferedElements'])) {
						foreach ($cellConfig['preferedElements'] as $elementName) {
							$value[] = $cellName . ':' . $elementName;
						}
					}
					else {
						foreach ($GLOBALS['TL_MCE'] as $elements) {
							foreach ($elements as $elementType) {
								$value[] = $cellName . ':' . $elementType;
							}
						}
					}
				}
			}
		}
		return $value;
	}

	static public function getterCallbackAllowedCellContents($value, \Avisota\Contao\Entity\Layout $layout)
	{
		if ($value === null) {
			return static::getDefaultSelectedCellContentElements($layout);
		}

		return $value;
	}

	static public function setterCallbackAllowedCellContents($value, \Avisota\Contao\Entity\Layout $layout)
	{
		if (!is_array($value)) {
			$value = null;
		}
		else if ($value !== null) {
			$defaultValue = static::getDefaultSelectedCellContentElements($layout);

			$diffLeft = array_diff($value, $defaultValue);
			$diffRight = array_diff($defaultValue, $value);

			if (!(count($diffLeft) + count($diffRight))) {
				$value = null;
			}
		}

		return $value;
	}


	public function getStylesheets($dc)
	{
		/** @var EventDispatcher $eventDispatcher */
		$eventDispatcher = $GLOBALS['container']['event-dispatcher'];

		$stylesheets = new \ArrayObject();

		$eventDispatcher->dispatch(CollectStylesheetsEvent::NAME, new CollectStylesheetsEvent($stylesheets));

		return $stylesheets->getArrayCopy();
	}
}
