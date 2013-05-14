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

class tl_article_avisota extends tl_article
{

	public function pasteArticle(DataContainer $dc, $row, $table, $cr, $clipboardData = false)
	{
		if ($table == $GLOBALS['TL_DCA'][$dc->table]['config']['ptable'] && $row['type'] == 'avisota') {
			return $this->generateImage('pasteinto_.gif', '', 'class="blink"');
		}

		return parent::pasteArticle($dc, $row, $table, $cr, $clipboardData);
	}
}
