

<?php

/*
 * Plugin Name:       Related Posts
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       With the plugin can enhance your post engagement and user can reach your more posts ..
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            Muhammad Aniab
 * Author URI:        http://muhammadaniab.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-assignment-plugin
 * Domain Path:       /languages
 */

class rpp_related_post_plugin {
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}
	public function init() {
				add_action('wp_enqueue_scripts', array( $this,'enqueue_styles') );
				add_filter( 'the_content', array( $this, 'create_related_post' ) );
				
	}

	public function create_related_post( $contents ) {
		// Check if it's a single post page
		if ( is_single() && 'post' == get_post_type() ) {
			// Start output buffering
			ob_start();

			$post          = get_the_ID();
			$category_name = wp_get_post_categories( $post );

			$arg   = array(
				'post_type'      => 'post',
				'posts_per_page' => '5',
				'orderby'		 => 'rand',
				'category__in'   => $category_name,
				'post__not_in'   => array( $post ),
			);
			$query = new WP_Query( $arg );

			if ( $query->have_posts() ) {
				// Loop through related posts
				while ( $query->have_posts() ) {
					$query->the_post();
					$link  = esc_url(get_permalink());
					$title = esc_html__(get_the_title());
					$thumbnail = get_the_post_thumbnail( get_the_ID(), 'thumbnail' );

					// Set a default thumbnail if not available
					if ( ! $thumbnail ) {
						$thumbnail = esc_url('https://www.nicepng.com/png/detail/942-9422332_gallery-icon-critical-care-unit-logo.png');
					}
					// Related Post HTML
					$category_list .= "<div class='related_post'> <img src={$thumbnail} 
					<a href={$link} <p> {$title} </p> 
					</a></div>";

				}
				return $contents . '<div class="related_heading" > <h2>Related Posts</h2></div>
				<div class="related_section" >' .$category_list . '</div>';
			}
			wp_reset_postdata();
			// buffer clean
			return ob_get_clean();
		}
		// Return original content for non-single post pages
		return $contents;
	}

	public function enqueue_styles()
    {
        // Enqueue the CSS file
        wp_enqueue_style('related-posts-css', plugins_url('css/style.css', __FILE__));
    }
}

new rpp_related_post_plugin();

