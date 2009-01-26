<?php get_header(); ?>
<?php is_tag(); ?>

	<div id="primary" class="single-post">
	<div class="inside">
		<div class="primary">

			<?php if (have_posts()) : ?>
	
			<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
			<?php /* If this is a category archive */ if (is_category()) { ?>
			<h1>Archive for the '<?php single_cat_title(); ?>' Category</h1>

			<?php /* If this is a tag archive */ } elseif (is_tag()) { ?>
			<h1>Archive for the '<?php single_tag_title(); ?>' Tag</h1>
			
			<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
			<h1>Archive for <?php the_time('F jS, Y'); ?></h1>
			
		 <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<h1>Archive for <?php the_time('F, Y'); ?></h1>
	
			<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<h1>Archive for <?php the_time('Y'); ?></h1>
			
			<?php /* If this is a search */ } elseif (is_search()) { ?>
			<h1>Search Results</h1>
			
			<?php /* If this is an author archive */ } elseif (is_author()) { ?>
			<h1>Author Archive</h1>
	
			<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<h1>Blog Archives</h1>
	
			<?php } ?>

		 <ul class="dates">
		 	<?php while (have_posts()) : the_post(); ?>
			<li>
				<span class="date"><?php the_time('m.j.y') ?></span>
				<a href="<?php the_permalink() ?>"><?php the_title(); ?></a> 
				 posted in 
				<?php the_category(', ') ?>
				<?php if (is_callable('the_tags')) the_tags('tagged ', ', '); ?>
			</li>
		
			<?php endwhile; ?>
		</ul>
		
		<div class="navigation">
			<div class="left"><?php next_posts_link('&laquo; Previous Entries') ?></div>
			<div class="right"><?php previous_posts_link('Next Entries &raquo;') ?></div>
		</div>

	
	<?php else : ?>

		<h1>Not Found</h1>

	<?php endif; ?>
		
	</div>
	
	<div class="secondary">
		<h2>About the archives</h2>
		<div class="featured">
			<p>Welcome to the archives here at <?php bloginfo('name'); ?>. Have a look around.</p>
			
		</div>
	</div>
	<div class="clear"></div>
	</div>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
