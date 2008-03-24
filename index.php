<?php get_header(); ?>


	<div id="primary" class="twocol-stories">
		<div class="inside">
			<?php
				// Here is the call to only make two posts show up on the homepage REGARDLESS of your options in the control panel
				// Remove this line if you wish to use the default behaviour.
				// TODO: Provide documentation for allowing many posts on the homepage & CSS style guidelines
				query_posts('showposts=2');
			?>
			<?php if (have_posts()) : ?>
				<?php $first = true; ?>
				<?php while (have_posts()) : the_post(); ?>
					<div class="story<?php if($first == true) echo " first" ?>">
						<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h3>
						<?php $hemingway->excerpt() ?>
						<div class="details">
							<? _e('Posted at') ?> <?php the_time('ga \o\n ' . $hemingway->date_format(true) . '/y') ?> | <?php comments_popup_link('no comments', '1 comment', '% comments'); ?> | <?php _e("Filed under") ?>: <?php the_category(', ') ?> <span class="read-on"><a href="<?php the_permalink() ?>"><?php _e("read on") ?></a></span>
						</div>
					</div>
					<?php $first = false; ?>
				<?php endwhile; ?>
		</div>
				
			<?php else : ?>
		
				<h2 class="center"><?php _e("Not Found") ?></h2>
				<p class="center"><?php _e("Sorry, but you are looking for something that isn't here.") ?></p>
				<?php include (TEMPLATEPATH . "/searchform.php"); ?>
		
			<?php endif; ?>
				
			<div class="clear"></div>
	</div>
	<!-- [END] #primary -->



<?php get_sidebar(); ?>

<?php get_footer(); ?>
