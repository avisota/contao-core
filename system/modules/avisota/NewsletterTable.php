<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */


/**
 * Class NewsletterTable
 *
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 */
class NewsletterTable extends Element
{

	/**
	 * HTML Template
	 *
	 * @var string
	 */
	protected $templateHTML = 'nle_table_html';

	/**
	 * Plain text Template
	 *
	 * @var string
	 */
	protected $templatePlain = 'nle_table_plain';


	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
		$rows = deserialize($this->tableitems);

		$this->Template->id                 = 'table_' . $this->id;
		$this->Template->summary            = specialchars($this->summary);
		$this->Template->useHeader          = $this->thead ? true : false;
		$this->Template->useFooter          = $this->tfoot ? true : false;
		$this->Template->thousandsSeparator = $GLOBALS['TL_LANG']['MSC']['thousandsSeparator'];
		$this->Template->decimalSeparator   = $GLOBALS['TL_LANG']['MSC']['decimalSeparator'];

		$headerContents = array();
		$bodyContents   = array();
		$footerContents = array();

		// Table header
		if ($this->thead) {
			foreach ($rows[0] as $i => $v) {
				// Add cell
				$headerContents[] = array
				(
					'class'   => 'head_' . $i . (($i == 0) ? ' col_first' : '') . (($i == (count($rows[0]) - 1))
						? ' col_last' : ''),
					'content' => (strlen($v) ? nl2br($v) : '&nbsp;')
				);
			}

			array_shift($rows);
		}

		$this->Template->header = $headerContents;
		$limit                  = $this->tfoot ? (count($rows) - 1) : count($rows);

		// Table body
		for ($j = 0; $j < $limit; $j++) {
			$class_tr = '';

			if ($j == 0) {
				$class_tr = ' row_first';
			}

			if ($j == ($limit - 1)) {
				$class_tr = ' row_last';
			}

			$class_eo = (($j % 2) == 0) ? ' even' : ' odd';

			foreach ($rows[$j] as $i => $v) {
				$class_td = '';

				if ($i == 0) {
					$class_td = ' col_first';
				}

				if ($i == (count($rows[$j]) - 1)) {
					$class_td = ' col_last';
				}

				$bodyContents['row_' . $j . $class_tr . $class_eo][] = array
				(
					'class'   => 'col_' . $i . $class_td,
					'content' => (strlen($v) ? preg_replace('/[\n\r]+/i', '<br />', $v) : '&nbsp;')
				);
			}
		}

		$this->Template->body = $bodyContents;

		// Table footer
		if ($this->tfoot) {
			foreach ($rows[(count($rows) - 1)] as $i => $v) {
				$footerContents[] = array
				(
					'class'   => 'foot_' . $i . (($i == 0) ? ' col_first' : '') . (($i == (count(
						$rows[(count($rows) - 1)]
					) - 1)) ? ' col_last' : ''),
					'content' => (strlen($v) ? preg_replace('/[\n\r]+/i', '<br />', $v) : '&nbsp;')
				);
			}
		}

		$this->Template->footer = $footerContents;
	}
}
