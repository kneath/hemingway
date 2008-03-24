<?php get_header(); ?>

	<div id="primary" class="single-post">
	<div class="inside">
		<div class="primary">

			<?php if (have_posts()) : ?>
	
			<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
			<?php /* If this is a category archive */ if (is_category()) { ?>				
			<h1><?php _e("Archive for the") ?> '<?php echo single_cat_title(); ?>' <?php _e("category") ?></h1>
			
			<?php /* If this is a daily archive */ } elseif (is_day()) { ?>
			<h1><?php _e("Archive for")?> <?php the_time('F jS, Y'); ?></h1>
			
		 <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<h1><?php _e("Archive for") ?> <?php the_time('F, Y'); ?></h1>
	
			<?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<h1><?php _e("Archive for") ?> <?php the_time('Y'); ?></h1>
			
			<?php /* If this is a search */ } elseif (is_search()) { ?>
			<h1><?php _e("Search Results") ?></h1>
			
			<?php /* If this is an author archive */ } elseif (is_author()) { ?>
			<h1><?php _e("Author Archive") ?></h1>
	
			<?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<h1><?php _e("Blog Archives") ?></h1>
	
			<?php } ?>

		 <ul class="dates">
		 	<?php while (have_posts()) : the_post(); ?>
			<li>
				<span class="date"><?php the_time( $hemingway->date_format() . '.y' ) ?></span>
				<a href="<?php the_permalink() ?>"><?php the_title(); ?></a> 
				 <?php _e("posted in") ?>
				<?php the_category(', ') ?>  		 
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
		<h2><?php _e("About the archives") ?></h2>
		<div class="featured">
			<p><?php _e("Welcome to the archives here at") ?> <?php bloginfo('name'); ?>. <?php _e("Have a look around") ?>.</p>
			
		</div>
	</div>
	<div class="clear"></div>
	</div>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>