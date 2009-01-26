<hr class="hide" />
	<div id="ancillary">
		<div class="inside">
			<div class="block first">
				<h2>About</h2>
				<?php query_posts('pagename=about'); ?>
				<?php if (have_posts()) : ?>
				<?php while (have_posts()) : the_post(); ?>
				<?php the_content(); ?>
				<?php endwhile; ?>
				<?php endif; ?>
			</div>
			
			<div class="block">
				<h2>Recently</h2>
				<ul class="dates">
					<?php
						// I love WordPress so
						query_posts('showposts=10');
					?>
					<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<li><a href="<?php the_permalink() ?>"><span class="date"><?php the_time('m.j') ?></span> <?php the_title() ?> </a></li>
					<?php endwhile; endif; ?>
				</ul>
			</div>
			
			<div class="block">
				<h2>Categories</h2>
				<ul class="counts">
					<?php wp_list_cats('sort_column=name&optioncount=1&hierarchical=0'); ?>
				</ul>
			</div>
			
			<div class="clear"></div>
		</div>
	</div>
	<!-- [END] #ancillary -->	