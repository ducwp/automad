<?php defined('AUTOMAD') or die('Direct access not permitted!'); ?>

<@~ if @{ :pagelistCount } > 2 ~@>
	<@ set { 
		:container: 'am-stretched masonry uk-grid uk-grid-width-small-1-2 uk-grid-width-large-1-3'
	} @>
<@~ else ~@>
	<@ set { 
		:container: 'uk-grid uk-grid-width-small-1-2'
	} @>
<@~ end ~@>

<ul class="@{ :container }">
	<@ foreach in pagelist ~@>
		<li>
			<div class="uk-panel uk-panel-box uk-height-1-1">
				<@~ ../../snippets/set_imageteaser_variable.php @>
				<@~ if @{ :imageTeaser } @>
					<div class="uk-panel-teaser">
						<a href="@{ url }"><img src="@{ :imageTeaser }"></a>
					</div>
				<@~ end @>
				<div class="uk-panel-title uk-margin-bottom-remove">
					<a href="@{ url }">@{ title }</a>
					<div class="text-subtitle">
						<@ ../../snippets/date.php @>
						<@ if @{ date } and @{ tags } @><br><@ end @>
						<@ ../../snippets/tags.php @>
					</div>
				</div>
				<@ more.php @>
			</div>
		</li>
	<@ else @>
		<li>
			<h4>@{ notificationNoSearchResults | def ('No Pages Found') }</h4>
		</li>
	<@~ end @>
</ul>