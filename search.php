<?php get_header(); ?>

	<div id="primary" class="single-post">
	<div class="inside">
		<div class="primary">

	<?php if (have_posts()) : ?>

		<h1>Search Results</h1>
		
		<ul class="dates">
		 	<?php while (have_posts()) : the_post(); ?>
			<li>
				<span class="date"><?php the_time('n.j.y') ?></span>
				<a href="<?php the_permalink() ?>"><?php the_title(); ?></a> 
				 posted in 
				<?php the_category(', ') ?>  
				<?php if (is_callable('the_tags')) the_tags('tagged ', ', '); ?>
			</li>
			<?php $results++; ?>
			<?php endwhile; ?>
		</ul>
		
		<div class="navigation">
			<div class="left"><?php next_posts_link('&laquo; Previous Entries') ?></div>
			<div class="right"><?php previous_posts_link('Next Entries &raquo;') ?></div>
		</div>
	
	<?php else : ?>

		<h1>No posts found. Try a different search?</h1>

	<?php endif; ?>
		
	</div>
	
	<div class="secondary">
		<h2>Search</h2>
		<div class="featured">
			<p>You searched for &ldquo;<?php echo wp_specialchars($s, 1); ?>&rdquo; at <?php bloginfo('name'); ?>. There were
			<?php
				if (!$results) echo "no results, better luck next time.";
				elseif (1 == $results) echo "one result found. It must be your lucky day.";
				else echo $results . " results found.";
			?>
			</p>
			
		</div>
	</div>
	<div class="clear"></div>
	</div>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
