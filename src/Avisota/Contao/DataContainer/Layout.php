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

namespace Avisota\Contao\DataContainer;

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
	 * @param \DC_General|\Avisota\Contao\Entity\Layout $layout
	 */
	static public function getCellContentOptions($layout)
	{
		if ($layout instanceof \DC_General) {
			$layout = $layout->getCurrentModel()->getEntity();
		}
		list($group, $baseTemplate) = explode(':', $layout->getBaseTemplate());
		$config = $GLOBALS['AVISOTA_MESSAGE_BASE_TEMPLATE'][$group][$baseTemplate];

		$options = array();
		foreach ($config['cells'] as $cellName => $cellConfig) {
			if (!isset($cellConfig['content'])) {
				foreach ($GLOBALS['TL_NLE'] as $elementGroup => $elements) {
					if (isset($GLOBALS['TL_LANG']['NLE'][$elementGroup])) {
						$elementGroupLabel = $GLOBALS['TL_LANG']['NLE'][$elementGroup];
					}
					else {
						$elementGroupLabel = $elementGroup;
					}
					foreach ($elements as $elementName => $elementClass) {
						if (isset($GLOBALS['TL_LANG']['NLE'][$elementName])) {
							$elementLabel = $GLOBALS['TL_LANG']['NLE'][$elementName][0];
						}
						else {
							$elementLabel = $elementName;
						}

						$options[$cellName][$cellName . ':' . $elementName] = sprintf(
							'[%s] %s',
							$elementGroupLabel,
							$elementLabel
						);
					}
				}
			}
		}

		return $options;
	}

	static public function getDefaultSelectedCellContentElements($layout)
	{
		if ($layout instanceof \DC_General) {
			$layout = $layout->getCurrentModel()->getEntity();
		}
		list($group, $baseTemplate) = explode(':', $layout->getBaseTemplate());
		$config = $GLOBALS['AVISOTA_MESSAGE_BASE_TEMPLATE'][$group][$baseTemplate];

		$value = array();
		foreach ($config['cells'] as $cellName => $cellConfig) {
			if (isset($cellConfig['preferedElements'])) {
				foreach ($cellConfig['preferedElements'] as $elementName) {
					$value[] = $cellName . ':' . $elementName;
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
}
