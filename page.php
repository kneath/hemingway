<?php get_header(); ?>

	<div id="primary">
	<div class="inside">

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h1><?php the_title(); ?></h1>
				<?php the_content('<p class="serif">' . _('Read the rest of this page') . ' &raquo;</p>'); ?>
	
				<?php link_pages('<p><strong>' . _('Pages') . ':</strong> ', '</p>', 'number'); ?>
	  <?php endwhile; endif; ?>
	<?php edit_post_link(_('Edit this entry.'), '<p>', '</p>'); ?>
	</div>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>