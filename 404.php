<?php get_header(); ?>

<div id="primary" class="single-post">
	<div class="inside">
		<div class="primary">
			<h1>Page not found</h1>
			<p>It looks like there's a problem with the page you're trying to get to. If you're looking for something in particular, try using the search form below, or by browsing the archives.</p>
			
			<h2>Search this site:</h2>
			<?php include (TEMPLATEPATH . '/searchform.php'); ?>
			
			<h2>Archives by month:</h2>
			<ul>
				<?php wp_get_archives('type=monthly'); ?>
			</ul>
	
			<h2>Archives by subject:</h2>
			<ul>
				 <?php wp_list_cats(); ?>
			</ul>
		</div>
		<div class="secondary">
			<h2>Why am I seeing this?</h2>
			<p>You requested a page that doesn't exist on this site any more. This could be caused by a link you followed that was out of date, by a typing in the wrong address in the address bar, or simply because the post has been deleted.</p>
		</div>
		<div class="clear"></div>
	</div>
</div>
<?php get_sidebar(); ?>

<?php get_footer(); ?>