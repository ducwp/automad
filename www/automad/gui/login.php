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
 *	The GUI Login Page. As part of the GUI, this file is only to be included via the GUI class.
 */


if ($_POST) {
	
	$username = $_POST['username'];
	$password = $_POST['password'];
	$accounts = unserialize(file_get_contents(AM_FILE_ACCOUNTS));
	
	if (isset($accounts[$username]) && $this->passwordVerified($password, $accounts[$username])) {
		
		$_SESSION['username'] = $username;
		header('Location: http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
		die;
		
	} else {
		
		$error = $this->tb['error_login'];
		
	}
		
}


$this->guiTitle = $this->guiTitle . ' / ' . $this->tb['login_title'];
$this->element('header');


?>

	<div class="row">
	
		<div class="col-md-4 col-md-offset-4">
			
			<?php $this->element('title'); ?>
			
			<?php if (isset($error)) { ?><div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><?php echo $error; ?></div><?php } ?>
			
      			<form role="form" method="post">
				
				<div class="list-group">
      
					<div class="list-group-item">
						<div class="form-group">
							<label for="username" class="text-muted">Username</label>
							<input id="username" class="form-control" type="text" name="username" placeholder="Username" />
						</div>
						<div class="form-group">
							<label for="password" class="text-muted">Password</label>	
							<input id="password" class="form-control" type="password" name="password" placeholder="Password" />
						</div>		
					</div>
					
					<div class="list-group-item clearfix">
						<div class="pull-right">
							<button type="submit" class="btn btn-primary"><?php echo $this->tb['btn_login']; ?></button>
						</div>
					</div>
				
				</div>
				
			</form>

		</div>

	</div>	
					
<?php


$this->element('footer');


?>		