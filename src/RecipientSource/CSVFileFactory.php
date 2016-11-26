<?php

/**
 * Avisota newsletter and mailing system
 * Copyright © 2016 Sven Baumann
 *
 * PHP version 5
 *
 * @copyright  way.vision 2016
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Contao\Core\RecipientSource;

use Avisota\Contao\Core\CoreEvents;
use Avisota\Contao\Core\Event\CreateRecipientSourceEvent;
use Avisota\Contao\Entity\RecipientSource;
use Avisota\RecipientSource\CSVFile;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * The cs file factory.
 */
class CSVFileFactory implements RecipientSourceFactoryInterface
{
    /**
     * Create the recipient source.
     *
     * @param RecipientSource $recipientSourceData The recipient source data.
     *
     * @return \Avisota\RecipientSource\RecipientSourceInterface
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    public function createRecipientSource(RecipientSource $recipientSourceData)
    {
        $file      = \Compat::resolveFile($recipientSourceData->getCsvFileSrc());
        $columns   = array();
        $delimiter = $recipientSourceData->getCsvFileDelimiter();
        $enclosure = $recipientSourceData->getCsvFileEnclosure();

        foreach ($recipientSourceData->getCsvColumnAssignment() as $item) {
            $columns[$item['column'] - 1] = $item['field'];
        }

        switch ($delimiter) {
            case 'semicolon':
                $delimiter = ';';
                break;
            case 'space':
                $delimiter = ' ';
                break;
            case 'tabulator':
                $delimiter = "\t";
                break;
            case 'linebreak':
                $delimiter = "\n";
                break;
            default:
                $delimiter = ',';
        }

        switch ($enclosure) {
            case 'single':
                $enclosure = "'";
                break;
            default:
                $enclosure = '"';
        }

        $recipientSourceCSV = new CSVFile(TL_ROOT . DIRECTORY_SEPARATOR . $file, $columns, $delimiter, $enclosure);

        /** @var EventDispatcherInterface $eventDispatcher */
        $eventDispatcher = $GLOBALS['container']['event-dispatcher'];

        $event = new CreateRecipientSourceEvent($recipientSourceData, $recipientSourceCSV);
        $eventDispatcher->dispatch(CoreEvents::CREATE_RECIPIENT_SOURCE, $event);

        return $event->getRecipientSource();
    }
}
