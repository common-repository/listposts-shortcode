<?php
/*
Plugin Name: ListPosts Shortcode
Plugin URI: http://listposts.lanexa.net
Description: Use shortcode to list posts from any taxonomy. Use parameters to customize appearance & functionality.
Version: 1.2
Author: Doug Walker
Author URI: http://lanexa.ent
License: GPL2
*/
/*  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// ListPosts via [listposts]
function listposts_shortcode( $lpatts, $lpcontent = null ) {
	$lpatts = shortcode_atts(
		array( // parameters and their defaults
			'posttype' => 'post', /* could be 'page' or a custom post type */
			'taxonomy' => 'category', /* optional custom taxonomy, i.e. "genre" */
			'category' => '', /* your-category-slug */
			'cat' => '', /* your_cat_ID (use "-" to exclude a cat) */
			'taxname' => '', /* your-taxonomy-name-slug, i.e. "history" */
			'tag' => '', /* optional comma-separated tag IDs */
			'showposts' => '0', /* uses number in Dashboard Settings > Reading */
			'orderby' => 'date',  /* or choose 'title', 'menu_order', etc. */
			'order' => 'DESC', /* latest first */
			'showtitle' => 'yes',
			'linktitle' => 'yes', /* make the title a link? ('yes' or 'no') */
			'linkblock' => 'no', /* make the entire block a link? ('yes' or 'no') */
			'showimage' => 'no',
			'linkimage' => 'no', /* make the image a link? 'yes' or 'no' */
			'imagesize' => 'thumbnail',
			'showdate' => 'yes', /* uses date format in Dashboard Settings > General */
			'content' => 'no', /* show the_content() ('yes' or 'no') */
			'excerpt' => 'no', /* show the_excerpt() ('yes' or 'no') */
			'divider' => 'no', /* insert <hr /> between posts */
			/* Below parameters only work with permalink structure /%category%/%postname%/ */
			'morebtn' => '', /* link to category page ('above' or 'below') */
			'btnabove' => 'no', /* display More button above the list ('yes' or 'no') */
			'btnbelow' => 'no', /* display More button below the list ('yes' or 'no') */
			'btnlabel' => 'More', /* button label */
			'btnclass' => 'button', /* class for More button element */
			/* Supports special MP3 Attachment function 'mp3_file_url()' */
			'mp3' => 'no', 
		), $lpatts
	);	
	
	ob_start();
?>

<?php $temp = $wp_query; $wp_query = null; $wp_query = new WP_Query(); $wp_query->query('post_type='.$lpatts['posttype'].'&category_name='.$lpatts['category'].'&cat='.$lpatts['cat'].'&'.$lpatts['taxonomy'].'='.$lpatts['taxname'].'&tag='.$lpatts['tag'].'&posts_per_page='.$lpatts['showposts'].'&orderby='.$lpatts['orderby'].'&order='.$lpatts['order']); if($wp_query->have_posts()): ?>
<ul class="listposts lp-<?php echo $lpatts['category'];?> clearfix">
   <?php if ($lpatts['morebtn'] == 'above') { ?>
	<li class="listposts-li clearfix"><a class="listposts-morebtn <?php echo $lpatts['btnclass'];?>" href="<?php bloginfo('siteurl');?>/category/<?php echo $lpatts['category'];?>"><?php echo $lpatts['btnlabel'];?></a></li>
   <?php } ?>
	<?php while($wp_query->have_posts()): $wp_query->the_post();?>
	<li class="listposts-li lp-entry lp-entry-<?php the_ID();?> clearfix">
	  <article class="clearfix">
	   <?php if ($lpatts['linkblock'] == 'yes') { ?>
	   <a class="block-link" title="<?php the_title();?>" href="<?php the_permalink();?>">
	   <?php } ?>
		<?php if ($lpatts['showimage'] == 'yes') { ?>
		<div class="lp-image">
			<?php if ($lpatts['linkimage'] == 'yes') { ?>
			<a class="lp-linkimage" title="<?php the_title();?>" href="<?php the_permalink();?>">
				<?php the_post_thumbnail($lpatts['imagesize']);?>
			</a>
			<?php } else { ?>
			<?php the_post_thumbnail($lpatts['imagesize']);?>
			<?php } ?>
		</div>
		<?php } ?>
		<div class="lp-text">
			<?php if ($lpatts['showtitle'] == 'yes') { ?>
			<h4 class="lp-entry-title">
				<?php if ($lpatts['linktitle'] == 'yes') { ?>
					<a href="<?php the_permalink();?>" title="<?php the_title();?>"><?php the_title();?></a>
				<?php } else { ?>
					<?php the_title();?>
				<?php } ?>
			</h4>
			<?php } ?>
			<?php if ($lpatts['showdate'] == 'yes') { ?>
			<p><small><?php echo get_the_date();?></small></p>
			<?php } ?>
			<?php if ($lpatts['mp3'] == 'yes') { ?>
				<?php $custom = get_post_custom($wp_query->post->ID);?>
				<a href="<?php echo $custom['mp3_url'][0];?>" title="Play <?php the_title();?>"><?php the_title();?></a>
			<?php } ?>
			<?php if ($lpatts['content'] == 'yes') { ?>
			<div class="lp-content"><?php the_content();?></div>
			<?php } ?>
			<?php if ($lpatts['excerpt'] == 'yes') { ?>
			<div class="lp-excerpt"><?php the_excerpt();?></div>
			<?php } ?>
		</div>
	   <?php if ($lpatts['linkblock'] == 'yes') { ?>
	   </a><!--/block-link-->
	   <?php } ?>
	  </article>
	  <?php if ($lpatts['divider'] == 'yes') { ?>
	  <hr />
	  <?php } ?>
	</li>
<?php endwhile;?>
   <?php if ($lpatts['morebtn'] == 'below') { ?>
	<li class="listposts-li clearfix"><a class="lp-morebtn <?php echo $lpatts['btnclass'];?>" href="<?php bloginfo('siteurl');?>/category/<?php echo $lpatts['category'];?>"><?php echo $lpatts['btnlabel'];?></a></li>
   <?php } ?>
</ul>
<?php endif; $wp_query = null; $wp_query = $temp;?>

<?php 

    $result = ob_get_contents(); // get everything in to $result variable
    ob_end_clean();
    return $result;

} 

add_shortcode( 'listposts', 'listposts_shortcode' );

?>