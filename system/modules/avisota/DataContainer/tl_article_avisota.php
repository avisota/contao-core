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
