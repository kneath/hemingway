<h2>Recently</h2>
<ul class="dates">
	<?php
		// I love WordPress so
		query_posts('showposts=10');
	?>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	<li><a href="<?php the_permalink() ?>"><span class="date"><?php the_time('m.d') ?></span> <?php the_title() ?> </a></li>
	<?php endwhile; endif; ?>
</ul>
