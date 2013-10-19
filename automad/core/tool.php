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


/**
 * 	The Tool class holds all methods to be used within the template files.
 */


class Tool {
	

	/**
	 * 	Site object.
	 */
	
	private $S;
	
	
	/**
	 *	The full collection of pages.
	 */
	
	private $collection;
	
	
	/**
	 * 	Current Page object.
	 */
	
	private $P;
	
	
	/**
	 * 	The modus defines the way a selection of pages gets sortet.
	 *	
	 *	Default is empty, to make sure the original order is kept when leaving out $this->sortBy().
	 */
	
	private $sortType = '';
	
	
	/**
	 * 	Sort order for selections.
	 *
	 *	Default is SORT_ASC, to make sure the original order is kept when leaving out $this->sortAscending().
	 */
	
	private $sortDirection = SORT_ASC;
	
	
	/**
	 * 	The Site object is passed as an argument. It shouldn't be created again (performance).
	 */
		
	public function __construct($site) {
		
		$this->S = $site;
		$this->collection = $this->S->getCollection();
		$this->P = $this->S->getCurrentPage();
		
	}
	
	
	/**
	 *	Place a set of the current page's tags and link back to the parent page passing each tag as a filter.
	 *
	 *	@return the HTML of the filters
	 */

	public function filterParentByTags() {
		
		return Html::generateFilterMenu($this->P->tags, BASE_URL . $this->P->parentRelUrl);
		
	}
	
	
	/**
	 *	To place the homepage at the same level like all the other pages from the first level,
	 *	includeHome() will modify $this->collection and move the homepage one level down: 0 -> 1
	 */
	
	public function includeHome() {
		
		$selection = new Selection($this->collection);
		$selection->makeHomePageFirstLevel();
		$this->collection = $selection->getSelection();
		
	}


	/**
	 * 	Return the HTML for a list of all pages excluding the current page.
	 *	The variables to be included in the output are set in a comma separated parameter string ($optionStr).
	 *
	 *	@param string $optionStr - All variables from the page's text file which should be included in the output. Expample: $[listAll(title, subtitle, date)]
	 *	@return the HTML of the list
	 */
	
	public function listAll($optionStr) {
	
		$selection = new Selection($this->collection);	
	
		if (isset($_GET['filter'])) {
			$selection->filterByTag($_GET['filter']);
		}
		
		if (isset($_GET['sort_type'])) {
			$this->sortType = $_GET['sort_type'];
		}
		
		if (isset($_GET['sort_dir'])) {
			$this->sortDirection = constant(strtoupper($_GET['sort_dir']));
		}
	
		$selection->sortPages($this->sortType, $this->sortDirection);
	
		$pages = $selection->getSelection();
	
		// Remove current page from selecion
		unset($pages[$this->P->relUrl]);
	
		return Html::generateList($pages, $optionStr);	
		
	}

	
	/**
	 * 	Return the HTML for a list of pages below the current page.
	 *	The variables to be included in the output are set in a comma separated parameter string ($optionStr).
	 *
	 *	@param string $optionStr - All variables from the page's text file which should be included in the output. Expample: $[listAll(title, subtitle, date)]
	 *	@return the HTML of the list
	 */
	
	public function listChildren($optionStr) {
		
		$selection = new Selection($this->collection);
		$selection->filterByParentUrl($this->P->relUrl);
		
		if (isset($_GET['filter'])) {
			$selection->filterByTag($_GET['filter']);
		}
		
		if (isset($_GET['sort_type'])) {
			$this->sortType = $_GET['sort_type'];
		}
		
		if (isset($_GET['sort_dir'])) {
			$this->sortDirection = constant(strtoupper($_GET['sort_dir']));
		}
		
		$selection->sortPages($this->sortType, $this->sortDirection);
		
		return Html::generateList($selection->getSelection(), $optionStr);
		
	}
	

	/**
	 *	Place a set of all tags (sitewide) to filter the full page list. The filter only affects lists of pages created by Tool::listAll()
	 *
	 *	This method should be used together with the listAll() method.
	 *
	 *	@return the HTML of the filter menu
	 */
	
	public function menuFilterAll() {
		
		$selection = new Selection($this->collection);
		$pages = $selection->getSelection();
		
		// Remove current page from selecion
		unset($pages[$this->P->relUrl]);
		
		$tags = array();
		
		foreach ($pages as $page) {
			
			$tags = array_merge($tags, $page->tags);
			
		}
		
		$tags = array_unique($tags);
		sort($tags);
		
		return Html::generateFilterMenu($tags);
		
	}


	/**
	 *	Place a set of all tags included in the children pages to filter the list of children pages. The filter only affects lists of pages created by Tool::listChildren()
	 *
	 *	This method should be used together with the listChildren() method.
	 *
	 *	@return the HTML of the filter menu
	 */
	
	public function menuFilterChildren() {
		
		$selection = new Selection($this->collection);
		$selection->filterByParentUrl($this->P->relUrl);
		$pages = $selection->getSelection();
		
		$tags = array();
		
		foreach ($pages as $page) {
			
			$tags = array_merge($tags, $page->tags);
			
		}
		
		$tags = array_unique($tags);
		sort($tags);
		
		return Html::generateFilterMenu($tags);
		
	}

	
	/**
	 *	Place a menu to select the sort direction. The menu only affects lists of pages created by Tool::listChildren() and Tool::listAll()
	 *
	 *	@param string $optionStr (optional) - Example: $[menuSortDirection(SORT_ASC: Up, SORT_DESC: Down)] 
	 *	@return the HTML for the sort menu
	 */
	
	public function menuSortDirection($optionStr) {
		
		// $this->sortDirection gets passed as well to let Html know what flag is set to apply the correct "current" class to the HTML tag
		return Html::generateSortDirectionMenu($this->sortDirection, $optionStr);
		
	}
		

	/**
	 *	Place a set of sort options. The menu only affects lists of pages created by Tool::listChildren() and Tool::listAll()
	 *
	 *	@param string $optionStr (optional) - Example: $[menuSortType(Original, title: Title, date: Date, variablename: Title ...)]  
	 *	@return the HTML for the sort menu
	 */

	public function menuSortType($optionStr) {
		
		return Html::generateSortTypeMenu($optionStr);
		
	}

	
	/**
	 *	Generate a list for the navigation below a given URL.
	 *
	 *	@param string $parentRelUrl
	 *	@return html of the generated list	
	 */
	
	public function navBelow($parentUrl) {
				
		$selection = new Selection($this->collection);
		$selection->filterByParentUrl($parentUrl);
		$selection->sortPagesByPath();
		
		return Html::generateNav($selection->getSelection());
		
	}
	

	/**
	 * 	Generate breadcrumbs to current page.
	 *
	 *	@return html of breadcrumb navigation
	 */
	
	public function navBreadcrumbs() {
		
		$pages = array();
		$urlSegments = explode('/', $this->P->relUrl);
		$urlSegments = array_unique($urlSegments);
		$tempUrl = '';
		
		foreach ($urlSegments as $urlSegment) {
			
			$tempUrl = '/' . trim($tempUrl . '/' . $urlSegment, '/');
			$pages[] = $this->S->getPageByUrl($tempUrl); 
			
		}
		
		return Html::generateBreadcrumbs($pages);
		
	}
	
		
	/**
	 *	Generate a list for the navigation below the current page.
	 *
	 *	@return html of the generated list	
	 */
	
	public function navChildren() {
	
		return $this->navBelow($this->P->relUrl);
		
	}
	
	
	/**
	 *	Generate a seperate navigation menu for each level within the current path.
	 *
	 *	@return the HTML for the seperate navigations
	 */
	
	public function navPerLevel() {
		
		$urlSegments = explode('/', $this->P->relUrl);
		$urlSegments = array_unique($urlSegments);
		$tempUrl = '';
		$html = '';
				
		foreach ($urlSegments as $urlSegment) {
			
			$tempUrl = '/' . trim($tempUrl . '/' . $urlSegment, '/');	
			$html .= $this->navBelow($tempUrl);
					
		}
		
		return $html;
		
	}
	
	
	/**
	 *	Generate a list for the navigation below the current page's parent.
	 *
	 *	@return html of the generated list	
	 */
	
	public function navSiblings() {
		
		return $this->navBelow($this->P->parentRelUrl);
		
	}
	
	
	/**
	 *	Generate a list for the navigation at the top level including the home page (level 0 & 1).
	 *
	 *	@return html of the generated list	
	 */
	
	public function navTop() {
	
		$selection = new Selection($this->collection);
		$selection->filterByParentUrl('/');
		$selection->sortPagesByPath();
		
		return Html::generateNav($selection->getSelection());
		
	}
	
	
	/**
	 * 	Generate full navigation tree.
	 *
	 *	@return the HTML of the tree
	 */
	
	public function navTree() {
				
		return Html::generateTree($this->collection);
	
	}
	
	
	/**
	 * 	Generate navigation tree expanded only along the current page's path.
	 *
	 *	@return the HTML of the tree
	 */
	
	public function navTreeCurrent() {
				
		return Html::generateTree($this->collection, false);
	
	}

	
	/**
	 * 	Generate a list of pages having at least one tag in common with the current page.
	 *
	 *	@param string $optionStr - Variables from the text files to be included in the output. Example: $[relatedPages(title, date)]
	 *	@return html of the generated list
	 */
	
	public function relatedPages($optionStr) {
		
		$pages = array();
		$tags = $this->P->tags;
		
		// Get pages
		foreach ($tags as $tag) {
			
			$selection = new Selection($this->collection);
			$selection->filterByTag($tag);			
			$pages = array_merge($pages, $selection->getSelection());
						
		}
		
		// Remove current page from selecion
		unset($pages[$this->P->relUrl]);
		
		// Sort pages
		$selection = new Selection($pages);
		$selection->sortPagesByPath();
		
		return Html::generateList($selection->getSelection(), $optionStr);
				
	}
	
		
	/**
	 * 	Place a search field with placeholder text.
	 *
	 *	@param string $optionStr (optional) - placeholder text
	 *	@return the HTML of the searchfield
	 */
	
	public function searchField($optionStr) {
		
		$targetUrl = BASE_URL . $this->S->getSiteData('resultsPageUrl');
		
		return Html::generateSearchField($targetUrl, $optionStr);
		
	}

	
	/**
	 * 	Generate a list of search results.
	 *
	 *	@param string $optionStr (optional) - Variables from the text files to be included in the output. Example: $[searchResults(title, date)]
	 *	@return the HTML for the results list
	 */
	
	public function searchResults($optionStr) {
		
		if (isset($_GET["search"])) {
			
			$search = $_GET["search"];
			
			$selection = new Selection($this->collection);
			$selection->filterByKeywords($search);
			$selection->sortPagesByPath();
			
			$pages = $selection->getSelection();
			
			return Html::generateList($pages, $optionStr);
			
		}
		
	}
	
	
	/**
	 * 	Resets the sort mode to the original file system order. This method only affects following lists of pages created by Tool::listChildren() and Tool::listAll()
	 *	
	 *	If Selection::sortPages() gets passed an empty variable as mode, it will fall back to Selection::sortPagesByPath().
	 */
	
	public function sortOriginal() {
		
		// To prevent that the user selection passed in the query string gets overwritten by this method,
		// the $_GET array gets first checked, if sort_type is empty.
		if (!isset($_GET['sort_type'])) {
			$this->sortType = NULL;
		}
		
	}
	
	
	/**
	 * 	Sets the $key in Page::data[$key] as the sort type. This method only affects following lists of pages created by Tool::listChildren() and Tool::listAll()
	 *
	 *	@param string $optionStr - Any variable from the text file of the page - will be used as the new sort type
	 */
	
	public function sortBy($optionStr) {
		
		// To prevent that the user selection passed in the query string gets overwritten by this method,
		// the $_GET array gets first checked, if sort_type is empty.
		if (!isset($_GET['sort_type'])) {	
			$this->sortType = $optionStr;
		}
		
	}
	
	
	/**
	 * 	Sets the sort order to ascending. This method only affects following lists of pages created by Tool::listChildren() and Tool::listAll()
	 */
	
	public function sortAscending() {
		
		// To prevent that the user selection passed in the query string gets overwritten by this method,
		// the $_GET array gets first checked, if sort_dir is empty.
		if (!isset($_GET['sort_dir'])) {
			$this->sortDirection = SORT_ASC;
		}
		
	}
	
	
	/**
	 * 	Sets the sort order to descending. This method only affects following lists of pages created by Tool::listChildren() and Tool::listAll()
	 */
	
	public function sortDescending() {
		
		// To prevent that the user selection passed in the query string gets overwritten by this method,
		// the $_GET array gets first checked, if sort_dir is empty.
		if (!isset($_GET['sort_dir'])) {
			$this->sortDirection = SORT_DESC;
		}
		
	}
	

	/**
	 * 	Return the site name.
	 *
	 *	@return site name
	 */
	
	public function siteName() {
		
		return $this->S->getSiteName();
		
	}
	
	
	/**
	 * 	Return the URL of the page theme.
	 *
	 *	@return page theme URL
	 */
	
	public function themeURL() {
		
		return str_replace(BASE_DIR, BASE_URL, $this->S->getThemePath());
		
	}
	
	
}


?>
