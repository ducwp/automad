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
 *	Copyright (c) 2014 by Marc Anton Dahmen
 *	http://marcdahmen.de
 *
 *	Licensed under the MIT license.
 */


namespace Core;


defined('AUTOMAD') or die('Direct access not permitted!');


/**
 *	Add an user.
 */


$output = array();


if (isset($_POST['username']) && $_POST['username'] && isset($_POST['password1']) && $_POST['password1'] && isset($_POST['password2']) && $_POST['password2']) {
	
	// Check if password1 equals password2.
	if ($_POST['password1'] == $_POST['password2']) {
		
		// Get all accounts from file.
		$accounts = unserialize(file_get_contents(AM_FILE_ACCOUNTS));
		
		// Check, if user exists already.
		if (!isset($accounts[$_POST['username']])) {
		
			// Add user to accounts array.
			$accounts[$_POST['username']] = $this->passwordHash($_POST['password1']);
			ksort($accounts);
			
			if (is_writable(AM_FILE_ACCOUNTS)) {
				
				// Write array with all accounts back to file.
				if (file_put_contents(AM_FILE_ACCOUNTS, serialize($accounts))) {
					$output['success'] = $this->tb['success_added'] . ' <strong>' . $_POST['username'] . '</strong>';
				}
				
			} else {
				
				$output['error'] = $this->tb['error_permission'];
				
			}
			
		} else {
		
			$output['error'] = '<strong>' . $_POST['username'] . '</strong> ' . $this->tb['error_existing'];	
			
		}
		
	} else {
		
		$output['error'] = $this->tb['error_form'];
		
	}
	
} else {
	
	$output['error'] = $this->tb['error_form'];
	
}


echo json_encode($output);


?>