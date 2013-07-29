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

namespace Avisota\Contao;

use Avisota\Contao\Entity\MessageCategory;
use Contao\Doctrine\ORM\EntityHelper;
use Doctrine\ORM\EntityRepository;

/**
 * Class Backend
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class Backend extends \Controller
{
	/**
	 * @var Backend
	 */
	protected static $instance = null;

	/**
	 * @static
	 * @return Backend
	 */
	public static function getInstance()
	{
		if (self::$instance === null) {
			self::$instance = new Backend();
		}
		return self::$instance;
	}

	protected function __construct()
	{
		parent::__construct();
	}

	public function regenerateDynamics()
	{
		$dynamics = array();

		// $mailingListRepository     = EntityHelper::getRepository('Avisota\Contao:MailingList');
		$recipientSourceRepository = EntityHelper::getRepository('Avisota\Contao:RecipientSource');
		// $themeRepository           = EntityHelper::getRepository('Avisota\Contao:Theme');
		$transportRepository       = EntityHelper::getRepository('Avisota\Contao:Transport');
		$queueRepository           = EntityHelper::getRepository('Avisota\Contao:Queue');

		/** @var EntityRepository[] $repositories */
		$repositories = array(
			// $mailingListRepository,
			$queueRepository,
			$recipientSourceRepository,
			// $themeRepository,
			$transportRepository,
		);

		foreach ($repositories as $repository) {
			$entityName = $repository->getClassName();
			$entityName = str_replace('Avisota\\Contao\\Entity\\', '', $entityName);
			$entityName = lcfirst($entityName);
			$entities = $repository->findAll();

			$dynamics[$entityName] = array();
			foreach ($entities as $entity) {
				$dynamics[$entityName][] = array(
					'id'    => $entity->id(),
					'alias' => $entity->getAlias(),
					'title' => $entity->getTitle(),
				);
			}
		}

		$queryBuilder = EntityHelper::getEntityManager()
			->createQueryBuilder();
		$query        = $queryBuilder
			->select('c')
			->from('Avisota\Contao:MessageCategory', 'c')
			->where('c.showInMenu=:showInMenu')
			->andWhere('c.boilerplates=:boilerplates')
			->setParameter(':showInMenu', true)
			->setParameter(':boilerplates', false)
			->orderBy('c.title')
			->getQuery();
		$categories   = $query->getResult();

		$dynamics['category'] = array();
		/** @var MessageCategory $category */
		foreach ($categories as $category) {
			$dynamics['category'][] = array(
				'id'    => $category->id(),
				'label' => $category->getTitle(),
				'icon'  => $category->getUseCustomMenuIcon() ? $category->getMenuIcon() : false
			);
		}

		$array = var_export($dynamics, true);

		$fileContents = <<<EOF
<?php

return $array;


EOF;

		$tempFile = new \File('system/modules/avisota/config/dynamics.php');
		$tempFile->write($fileContents);
		$tempFile->close();
	}
}
