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
 * Class NewsletterGallery
 *
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota/contao-core
 */
class NewsletterGallery extends Element
{

	/**
	 * HTML Template
	 *
	 * @var string
	 */
	protected $templateHTML = 'nle_gallery_html';

	/**
	 * Plain text Template
	 *
	 * @var string
	 */
	protected $templatePlain = 'nle_gallery_plain';


	/**
	 * Parse the html template
	 *
	 * @return string
	 */
	public function generateHTML()
	{
		$this->multiSRC = deserialize($this->multiSRC);

		if (!is_array($this->multiSRC) || count($this->multiSRC) < 1) {
			return '';
		}

		return parent::generateHTML();
	}


	/**
	 * Parse the plain text template
	 *
	 * @return string
	 */
	public function generatePlain()
	{
		$this->multiSRC = deserialize($this->multiSRC);

		if (!is_array($this->multiSRC) || count($this->multiSRC) < 1) {
			return '';
		}

		return parent::generatePlain();
	}


	/**
	 * Compile the current element
	 */
	protected function compile($mode)
	{
		$images  = array();
		$auxDate = array();

		// Get all images
		foreach ($this->multiSRC as $pathname) {
			if (isset($images[$pathname]) || !file_exists(TL_ROOT . '/' . $pathname)) {
				continue;
			}

			// Single files
			if (is_file(TL_ROOT . '/' . $pathname)) {
				$file = new File($pathname);
				$this->parseMetaFile(dirname($pathname), true);
				$metaData = $this->arrMeta[$file->basename];

				if ($metaData[0] == '') {
					$metaData[0] = str_replace('_', ' ', preg_replace('/^[0-9]+_/', '', $file->filename));
				}

				if ($file->isGdImage) {
					$images[$pathname] = array
					(
						'name'      => $file->basename,
						'singleSRC' => $pathname,
						'alt'       => $metaData[0],
						'imageUrl'  => $metaData[1],
						'caption'   => $metaData[2]
					);

					$auxDate[] = $file->mtime;
				}

				continue;
			}

			$subfiles = scan(TL_ROOT . '/' . $pathname);
			$this->parseMetaFile($pathname);

			// Folders
			foreach ($subfiles as $subfile) {
				if (is_dir(TL_ROOT . '/' . $pathname . '/' . $subfile)) {
					continue;
				}

				$file = new File($pathname . '/' . $subfile);

				if ($file->isGdImage) {
					$metaData = $this->arrMeta[$subfile];

					if ($metaData[0] == '') {
						$metaData[0] = str_replace('_', ' ', preg_replace('/^[0-9]+_/', '', $file->filename));
					}

					$images[$pathname . '/' . $subfile] = array
					(
						'name'      => $file->basename,
						'singleSRC' => $pathname . '/' . $subfile,
						'alt'       => $metaData[0],
						'imageUrl'  => $metaData[1],
						'caption'   => $metaData[2]
					);

					$auxDate[] = $file->mtime;
				}
			}
		}

		// Sort array
		switch ($this->sortBy) {
			default:
			case 'name_asc':
				uksort($images, 'basename_natcasecmp');
				break;

			case 'name_desc':
				uksort($images, 'basename_natcasercmp');
				break;

			case 'date_asc':
				array_multisort($images, SORT_NUMERIC, $auxDate, SORT_ASC);
				break;

			case 'date_desc':
				array_multisort($images, SORT_NUMERIC, $auxDate, SORT_DESC);
				break;

			case 'meta':
				$images = array();
				foreach ($this->arrAux as $k) {
					if (strlen($k)) {
						$images[] = $images[$k];
					}
				}
				break;

			case 'random':
				shuffle($images);
				break;
		}

		$images = array_values($images);
		$total  = count($images);
		$limit  = $total;
		$offset = 0;

		$rowcount = 0;
		if (!$this->perRow) {
			$this->perRow = 1;
		}
		$colwidth    = floor(100 / $this->perRow);
		$maxImageWidth = (TL_MODE == 'BE')
			? floor((640 / $this->perRow))
			: floor(
				($GLOBALS['TL_CONFIG']['maxImageWidth'] / $this->perRow)
			);
		$body        = array();

		// Rows
		for ($i = $offset; $i < $limit; $i = ($i + $this->perRow)) {
			$class_tr = '';

			if ($rowcount == 0) {
				$class_tr = ' row_first';
			}

			if (($i + $this->perRow) >= count($images)) {
				$class_tr = ' row_last';
			}

			$class_eo = (($rowcount % 2) == 0) ? ' even' : ' odd';

			// Columns
			for ($j = 0; $j < $this->perRow; $j++) {
				$class_td = '';

				if ($j == 0) {
					$class_td = ' col_first';
				}

				if ($j == ($this->perRow - 1)) {
					$class_td = ' col_last';
				}

				$cell = new stdClass();
				$key     = 'row_' . $rowcount . $class_tr . $class_eo;

				// Empty cell
				if (!is_array($images[($i + $j)]) || ($j + $i) >= $limit) {
					$cell->class = 'col_' . $j . $class_td;
					$body[$key][$j] = $cell;

					continue;
				}

				// Add size and margin
				$images[($i + $j)]['size']        = $this->size;
				$images[($i + $j)]['imagemargin'] = $this->imagemargin;
				$images[($i + $j)]['fullsize']    = $this->fullsize;

				$this->addImageToTemplate($cell, $images[($i + $j)], $maxImageWidth);

				// Add column width and class
				$cell->colWidth = $colwidth . '%';
				$cell->class    = 'col_' . $j . $class_td;

				$body[$key][$j] = $cell;
			}

			++$rowcount;
		}

		switch ($mode) {
			case NL_HTML:
				$templateName = 'nl_gallery_default_html';

				// Use a custom template
				if (TL_MODE == 'NL' && $this->galleryHtmlTpl != '') {
					$templateName = $this->galleryHtmlTpl;
				}
				break;

			case NL_PLAIN:
				$templateName = 'nl_gallery_default_plain';

				// Use a custom template
				if (TL_MODE == 'NL' && $this->galleryPlainTpl != '') {
					$templateName = $this->galleryPlainTpl;
				}
		}

		$template = new AvisotaNewsletterTemplate($templateName);

		$template->body     = $body;
		$template->headline = $this->headline;

		$this->Template->images = $template->parse();
	}
}
