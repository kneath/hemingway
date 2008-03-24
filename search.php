<?php get_header(); ?>

	<div id="primary" class="single-post">
	<div class="inside">
		<div class="primary">

	<?php if (have_posts()) : ?>

		<h1>Search Results</h1>
		
		<ul class="dates">
		 	<?php while (have_posts()) : the_post(); ?>
			<li>
				<span class="date"><?php the_time( $hemingway->date_format() . '.y' ) ?></span>
				<a href="<?php the_permalink() ?>"><?php the_title(); ?></a> 
				 <?php _e('posted in') ?> 
				<?php the_category(', ') ?>  
			</li>
			<?php $results++; ?>
			<?php endwhile; ?>
		</ul>
		
		<div class="navigation">
			<div class="left"><?php next_posts_link('&laquo; ' . _('Previous Entries')) ?></div>
			<div class="right"><?php previous_posts_link(_('Next Entries') . ' &raquo;') ?></div>
		</div>
	
	<?php else : ?>

		<h1><?php _e("No posts found") ?>.</h1>
		<h2><?php _e("Try something new") ?>:</h2>
		
		<?php include (TEMPLATEPATH . '/searchform.php'); ?>

	<?php endif; ?>
		
	</div>
	
	<div class="secondary">
		<h2>Search</h2>
		<div class="featured">
			<p><?php _e("You searched for") ?> &ldquo;<?php echo wp_specialchars($s, 1); ?>&rdquo; at <?php bloginfo('name'); ?>.
			<?php
				if (!$results) _e("There were no results. Try again with a new term.");
				elseif (1 == $results) _e("There was one result found. It must be your lucky day!");
				else echo __("There were") . " " . $results . " " . __("results found.");
			?>
			</p>
			
		</div>
	</div>
	<div class="clear"></div>
	</div>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>