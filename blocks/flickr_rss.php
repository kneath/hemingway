<h2>Flickr Photos</h2>

<?php if ( (function_exists('get_flickrRSS')) ) { ?>
	
		<?php get_flickrRSS(); ?>
		
		<?php if (get_option('flickrRSS_tagtype') == "userid") { $flickrurl = "photos/" . get_option('flickrRSS_tag'); }
		elseif (get_option('flickrRSS_tagtype') == "tag") { $flickrurl = "photos/tags/" . get_option('flickrRSS_tag'); }
		elseif (get_option('flickrRSS_tagtype') == "group") { $flickrurl = "groups/" . get_option('flickrRSS_tag') . "/pool"; }
		elseif (get_option('flickrRSS_tagtype') == "usertag") { $flickrurl = "photos/" . get_option('flickrRSS_tag') . "/tags/" . get_option('flickrRSS_tag2'); } ?>
		
			<p><a href="http://flickr.com/<?php echo $flickrurl; ?>/" class="flickrlink"><span>View All Photos</span></a></p>
		

<?php } else { ?>

		<p>If you have a Flickr account, you can display your photos here using the <a href="http://eightface.com/code/wp-flickrrss/">flickrRSS</a> plugin.</p>

		<p>If you have already downloaded the flickrRSS plugin, but are getting this message, <a href="<?php echo get_settings('home'); ?>/wp-admin/plugins.php">click here to make sure that the plugin is activated</a>.</p>

		<p>If you do not have a Flickr account you can:
			<ul>
				<li>Create a Flickr account at <a href="http://www.flickr.com/signup/">flickr.com</a>.</li>
				<li>Remove this block.</li>
			</ul>
		</p>

<?php } ?>
<?php
/*
	 Props to http://freshnecessity.net/
*/ 
?>