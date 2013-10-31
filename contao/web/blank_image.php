<?php

/**
 * Contao Open Source CMS
 *
 * @copyright  MEN AT WORK 2013 
 * @package    avisota
 * @license    GNU/LGPL 
 * @filesource
 */

/**
 * Initialize the system
 */
$dir = dirname(isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : __FILE__);

while ($dir && $dir != '.' && $dir != '/' && !is_file($dir . '/system/initialize.php')) {
	$dir = dirname($dir);

}

if (!is_file($dir . '/system/initialize.php')) {
	header("HTTP/1.0 500 Internal Server Error");
	header('Content-Type: text/html; charset=utf-8');
	echo '<h1>500 Internal Server Error</h1>';
	echo '<p>Could not find initialize.php!</p>';
	exit(1);
}

define('TL_MODE', 'FE');
require($dir . '/system/initialize.php');


class blank_image
{
	public function create()
	{
		$im=imagecreatetruecolor(1,1);
		imagecolortransparent($im,imagecolorallocate($im,0,0,0));
		header('Content-type: image/png');
		imagepng($im);
		imagedestroy($im);
		exit();
	}
}

$blankImage = new blank_image();
$blankImage->create();
?>