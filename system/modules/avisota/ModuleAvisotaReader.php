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
 * Class ModuleAvisotaReader
 *
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 */
class ModuleAvisotaReader extends Module
{
	/**
	 * @var AvisotaNewsletterContent
	 */
	protected $AvisotaNewsletterContent;

	/**
	 * Template
	 *
	 * @var string
	 */
	protected $strTemplate = 'mod_avisota_reader';


	/**
	 * Construct the content element
	 */
	public function __construct(Database_Result $module)
	{
		parent::__construct($module);
		$this->import('DomainLink');
		$this->import('FrontendUser', 'User');
		$this->import('AvisotaNewsletterContent');
		$this->loadLanguageFile('avisota');
	}


	/**
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE') {
			$template = new BackendTemplate('be_wildcard');
			$template->wildcard = '### Avisota Newsletter Reader ###';
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

		$newsletter = \Database::getInstance()
			->prepare(
			"SELECT * FROM orm_avisota_message WHERE (id=? OR alias=?) AND pid IN (" . implode(
				',',
				$this->avisota_categories
			) . ")"
		)
			->execute($id, $id);

		if ($newsletter->next()) {
			$category = \Database::getInstance()
				->prepare("SELECT * FROM orm_avisota_message_category WHERE id=?")
				->execute($newsletter->pid);
			if ($category->next()) {
				$newsletter->template_html = $this->avisota_reader_template;

				$this->Template->newsletter = $newsletter->row();
				$this->Template->html       = $this->AvisotaNewsletterContent->generateHtml(
					$newsletter,
					$category,
					false
				);
			}
		}
	}
}
