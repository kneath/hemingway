
<hr class="hide" />
	<div id="footer">
		<div class="inside">
			<?php
				// You are not required to keep this link back to Warpspire, but if you wouldn't mind, leaving it in would make my day.
			?>
			<p class="copyright"><?php _e("Powered by") ?> <a href="http://warpspire.com/hemingway">Hemingway</a> <?php _e("flavored") ?> <a href="http://wordpress.org">Wordpress</a>.</p>
			<p class="attributes"><a href="feed:<?php bloginfo('rss2_url'); ?>"><?php _e("Entries") ?> RSS</a> <a href="feed:<?php bloginfo('comments_rss2_url'); ?>"><?php _e("Comments") ?> RSS</a><?php wp_register('',''); ?></p>
		</div>
	</div>
	<!-- [END] #footer -->	

	<!-- <?php echo get_num_queries(); ?> queries. <?php timer_stop(1); ?> seconds. -->
	<?php wp_footer(); ?>
</body>
</html>
