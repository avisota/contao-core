<?php

class tl_article_avisota extends tl_article
{
	public function alterDataContainer($strName)
	{
		if ($strName == 'tl_article')
		{
			$GLOBALS['TL_DCA']['tl_article']['list']['sorting']['paste_button_callback'][0] = 'tl_article_avisota';
		}
	}
	
	public function pasteArticle(DataContainer $dc, $row, $table, $cr, $arrClipboard=false)
	{
		if ($table == $GLOBALS['TL_DCA'][$dc->table]['config']['ptable'] && $row['type'] == 'avisota')
		{
			return $this->generateImage('pasteinto_.gif', '', 'class="blink"');
		}

		return parent::pasteArticle($dc, $row, $table, $cr, $arrClipboard);
	}
}

// add hook
$GLOBALS['TL_HOOKS']['loadDataContainer'][] = array('tl_article_avisota', 'alterDataContainer');

?>