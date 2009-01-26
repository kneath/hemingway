<?php get_header(); ?>


	<div id="primary" class="twocol-stories">
		<div class="inside">
			<?php
				// Here is the call to only make two posts show up on the homepage REGARDLESS of your options in the control panel
				query_posts('showposts=2');
			?>
			<?php if (have_posts()) : ?>
				<?php $first = true; ?>
				<?php while (have_posts()) : the_post(); ?>
					<?php if($count < 2) { ?>
					<?php $count++; ?>
					<div class="story<?php if($first == true) echo " first" ?>">
						<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h3>
						<?php the_excerpt() ?>
						<div class="details">
							Posted at <?php the_time('ga \o\n n/j/y') ?> | <?php comments_popup_link('no comments;', '1 comment', '% comments'); ?> | Filed Under: <?php the_category(', ') ?> | <?php if (is_callable('the_tags')) the_tags('Tagged: ', ', ', ' | '); ?> <span class="read-on"><a href="<?php the_permalink() ?>">read on</a></span>
						</div>
					</div>
					<?php $first = false; ?>
					<?php } ?>
				<?php endwhile; ?>
		</div>
				
			<?php else : ?>
		
				<h2 class="center">Not Found</h2>
				<p class="center">Sorry, but you are looking for something that isn't here.</p>
				<?php include (TEMPLATEPATH . "/searchform.php"); ?>
		
			<?php endif; ?>
				
			<div class="clear"></div>
		</div>
	</div>
	<!-- [END] #primary -->



<?php get_sidebar(); ?>

<?php get_footer(); ?>
