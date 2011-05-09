<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

#copyright


/**
 * Class PHPTransportModule
 *
 * 
 * @copyright  InfinitySoft 2010
 * @author     Tristan Lins <tristan.lins@infinitysoft.de>
 * @package    Avisota
 */
class PHPTransportModule
{
	protected $arrConfig;
	
	
	public function __construct($arrConfig)
	{
		$this->arrConfig = $arrConfig;
	}
	
	
	/**
	 * (non-phpdoc)
	 * @see AvisotaTransportModule::transportNewsletter
	 */
	public function transportNewsletter(Newsletter $objNewsletter)
	{
		// Generate the email object
		$objEmail = new ExtendedEmail();

		// Add sender name
		$objEmail->from = strlen($this->arrConfig['sender']) ? $this->arrConfig['sender'] : $GLOBALS['TL_CONFIG']['adminEmail'];
		if (strlen($this->arrConfig['senderName']))
		{
			$objEmail->fromName = $this->arrConfig['senderName'];
		}

		// Set basics
		$objEmail->subject = $objNewsletter->subject;
		$objEmail->logFile = 'newsletter_' . $objNewsletter->id . '.log';

		// Prepare text content
		$objEmail->text = $objNewsletter->getContentPlain();

		// Prepare html content
		$objEmail->html = $objNewsletter->getContentHtml();
		$objEmail->imageDir = TL_ROOT . '/';
		
		// Attachments
		if ($objNewsletter->addFile)
		{
			$files = deserialize($objNewsletter->files);

			if (is_array($files) && count($files) > 0)
			{
				foreach ($files as $file)
				{
					if (is_file(TL_ROOT . '/' . $file))
					{
						$objEmail->attachFile(TL_ROOT . '/' . $file);
					}
				}
			}
		}

		$blnFailed = false;
		
		// Deactivate invalid addresses
		try
		{
			if ($GLOBALS['TL_CONFIG']['avisota_developer_mode'])
			{
				$objEmail->sendTo($GLOBALS['TL_CONFIG']['avisota_developer_email']);
			}
			else
			{
				$objEmail->sendTo($objNewsletter->recipient->email);
			}
		}
		catch (Swift_RfcComplianceException $e)
		{
			$blnFailed = true;
		}

		// Rejected recipients
		if (count($objEmail->failures))
		{
			$blnFailed = true;
		}
		
		return !$blnFailed;
	}
	
	
	/**
	 * Batch transport a newsletter queue.
	 * 
	 * @param int $intQueue
	 * ID of the outbox queue.
	 * 
	 * @return void
	 */
	public function batchTransport($intQueue)
	{
		// TODO
	}
}

?>