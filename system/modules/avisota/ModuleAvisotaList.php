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
 * Class ModuleAvisotaList
 *
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 */
class ModuleAvisotaList extends Module
{
	/**
	 * @var Database
	 */
	protected $Database;

	/**
	 * @var Input
	 */
	protected $Input;

	/**
	 * @var AvisotaBase
	 */
	protected $Base;

	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'mod_avisota_list';


	/**
	 * Construct the content element
	 */
	public function __construct(Database_Result $resultSet)
	{
		parent::__construct($resultSet);
		$this->import('DomainLink');
		$this->import('FrontendUser', 'User');
		$this->import('AvisotaBase', 'Base');
		$this->loadLanguageFile('avisota');
	}


	/**
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			$template           = new BackendTemplate('be_wildcard');
			$template->wildcard = '### Avisota Newsletter List ###';
			return $template->parse();
		}

		$this->avisota_categories = array_filter(
			array_map(
				'intval',
				deserialize($this->avisota_categories, true)
			)
		);
		if (!count($this->avisota_categories)) {
			$this->avisota_categories = array(0);
		}

		return parent::generate();
	}


	/**
	 * Generate the content element
	 */
	public function compile()
	{
		$id = $this->Input->get('items');

		$pageId = $this->Input->get('page');
		if (!$pageId) {
			$pageId = 0;
		}
		$limit  = $this->perPage;
		$offset = $pageId * $limit;

		$newsletter = \Database::getInstance()
			->prepare(
			"SELECT * FROM orm_avisota_message WHERE sendOn > 0 AND pid IN (" . implode(
				',',
				$this->avisota_categories
			) . ") ORDER BY sendOn DESC"
		);
		if ($limit > 0) {
			$newsletter->limit($limit, $offset);
		}
		$newsletter = $newsletter->execute();

		$viewPage        = $this->jumpTo ? $this->getPageDetails($this->avisota_view_page) : false;
		$viewOnlineCache = array();

		$newsletterDataSets = array();
		while ($newsletter->next()) {
			$newsletterData = $newsletter->row();

			$params = '/items/' . ($GLOBALS['TL_CONFIG']['disableAlias'] ? $newsletterData['id']
				: $newsletterData['alias']);

			if ($viewPage) {
				$newsletterData['href'] = $this->generateFrontendUrl($viewPage->row(), $params);
			}
			else {
				if (!isset($viewOnlineCache[$newsletter->pid])) {
					$category = \Database::getInstance()
						->prepare("SELECT * FROM orm_avisota_message_category WHERE id=?")
						->execute($newsletter->pid);

					if ($category->next()) {
						$viewOnlineCache[$newsletter->pid] = $this->Base->getViewOnlinePage($category);
					}
					else {
						$viewOnlineCache[$newsletter->pid] = false;
					}
				}
				if ($viewOnlineCache[$newsletter->pid]) {
					$newsletterData['href'] = $this->generateFrontendUrl(
						$viewOnlineCache[$newsletter->pid]->row(),
						$params
					);
				}
				else {
					$newsletterData['href'] = '';
				}
			}

			$newsletterDataSets[] = $newsletterData;
		}

		$template              = new FrontendTemplate($this->avisota_list_template);
		$template->newsletters = $newsletterDataSets;
		$this->Template->list     = $template->parse();

		$newsletter = \Database::getInstance()
			->prepare(
			"SELECT COUNT(id) as `count` FROM orm_avisota_message WHERE sendOn > 0 AND pid IN (" . implode(
				',',
				$this->avisota_categories
			) . ")"
		)
			->execute();

		$this->Template->limit = $limit;
		$this->Template->total = $newsletter->next() ? $newsletter->count : 0;
	}
}
