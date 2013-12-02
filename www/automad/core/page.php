<?php 
/*
 *	                  ....
 *	                .:   '':.
 *	                ::::     ':..
 *	                ::.         ''..
 *	     .:'.. ..':.:::'    . :.   '':.
 *	    :.   ''     ''     '. ::::.. ..:
 *	    ::::.        ..':.. .''':::::  .
 *	    :::::::..    '..::::  :. ::::  :
 *	    ::'':::::::.    ':::.'':.::::  :
 *	    :..   ''::::::....':     ''::  :
 *	    :::::.    ':::::   :     .. '' .
 *	 .''::::::::... ':::.''   ..''  :.''''.
 *	 :..:::'':::::  :::::...:''        :..:
 *	 ::::::. '::::  ::::::::  ..::        .
 *	 ::::::::.::::  ::::::::  :'':.::   .''
 *	 ::: '::::::::.' '':::::  :.' '':  :
 *	 :::   :::::::::..' ::::  ::...'   .
 *	 :::  .::::::::::   ::::  ::::  .:'
 *	  '::'  '':::::::   ::::  : ::  :
 *	            '::::   ::::  :''  .:
 *	             ::::   ::::    ..''
 *	             :::: ..:::: .:''
 *	               ''''  '''''
 *	
 *
 *	AUTOMAD CMS
 *
 *	Copyright (c) 2013 by Marc Anton Dahmen
 *	http://marcdahmen.de
 *
 *	Licensed under the MIT license.
 */


namespace Core;


defined('AUTOMAD') or die('Direct access not permitted!');

 
/**
 *	The Page class holds all properties and methods of a single page.
 *	A Page object describes an entry in the collection of all pages in the Site class.
 *	Basically the Site object consists of many Page objects.
 */


class Page {
	
	
	/**
	 * 	The $data array holds all the information stored as "key: value" in the text file.
	 *	
	 *	The key can be everything alphanumeric as long as there is a matching var set in the template files.
	 *	Out of all possible keys ther are two very special ones:
	 *
	 *	- "title": 				The title of the page - will also be used for sorting
	 *	- "tags" (or better AM_PARSE_TAGS_KEY): 	The tags (or what ever is set in the const.php) will be extracted and stored as an array in the main properties of that page 
	 *						The original string will remain in the $data array for seaching
	 */
	
	public $data = array();
	
	
	/**
	 * 	The $tags get also extracted from the text file (see $data).
	 */
	
	public $tags = array();
	
	
	/**
	 *	The relative URL of the page (PATH_INFO).
	 */
	
	public $url;
	
	
	/**
	 * 	The relative path in the file system.
	 */
	
	public $path;
	
	
	/**
	 * 	The level in the folder tree.
	 */
	
	public $level;
	
	
	/**
	 * 	The relative URL of the parent page.
	 */
	
	public $parentUrl;
	
	
	/**
	 *	The theme used to provide the template file.
	 */
	
	public $theme;
	
	
	/**
	 * 	The template used to render the page (just the filename of the text file without the suffix).
	 */
	
	public $template;
	

	/**
	 *	Return the theme path (root relative) of the page.
	 *
	 *	@return The path of the theme relative to the site's root.
	 */
	
	public function getTheme() {
		
		$themePath = AM_DIR_THEMES . '/' . $this->theme;
		
		if (!file_exists(AM_BASE_DIR . $themePath) || !$this->theme) {
			
			Debug::log('Page: Theme "' . $themePath . '" not found! Will try default template directory instead...');
			$themePath = AM_DIR_DEFAULT_TEMPLATES;
			
		}
		
		return $themePath;
		
	}
	

	/**
	 * 	Return the template of the page.
	 *
	 *	@return The full file system path of the template file.
	 */
	
	public function getTemplate() {
		
		$templatePath = AM_BASE_DIR . $this->getTheme() . '/' . $this->template . '.php';
		
		if (!file_exists($templatePath)) {
			
			Debug::log('Page: Template "' . $templatePath . '" not found! Will use default template instead!');
			$templatePath = AM_FILE_DEFAULT_TEMPLATE;
			
		}
	
		return $templatePath;
		
	}
	
	
	/**
	 *	Check if page is the current page.
	 *
	 *	@return boolean
	 */
	
	public function isCurrent() {
		
		if (isset($_SERVER["PATH_INFO"])) {
			$currentPath = '/' . trim($_SERVER["PATH_INFO"], '/');
		} else {
			$currentPath = '/';
		}
		
		if ($currentPath == $this->url) {
			return true;
		} else {
			return false;
		}
		
	}
	
	
	/**
	 *	Check if the page URL is a part the current page's URL.
	 *
	 *	@return boolean
	 */
	
	public function isInCurrentPath() {
		
		if (isset($_SERVER["PATH_INFO"])) {
			$currentPath = '/' . trim($_SERVER["PATH_INFO"], '/');
		} else {
			$currentPath = '/';
		}
		
		// Test if $currentPath starts with $this->url.
		// The trailing slash is very important ($this->url . '/'), since without that slash,
		// /path/to/page and /path/to/page-1 would both match a current URL like /path/to/page-1/subpage, 
		// while /path/to/page/ would not match.
		if (strpos($currentPath, $this->url . '/') === 0 && !$this->isCurrent()) {
			return true;
		} else {
			return false;
		}
		
	}
	
	
	/**
	 *	Check if page is the home page.
	 *
	 *	@return boolean
	 */
	
	public function isHome() {
		
		if ($this->url == '/') {
			return true;
		} else {
			return false;
		}
		
	}
	
} 
 
 
?>
