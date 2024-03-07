
// Linked with the style.css file
<style>
<?php include 'css/style.css'; ?>
</style>
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

 class rpp_related_post_plugin{
	public function __construct(){
		add_action("init", array( $this,"init") );
	}
	public function init() {
		// 
		add_filter("the_content", array( $this,"create_related_post") );
	}

	public function create_related_post($contents){

		if(is_single() && 'post' == get_post_type()){
			
			ob_start();

			$post= get_the_ID();
			$category_name=wp_get_post_categories($post);
			
			$arg=[
				'post_type'=>'post',
				'posts_per_page'=> '5',
				'category__in'=> $category_name,
				'post__not_in'=> array($post),
			];
			$query= new WP_Query($arg);
			$category_list="";
			

			if($query->have_posts()){
				while($query->have_posts()){
					$query->the_post();
					$link=get_permalink();
					$title=get_the_title();
					
					$thumbnail=get_the_post_thumbnail(get_the_ID(),'thumbnail');

					if(!$thumbnail){
						$thumbnail= 'http://muhammad.local/wp-content/uploads/2024/03/icon-256x256-1.jpg';
					}

					$category_list.="<a href={$link}><div> <img src={$thumbnail} </div>

					<div class='related-post-title' ><p> {$title} </p> 
					</div>
					</a>";

				}
				echo $contents.'<div class="related_heading" > <h2>Related Posts</h2></div>
				<div class="related_section" >'.$category_list.'</div>';
			}
			wp_reset_postdata();
			return ob_get_clean();
		}

		return $contents;

	}
 }

 new rpp_related_post_plugin();

