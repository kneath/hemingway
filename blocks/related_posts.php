<h2>Related Entries</h2>
<?php if ( function_exists('related_posts') ) { ?> 
<ul class="pages">
	<?php related_posts(); ?>
</ul>
<?php } else { ?>
<p>This block requires the <a href="http://www.w-a-s-a-b-i.com/archives/2006/02/02/wordpress-related-entries-20/">Related Entries Plugin</a>.</p>
<?php } ?>
<?php
/*
	 Props to http://freshnecessity.net/
*/ 
?>