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

namespace Avisota\Contao\Core\Queue;

use Avisota\Contao\Entity\Queue;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractQueueWebRunner extends \Backend
{
    function __construct()
    {
        parent::__construct();
    }

    public function run(Request $request)
    {
        $queueRepository = \Contao\Doctrine\ORM\EntityHelper::getRepository('Avisota\Contao:Queue');

        $queueId = $request->get('id');
        $queue   = $queueRepository->find($queueId);
        /** @var \Avisota\Contao\Entity\Queue $queue */

        if (!$queue) {
            header("HTTP/1.0 404 Not Found");
            echo '<h1>404 Not Found</h1>';
            exit;
        }

        $user = \BackendUser::getInstance();
        $user->authenticate();

        try {
            return $this->execute($request, $queue, $user);
        } catch (\Exception $exception) {
            $response = new JsonResponse(
                array(
                    'error' => sprintf(
                        '%s in %s:%d',
                        $exception->getMessage(),
                        $exception->getFile(),
                        $exception->getLine()
                    )
                ),
                500
            );
            $response->prepare($request);
            return $response;
        }
    }

    abstract protected function execute(Request $request, Queue $messageData, \BackendUser $user);
}
