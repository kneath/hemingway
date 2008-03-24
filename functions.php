<?php
class Hemingway
	{
		
		var $raw_blocks;
		var $available_blocks;
		var $style;
		var $version;
		
		function add_available_block($block_name, $block_ref)
			{

				$blocks = $this->available_blocks;
				
				if (!$blocks[$block_ref]){
					$blocks[$block_ref] = $block_name;
					update_option('hem_available_blocks', $blocks);
					wp_cache_flush();
				}
				
			}
		
		function get_available_blocks()
			// This function returns an array of available blocks
			// in the format of $arr[block_ref] = block_name
			{				
				$this->available_blocks = get_option('hem_available_blocks');
				return $this->available_blocks;
			}
		
		function get_block_contents($block_place)
			// Returns an array of block_refs in specififed block
			{
				if (!$this->raw_blocks){
					$this->raw_blocks = get_option('hem_blocks');
				}
				return $this->raw_blocks[$block_place];
			}
		
		function add_block_to_place($block_place, $block_ref)
			{
				$block_contents = $this->get_block_contents($block_place);
				if (in_array($block_ref, $block_contents))
					return true;
				
				$block_contents[] = $block_ref;	
				$this->raw_blocks[$block_place] = $block_contents;
				update_option('hem_blocks', $this->raw_blocks);
				wp_cache_flush(); // I was having caching issues
				return true;
			}
			
		function remove_block_in_place($block_place, $block_ref)
			{
				$block_contents = $this->get_block_contents($block_place);
				if (!in_array($block_ref, $block_contents))
					return true;
				$key = array_search($block_ref, $block_contents);
				unset($block_contents[$key]);
				$this->raw_blocks[$block_place] = $block_contents;
				update_option('hem_blocks', $this->raw_blocks);
				wp_cache_flush(); // I was having caching issues
				return true;
			}
			
			// Templating functions
			
			function get_block_output($block_place)
				{
					global $hemingway;
					$blocks = $this->get_block_contents($block_place);
					foreach($blocks as $key => $block ){
						include (TEMPLATEPATH . '/blocks/' . $block . '.php');
					}
				}
				
			function get_style(){
				$this->style = get_option('hem_style');
			}
			
			function date_format($slashes = false){
				global $hemingway_options;
				if ($slashes)
					return $hemingway_options['international_dates'] == 1 ? 'd/m' : 'm/d'; 
				else
					return $hemingway_options['international_dates'] == 1 ? 'd.m' : 'm.d'; 
			}
			
			// Excerpt cutting. I'd love to use the_excerpt_reloaded, but needless licensing prohibits me from doing so
			function excerpt(){
				echo $this->get_excerpt();
			}
			
			function get_excerpt(){
				global $post;
				
				$max_length = 75; // Maximum words.
				
				// If they've manually put in an excerpt, let it go!
				if ($post->post_excerpt) return $post->post_excerpt;
				
				// Check to see if it's a password protected post
				if ($post->post_password) {
						if ($_COOKIE['wp-postpass_'.COOKIEHASH] != $post->post_password) {
								if(is_feed()) {
										return __('This is a protected post');
								} else {
										return  get_the_password_form();
								}
						}
				}
				
				if( strpos($post->post_content, '<!--more-->') ) { // There's a more link
            $temp_ex = explode('<!--more-->', $post->post_content, 2);
            $excerpt =  $temp_ex[0];
        } else {
            $temp_ex = explode(' ', $post->post_content);  // Split up the spaces
						$length = count($temp_ex) < $max_length ? count($temp_ex) : $max_length;
						for ($i=0; $i<$length; $i++) $excerpt .= $temp_ex[$i] . ' ';
        }
        
				
				$excerpt = balanceTags($excerpt);
				$excerpt = apply_filters('the_excerpt', $excerpt);
				
				return $excerpt;
				
			}
	}
	
$hemingway = new Hemingway();

$hemingway->version = "0.17";
// Options

$default_blocks = Array(
	'recent_entries' => 'Recent Entries',
	'about_page' => 'About Page',
	'category_listing' => 'Category Listing',
	'blogroll' => 'Blogroll',
	'pages' => 'Pages',
	'monthly_archives' => 'Monthly Archives',
	'related_posts' => 'Recent Entries',
	'flickr_rss' => 'Flickr RSS'
);

$default_block_locations = Array(
	'block_1' => Array('about_page'),
	'block_2' => Array('recent_entries'),
	'block_3' => Array('category_listing'),
	'block_4' => Array(),
	'block_5' => Array(),
	'block_6' => Array()
);

$default_options = Array(
	'international_dates' => 0
);

if (!get_option('hem_version') || get_option('hem_version') < $hemingway->version){
	// Hemingway isn't installed, so we'll need to add options
	if (!get_option('hem_version') )
		add_option('hem_version', $hemingway->version, 'Hemingway Version installed');
	else
		update_option('hem_version', $hemingway->version);
		
	if (!get_option('hem_available_blocks') ) 
		add_option('hem_available_blocks', $default_blocks, 'A list of available blocks for Hemingway');
	
	if (!get_option('hem_blocks') ) 
		add_option('hem_blocks', $default_block_locations, 'An array of blocks and their contents');
	
	if (!get_option('hem_style') )
		add_option('hem_style', '', 'Location of custom style sheet');
		
	if (!get_option('hem_options') )
		add_option('hem_options', $default_options, 'Location of custom style sheet');
		
	wp_cache_flush(); // I was having caching issues
}

// Stuff

add_action ('admin_menu', 'hemingway_menu');

$hem_loc = '../themes/' . basename(dirname($file)); 

$hemingway->get_available_blocks();
$hemingway->get_style();
$hemingway_options = get_option('hem_options');

// Ajax Stuff

if ($_GET['hem_action'] == 'add_block'){
	auth_redirect(); // Make sure they're logged in
	$block_ref = $_GET['block_ref'];
	$block_place = $_GET['block_place'];
	
	$block_name = $hemingway->available_blocks[$block_ref];
	
	$hemingway->add_block_to_place($block_place, $block_ref);

	ob_end_clean(); // Kill preceding output
	$output = '<ul>';
	foreach($hemingway->get_block_contents($block_place) as $key => $block_ref){
			$block_name = $hemingway->available_blocks[$block_ref];
			$output .= '<li>' . $block_name . ' <a href="#" class="remove" onclick="remove_block(\'' . $block_place . '\', \'' . $block_ref . '\'); return false">remove</a></li>';
	}
	$output .= '</ul>';
	echo $output;
	exit(); // Kill any more output
}

if ($_GET['hem_action'] == 'remove_block'){
	auth_redirect(); // Make sure they're logged in
	$block_ref = $_GET['block_ref'];
	$block_place = $_GET['block_place'];
	
	$hemingway->remove_block_in_place($block_place, $block_ref);

	ob_end_clean(); // Kill preceding output
	$output = '<ul>';
	foreach($hemingway->get_block_contents($block_place) as $key => $block_ref){
			$block_name = $hemingway->available_blocks[$block_ref];
			$output .= '<li>' . $block_name . ' <a href="#" class="remove" onclick="remove_block(\'' . $block_place . '\', \'' . $block_ref . '\'); return false">remove</a></li>';
	}
	$output .= '</ul>';
	echo $output;
	exit(); // Kill any more output
}


function hemingway_menu() {
	add_submenu_page('themes.php', 'Hemingway Options', 'Hemingway Options', 5, $hem_loc . 'functions.php', 'menu');
}

function menu() {

	global $hem_loc, $hemingway, $message;
	
	if ($_POST['custom_styles']){
		update_option('hem_style', $_POST['custom_styles']);
		wp_cache_flush();
		$hemingway->get_style();
		$message  = 'Styles updated!';
	}
	
	if ($_POST['block_ref']){
		$hemingway->add_available_block($_POST['display_name'], $_POST['block_ref']);
		$hemingway->get_available_blocks();
		$message = 'Block added!';
	}
	
	if ($_POST['reset'] == 1){
		delete_option('hem_style');
		delete_option('hem_blocks');
		delete_option('hem_available_blocks');
		delete_option('hem_version');
		$message = 'Settings removed.';
	}
	
	if ($_POST['misc_options']){
		$hemingway_options['international_dates'] = $_POST['international_dates'];
		update_option('hem_options', $hemingway_options);
		wp_cache_flush();
		$message  = 'Options updated!';
	}


?>
<!--
Okay, so I don't honestly know how legit this is, but I want a more intuitive interface
so I'm going to import scriptaculous. There's a good chance this is going to mess stuff up
for some people :)
-->
<script type="text/javascript">
<?php include (TEMPLATEPATH . '/admin/js/prototype.js'); ?>
<?php include (TEMPLATEPATH . '/admin/js/dragdrop.js'); ?>
<?php include (TEMPLATEPATH . '/admin/js/effects.js'); ?>
</script>
<script type="text/javascript">
	function remove_block(block_place, block_ref){
		url = 'themes.php?page=functions.php&hem_action=remove_block&block_place=' + block_place + '&block_ref=' + block_ref;
		new Ajax.Updater(block_place, url, 
				{
					evalScripts:true, asynchronous:true,
					onComplete : function(request){
						$('dropmessage').innerHTML = "<p>Block removed!</p>";
						Effect.Appear('dropmessage', { queue: 'front' });
						Effect.Fade('dropmessage', { queue: 'end' });
					}
				}
		)
	}
</script>
<style>
	.block{
		width:200px;
		height:200px;
		border: 1px solid #bbb;
		background-color: #f0f8ff;
		float:left;
		margin:20px 1em 20px 0;
		padding:10px;
		display:inline;
	}
	.block ul{
		padding:0;
		margin:0;
	}
	.block ul li{
		margin:0 0 5px 0;
		list-style-type:none;
		border:1px solid #DDD;
		background:#FbFbFb;
		padding:4px 10px;
		position:relative;
	}
	.block ul li a.remove{
		position:absolute;
		right:10px;
		top:6px;
		display:block;
		text-decoration:none;
		font-size:1px;
		width:14px;
		height:14px;
		text-indent:-9999px;
		background:url(<?php bloginfo('stylesheet_directory'); ?>/admin/images/icon_delete.gif) 0 0 no-repeat #FFF;
		border:none;
	}
	* html .block ul li a.remove{ right:15px; }
	.block-active{
		border:1px solid #333;
		background:#F2F8FF;
	}
	
	#addables li{
		list-style-type:none;
		margin:1em 1em 1em 0;
		background:#F5F5F5;
		border:1px solid #CCC;
		padding:3px;
		width:215px;
		float:left;
		cursor:move;
	}
	ul#addables{
		margin:0;
		padding:0;
		width:720px;
		position:relative;
	}
</style>




<? if($message) : ?>
<div id="message" class="updated fade"><p><?=$message?></p></div>
<? endif; ?>
<div id="dropmessage" class="updated" style="display:none;"></div>
<div class="wrap" style="position:relative;">

<? if (get_option('hem_version')) : ?>


<h2><?php _e('Hemingway Options'); ?></h2>

<h3>Custom Styles</h3>
<p>Select a style from the dropdown below to customize hemingway with a special style.</p>
<form name="dofollow" action="" method="post">
  <input type="hidden" name="page_options" value="'dofollow_timeout'" />
	<select name="custom_styles">
	<option value="none"<?php if ($hemingway->style == 'none') echo 'selected="selected"'; ?>>No Custom Style</option>
	<?php
		$scheme_dir = @ dir(ABSPATH . '/wp-content/themes/' . get_template() . '/styles');
	
		if ($scheme_dir) {
			while(($file = $scheme_dir->read()) !== false) {
					if (!preg_match('|^\.+$|', $file) && preg_match('|\.css$|', $file)) 
					$scheme_files[] = $file;
				}
			}
			if ($scheme_dir || $scheme_files) {
				foreach($scheme_files as $scheme_file) {
				if ($scheme_file == $hemingway->style){
					$selected = ' selected="selected"';
				}else{
					$selected = "";
				}
				echo '<option value="' . $scheme_file . '"' . $selected . '>' . $scheme_file . '</option>';
			}
		} 
		?>
	</select>

	<input type="submit" value="Save" />
</form>

<h3>Hemingway's Bottombar&trade;</h3>
<p>Drag and drop the different blocks into their place below. After you drag the block to the area, it will update with the new contents automatically.</p>
<ul id="addables">
	<? foreach($hemingway->available_blocks as $ref => $name) : ?>
	<li id="<?= $ref ?>" class="blocks"><?= $name ?></li>
	<script type="text/javascript">new Draggable('<?= $ref ?>', {revert:true})</script>
	<? endforeach; ?>
</ul>

<div class="clear"></div>

<div class="block" id="block_1">
	<ul>
		<? 
		foreach($hemingway->get_block_contents('block_1') as $key => $block_ref) :
			$block_name = $hemingway->available_blocks[$block_ref];
		?>
			<li><?= $block_name ?> <a href="#" class="remove" onclick="remove_block('block_1', '<?=$block_ref?>'); return false">remove</a></li>
		<? endforeach; ?>
	</ul>
</div>
<script type="text/javascript">
Droppables.add(
	'block_1', {
		accept:'blocks', 
		onDrop:function(element){
			new Ajax.Updater('block_1', 'themes.php?page=functions.php&hem_action=add_block&block_place=block_1&block_ref=' + element.id, 
				{
					evalScripts:true, asynchronous:true,
					onComplete : function(request){
						$('dropmessage').innerHTML = "<p>Block added!</p>";
						Effect.Appear('dropmessage', { queue: 'front' });
						Effect.Fade('dropmessage', { queue: 'end' });
					}
				}
			)
		}, 
		hoverclass:'block-active'
	}
)
</script>

<div class="block" id="block_2">
	<ul>
		<? 
		foreach($hemingway->get_block_contents('block_2') as $key => $block_ref) :
			$block_name = $hemingway->available_blocks[$block_ref];
		?>
			<li><?= $block_name ?> <a href="#" class="remove" onclick="remove_block('block_2', '<?=$block_ref?>'); return false">remove</a></li>
		<? endforeach; ?>
	</ul>
</div>
<script type="text/javascript">
Droppables.add(
	'block_2', {
		accept:'blocks', 
		onDrop:function(element){
			new Ajax.Updater('block_2', 'themes.php?page=functions.php&hem_action=add_block&block_place=block_2&block_ref=' + element.id, 
				{
					evalScripts:true, asynchronous:true,
					onComplete : function(request){
						$('dropmessage').innerHTML = "<p>Block added!</p>";
						Effect.Appear('dropmessage', { queue: 'front' });
						Effect.Fade('dropmessage', { queue: 'end' });
					}
				}
			)
		}, 
		hoverclass:'block-active'
	}
)
</script>

<div class="block" id="block_3">
	<ul>
		<? 
		foreach($hemingway->get_block_contents('block_3') as $key => $block_ref) :
			$block_name = $hemingway->available_blocks[$block_ref];
		?>
			<li><?= $block_name ?> <a href="#" class="remove" onclick="remove_block('block_3', '<?=$block_ref?>'); return false">remove</a></li>
		<? endforeach; ?>
	</ul>
</div>
<script type="text/javascript">
Droppables.add(
	'block_3', {
		accept:'blocks', 
		onDrop:function(element){
			new Ajax.Updater('block_3', 'themes.php?page=functions.php&hem_action=add_block&block_place=block_3&block_ref=' + element.id, 
				{
					evalScripts:true, asynchronous:true,
					onComplete : function(request){
						$('dropmessage').innerHTML = "<p>Block added!</p>";
						Effect.Appear('dropmessage', { queue: 'front' });
						Effect.Fade('dropmessage', { queue: 'end' });
					}
				}
			)
		}, 
		hoverclass:'block-active'
	}
)
</script>

<!-- Maybe later...


<div class="clear"></div>

<div class="block" id="block_4">
	Block 4
	<ul>
		<? 
		foreach($hemingway->get_block_contents('block_4') as $key => $block_ref) :
			$block_name = $hemingway->available_blocks[$block_ref];
		?>
			<li><?= $block_name ?> (<a href="#" onclick="remove_block('block_4', '<?=$block_ref?>');">remove</a>)</li>
		<? endforeach; ?>
	</ul>
</div>
<script type="text/javascript">
Droppables.add(
	'block_4', {
		accept:'blocks', 
		onDrop:function(element){
			new Ajax.Updater('block_4', 'themes.php?page=functions.php&hem_action=add_block&block_place=block_4&block_ref=' + element.id, 
				{
					evalScripts:true, asynchronous:true
				}
			)
		}, 
		hoverclass:'block-active'
	}
)
</script>

<div class="block" id="block_5">
	Block 5
	<ul>
		<? 
		foreach($hemingway->get_block_contents('block_5') as $key => $block_ref) :
			$block_name = $hemingway->available_blocks[$block_ref];
		?>
			<li><?= $block_name ?> (<a href="#" onclick="remove_block('block_5', '<?=$block_ref?>');">remove</a>)</li>
		<? endforeach; ?>
	</ul>
</div>
<script type="text/javascript">
Droppables.add(
	'block_5', {
		accept:'blocks', 
		onDrop:function(element){
			new Ajax.Updater('block_5', 'themes.php?page=functions.php&hem_action=add_block&block_place=block_5&block_ref=' + element.id, 
				{
					evalScripts:true, asynchronous:true
				}
			)
		}, 
		hoverclass:'block-active'
	}
)
</script>

<div class="block" id="block_6">
	Block 6
	<ul>
		<? 
		foreach($hemingway->get_block_contents('block_6') as $key => $block_ref) :
			$block_name = $hemingway->available_blocks[$block_ref];
		?>
			<li><?= $block_name ?> (<a href="#" onclick="remove_block('block_6', '<?=$block_ref?>');">remove</a>)</li>
		<? endforeach; ?>
	</ul>
</div>
<script type="text/javascript">
Droppables.add(
	'block_6', {
		accept:'blocks', 
		onDrop:function(element){
			new Ajax.Updater('block_6', 'themes.php?page=functions.php&hem_action=add_block&block_place=block_6&block_ref=' + element.id, 
				{
					evalScripts:true, asynchronous:true
				}
			)
		}, 
		hoverclass:'block-active'
	}
)
</script>
-->

<div class="clear"></div>

	<?php
		$blocks_dir = @ dir(ABSPATH . '/wp-content/themes/' . get_template() . '/blocks');
	
		if ($blocks_dir) {
			while(($file = $blocks_dir->read()) !== false) {
					if (!preg_match('|^\.+$|', $file) && preg_match('|\.php$|', $file)) 
					$blocks_files[] = $file;
				}
			}
			if ($blocks_dir || $blocks_files) {
				foreach($blocks_files as $blocks_file) {
				$block_ref = preg_replace('/\.php/', '', $blocks_file);
				if (!array_key_exists($block_ref, $hemingway->available_blocks)){
				?>
				<h3>You have uninstalled blocks!</h3>
				<p>Give the block <strong><?=$block_ref ?></strong> a display name (such as "About Page")</p>
				<form action="" name="dofollow" method="post">
					<input type="hidden" name="block_ref" value="<?=$block_ref?>" />
					<?=$block_ref ?> : <input type="text" name="display_name" />
					<input type="submit" value="Save" />
				</form>
				<?
				}
			}
		} 
		?>


<h3>Miscellaneous Options</h3>
<form name="dofollow" action="" method="post">
<input type="hidden" name="misc_options" value="1" />
<p><label><input type="checkbox" value="1" name="international_dates" <? if ($hemingway_options['international_dates'] == 1) echo "checked=\"checked\""; ?> /> Use international dates? (day/month/year)</label></p>
<p><input type="submit" value="Save my options" /></p>
</form>

<h3>Reset / Uninstall</h3>
<form action="" method="post" onsubmit="return confirm('Are you sure you want to reset all of your settings?')">
<input type="hidden" name="reset" value="1" />
<p>If you would like to reset or uninstall Hemingway, push this button. It will erase all of your preferences. <input type="submit" value="Reset" /></p>
</form>

<? else: ?>
<p>Thank you for using Hemingway!  There's two reasons you might be seeing this:</p>
<ol>
	<li>You've just installed Hemingway for the first time: If this is the case, simply reload this page or click on Hemingway Options again and you'll be on your way!</li>
	<li>You've just uninstalled Hemingway or reset your options. If you'd like to keep using Hemingway, reload this page or click on Hemingway Options again.</li>
</ol>
<? endif; ?>

</div>

<?php
}
?>