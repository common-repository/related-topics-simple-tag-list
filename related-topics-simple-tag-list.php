<?php
/**
 * Plugin Name: Related Topics Simple Tag List
 * Plugin URI: https://github.com/5t3ph/related-topics-simple-tag-list
 * Description: Outputs a basic inline or bulleted list of the current post's tags with intelligent display & customizable options. 
 * Version: 1.1
 * Author: Stephanie Eckles
 * Author URI: http://thinkdobecreate.com
 * License: GPL2
 */

// Standard prevention of direct access
defined('ABSPATH') or die("No script kiddies please!");

// Require the options page functions
require_once plugin_dir_path( __FILE__ ) . 'rtstl-options.php';


/**
 * Output related tag list.
 *
 * May be modified based on plugin settings.
 *
 * @since 1.0
 *
 * @see rtstl_fetch_post_link()
 *
 * @global post Get current post.
 *
 * @param  type $var Description.
 *
 * @return string Final output to be echoed by template tag or filter.
 */
 function rtstl_output() {
	
	global $post;
	
	// Get user defined options
	$rtstl_options = get_option( 'rtstl_options' );
	
	// Assign option values to variables
	$rtstl_theme = isset($rtstl_options['rtstl_theme']) ? $rtstl_options['rtstl_theme'] : 'inline';
	$rtstl_separator = isset($rtstl_options['rtstl_inline_separator']) ? $rtstl_options['rtstl_inline_separator'] : 'pipe';
	$rtstl_html_before = isset($rtstl_options['rtstl_html_before']) ? $rtstl_options['rtstl_html_before'] : '<h3>Related Topics</h3>';
	$rtstl_html_after = isset($rtstl_options['rtstl_html_after']) ? $rtstl_options['rtstl_html_after'] : '';
	$rtstl_tag_number = isset($rtstl_options['rtstl_tag_number']) ? $rtstl_options['rtstl_tag_number'] : '5';
	
	// Declare variable to hold output
	$rtstl_output = '';
	
	/* WordPress function to get the tags attached to this post
	 * @link http://codex.wordpress.org/Function_Reference/get_the_tags
	 */
	$posttags = get_the_tags();
	
	if ($posttags) {
		
		// Get count & start counter for use in displaying inline separator
		$rtstl_tag_count = count($posttags);
		$rtstl_tag_counter = 1;
		
		// Output rtstl_before_html if defined in options
		$rtstl_output .= wp_kses_post($rtstl_html_before);
		
		// Begin list if using bullets
		if($rtstl_theme == 'bullets') {
			$rtstl_output .= '<ul id="rtstl_listing">';
		} else {
			// Else, wrap inline theme with a paragraph
			$rtstl_output .= '<p>';
		}
		
		foreach($posttags as $tag) {
			
			// Check that tag belongs to at least one other article
			if($tag->count > 1) {
				
				/*
				 * Determine if tag count equals 2.
				 * If so, link params treated differently.
				 */
				$rtstl_tag_count_two = $tag->count == 2 ? true : false;
				
				/*
				 * Determine tag link.
				 *
				 * If tag only attached to 2 posts, we want to go directly to the other post.
				 * Else, go to the tag archive page.
				 */
				$rtstl_tag_link = $rtstl_tag_count_two ? rtstl_fetch_post_link($post->ID, $tag->term_id) : get_tag_link($tag->term_id);
				/*
				 * Determine tag link title.
				 *
				 * If tag only attached to 2 posts, say "Related Post".
				 * Else, say "Archive of Related Posts".
				 */
				$rtstl_tag_title = $rtstl_tag_count_two ? 'Related Post' : 'Archive of Related Posts';
				
				// Add links if using bullets
				if($rtstl_theme == 'bullets')
					$rtstl_output .= '<li>';
				
				// Create final individual tag output
				$rtstl_output .= "<a href='$rtstl_tag_link' title='View $rtstl_tag_title'>$tag->name</a>";
				
				// End links if using bullets
				if($rtstl_theme == 'bullets')
					$rtstl_output .= '</li>';
				
				// Add separator if using inline theme
				if( ( $rtstl_theme == 'select' || $rtstl_theme == 'inline' ) && ( $rtstl_tag_counter < $rtstl_tag_count ) ) {
					switch($rtstl_separator) {
						case 'space':
							$separator = ' ';
							break;
						case 'bullet':
							$separator = ' &bull; ';
							break;
						default:
							$separator = ' | ';
					}
					
					$rtstl_output .= $separator;
				}
				
				// Increase counter
				$rtstl_tag_counter++;
				
			}
		
		} // End $posttags foreach
		
		// End list if using bullets
		if($rtstl_theme == 'bullets') {
			$rtstl_output .= '</ul>';
		} else {
			// Remove errant $separator which appears if the last tag is only attached to this post
			// therefore throwing off the count check above
			$rtstl_output = trim($rtstl_output, $separator);
			
			// End inline theme paragraph
			$rtstl_output .= '</p>';
		}
		
		// Include any HTML optioned as after the list
		$rtstl_output .= wp_kses_post($rtstl_html_after);
		
		
		// Return final output
		return $rtstl_output;
		
	} // End check for $posttags
 
 }
 
/**
 * Called by rtstl_output()
 *
 * Return post link for a tag with only one other post.
 *
 * @since 1.0
 *
 * @param  string $current_post_id Passed id of the current post.
 * @param  string $current_tag_id Passed id of tag we're checking.
 *
 * @return string The post permalink.
 */
 function rtstl_fetch_post_link($current_post_id, $current_tag_id) {
	 
	 // Create variable to hold fetched permalink
	 $rtstl_fetched_url = '';
	 
	 // Use passed params to fetch the one other post with this tag
	 $rtstl_fetch_post_args = array(
	 	'post_type' => 'post',
  		'post_status' => 'publish',
	 	'post__not_in' => array($current_post_id),
		'tag_id' => $current_tag_id
		);
		
	$rtstl_fetch_post_info = new WP_Query( $rtstl_fetch_post_args );

	// Loop to get our permalink
	if ( $rtstl_fetch_post_info->have_posts() ) :
	
	while ( $rtstl_fetch_post_info->have_posts() ) : $rtstl_fetch_post_info->the_post();
	
		$rtstl_fetched_url .= get_permalink();
	 
	 endwhile; endif; wp_reset_postdata();
	 
	 // Return fetched permalink
	 return $rtstl_fetched_url;
	 
 }
 
 /**
 * Append tag list to the end of every tag content.
 *
 * Occurs only if option set to allow this vs. using the template tag.
 *
 * @since 1.0
 *
 * @param $content Passed content of current post.
 *
 * @return string The post permalink.
 */
 function rtstl_append_to_posts($content) {
	 
	// Get user defined options
	$rtstl_options = get_option( 'rtstl_options' );
	
	// Find method of insertion choice
	$rtstl_method = isset($rtstl_options['rtstl_insertion_method']) ? $rtstl_options['rtstl_insertion_method'] : '';

	// If option is positive, then append list to content
	if($rtstl_method == '1') {
		/*
		 * Only display for main query on single pages
		 * @link https://pippinsplugins.com/playing-nice-with-the-content-filter/
		 */
		 if( is_single() && is_main_query() ) {
			
			$content .= rtstl_output();	
			
		}
	}
	
	return $content;
 }
add_filter('the_content', 'rtstl_append_to_posts');