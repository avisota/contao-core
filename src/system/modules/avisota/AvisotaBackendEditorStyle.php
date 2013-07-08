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
 * Class AvisotaBackendEditorStyle
 *
 * InsertTag hook class.
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class AvisotaBackendEditorStyle extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
		$this->import('Input');
	}


	public function hookGetEditorStylesLayout($editor)
	{
		if ($editor == 'newsletter'
			&& $this->Input->get('do') == 'avisota_newsletter'
			&& $this->Input->get('table') == 'orm_avisota_message_content'
			&& $this->Input->get('act') == 'edit'
		) {
			$id = $this->Input->get('id');

			$newsletter = $this->Database
				->prepare(
				"
					SELECT
						n.*
					FROM
						`orm_avisota_message` n
					INNER JOIN
						`orm_avisota_message_content` c
					ON
						n.`id`=c.`pid`
					WHERE
						c.`id`=?"
			)
				->execute($id);

			$category = $this->Database
				->prepare(
				"
					SELECT
						*
					FROM
						`orm_avisota_message_category`
					WHERE
						`id`=?"
			)
				->execute($newsletter->pid);

			if ($category->viewOnlinePage > 0 && 0) {
				// the "view online" page does not contains the option to set a layout, use parent page instead
				$viewOnlinePage = $this->Database
					->prepare(
					"
						SELECT
							*
						FROM
							`tl_page`
						WHERE
							`id`=?"
				)
					->execute($category->viewOnlinePage);
				$page           = $this->getPageDetails($viewOnlinePage->pid);
			}
			elseif ($category->subscriptionPage > 0) {
				$page = $this->getPageDetails($category->subscriptionPage);
			}
			else {
				return false;
			}
			return $page->layout;
		}
		return false;
	}
}
