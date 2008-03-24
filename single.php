<?php get_header(); ?>

	<div id="primary" class="single-post">
		<div class="inside">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="primary">
				<h1><?php the_title(); ?></h1>
				<?php the_content('<p class="serif">' . __('Read the rest of this entry') . ' &raquo;</p>'); ?>
			</div>
			<hr class="hide" />
			<div class="secondary">
				<h2>About this entry</h2>
				<div class="featured">
					<p><?php _e("You're currently reading") ?> &ldquo;<?php the_title(); ?>,&rdquo; <?php _e("an entry on") ?> <?php bloginfo('name'); ?></p>
					<dl>
						<dt><?php _e("Published") ?>:</dt>
						<dd><?php the_time( $hemingway->date_format() . '.y' ) ?> / <?php the_time('ga') ?></dd>
					</dl>
					<dl>
						<dt>Category:</dt>
						<dd><?php the_category(', ') ?></dd>
					</dl>
					<?php edit_post_link(__('Edit this entry') . '.', '<dl><dt>' . __('Edit') . ':</dt><dd> ', '</dd></dl>'); ?>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<!-- [END] #primary -->
	
	<hr class="hide" />
	<div id="secondary">
		<div class="inside">
			
			<?php if ('open' == $post-> comment_status) {
				// Comments are open ?>
				<div class="comment-head">
					<h2><?php comments_number('No comments','1 Comment','% Comments'); ?></h2>
					<span class="details"><a href="#comment-form"><?php _e("Jump to comment form") ?></a> | <?php comments_rss_link('comments rss'); ?> <a href="#what-is-comment-rss" class="help">[?]</a> <?php if ('open' == $post->ping_status): ?>| <a href="<?php trackback_url(true); ?>">trackback uri</a> <a href="#what-is-trackback" class="help">[?]</a><?php endif; ?></span>
				</div>
			<?php } elseif ('open' != $post-> comment_status) {
				// Neither Comments, nor Pings are open ?>
				<div class="comment-head">
					<h2><?php _e("Comments are closed") ?></h2>
					<span class="details"><?php _e("Comments are currently closed on this entry") ?>.</span>
				</div>	
			<?php } ?>
			
			<?php comments_template(); ?>
			
			<?php endwhile; else: ?>
			<p><?php _e("Sorry, no posts matched your criteria") ?>.</p>
			<?php endif; ?>
		</div>
	</div>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
