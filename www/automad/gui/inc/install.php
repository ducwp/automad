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
 *	AUTOMAD
 *
 *	Copyright (c) 2014-2017 by Marc Anton Dahmen
 *	http://marcdahmen.de
 *
 *	Licensed under the MIT license.
 *	http://automad.org/license
 */


namespace Automad\GUI;


defined('AUTOMAD') or die('Direct access not permitted!');


/*
 *	As part of the GUI, this file is only to be included via the GUI class.
 * 	The installer creates a file called "accounts.txt" to be installed in /config.
 */


$error = Accounts::install();


$this->guiTitle = $this->guiTitle . ' / ' . Text::get('install_title');
$this->element('header');


?>

		<div class="uk-width-medium-1-2 uk-container-center uk-margin-top">
			<div class="uk-animation-fade">
				<div class="uk-panel uk-panel-box uk-panel-box-primary uk-margin-bottom">
					<div class="uk-panel-title">
						<i class="uk-icon-user uk-icon-medium"></i>
					</div>
					<?php Text::e('install_help'); ?>
				</div>
				<form class="uk-form" method="post">
					<input class="uk-form-controls uk-form-large uk-width-1-1 uk-margin-bottom" type="text" name="username" placeholder="<?php Text::e('sys_user_add_name'); ?>" />
					<input class="uk-form-controls uk-width-1-1 uk-margin-small-bottom" type="password" name="password1" placeholder="<?php Text::e('sys_user_add_password'); ?>" />
					<input class="uk-form-controls uk-width-1-1 uk-margin-bottom" type="password" name="password2" placeholder="<?php Text::e('sys_user_add_repeat'); ?>" />
					<div class="uk-text-right">
						<button type="submit" class="uk-button uk-button-primary" data-uk-toggle="{target:'.uk-animation-fade'}">
							<i class="uk-icon-download"></i>&nbsp;&nbsp;<?php Text::e('btn_accounts_file'); ?>
						</button>
					</div>
				</form>
			</div>
			<div class="uk-animation-fade uk-hidden">
				<div class="uk-panel uk-panel-box uk-panel-box-primary uk-margin-bottom">
					<div class="uk-panel-title">
						<i class="uk-icon-cloud-upload uk-icon-medium"></i>
					</div>
					<?php Text::e('install_login'); ?>
				</div>
				<div class="uk-text-right">
					<a href="" class="uk-button uk-button-primary"><?php Text::e('btn_login'); ?>&nbsp;&nbsp;<i class="uk-icon-sign-in"></i></a>
				</div>
			</div>
		</div>		
		<?php if (!empty($error)) { ?>
		<script type="text/javascript">
			Automad.notify.error("<?php echo $error; ?>");
			$('form input').first().focus();
		</script>	
		<?php } ?>

<?php


$this->element('footer');


?>