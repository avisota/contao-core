<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class AvisotaRecipientSource
 *
 * 
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
interface AvisotaTransportModule
{
	/**
	 * Transport a single newsletter.
	 * Used by preview sending.
	 * 
	 * @param Newsletter $objNewsletter
	 *     The newsletter object to transport.
	 * @return void
	 */
	public function transportNewsletter(Newsletter $objNewsletter);
	
	
	/**
	 * Batch transport a newsletter queue.
	 * 
	 * @param int $intQueue
	 * ID of the outbox queue.
	 * 
	 * @return void
	 */
	public function batchTransport($intQueue);
}

?>