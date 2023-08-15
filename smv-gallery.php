<?php
/**
 * Plugin Name: SMV Gallery Plugin
 * Plugin URI: https://sindhu2704.wordpress.com/
 * Description: Creating a Gallery Plugin using post type
 * Version: 0.1
 * Text Domain: SMV Gallery Plugin
 * Author: Sindhu Periasamy
 * Author URI: https://sindhu2704.wordpress.com/
 */
 
//Create a Custom Post Type for SMV Gallery
add_theme_support('post-thumbnails');
add_post_type_support( 'msv_galleries', 'thumbnail' ); 

 function msv_galleries_custom_postype() {
	$labels = array(
        'name'                => _x( 'MSV Galleries', 'Post Type General Name', 'msvgallery' ),
        'singular_name'       => _x( 'MSV Gallery', 'Post Type Singular Name', 'msvgallery' ),
        'menu_name'           => __( 'MSV Galleries', 'msvgallery' ),
        'parent_item_colon'   => __( 'Parent MSV Gallery', 'msvgallery' ),
        'all_items'           => __( 'All MSV Galleries', 'msvgallery' ),
        'view_item'           => __( 'View MSV Gallery', 'msvgallery' ),
        'add_new_item'        => __( 'Add New MSV Gallery', 'msvgallery' ),
        'add_new'             => __( 'Add New', 'msvgallery' ),
        'edit_item'           => __( 'Edit MSV Gallery', 'msvgallery' ),
        'update_item'         => __( 'Update MSV Gallery', 'msvgallery' ),
        'search_items'        => __( 'Search MSV Gallery', 'msvgallery' ),
        'not_found'           => __( 'Not Found', 'msvgallery' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'msvgallery' ),
		'featured_image'        => __( 'Add SMV Gallery Image', 'msvgallery' ),
    );
	//Set other options for Custom Post Type
    $args = array(
        'label'               => __( 'msv_galleries', 'msvgallery' ),
        'description'         => __( 'MSV Galleries List', 'msvgallery' ),
        'labels'              => $labels,  
        'supports'            => array( 'title', 'thumbnail','excerpt','editor'),     
        'taxonomies'          => array( 'genres' ),     
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true, 
    );
    // Registering your Custom Post Type
    register_post_type( 'msv_galleries', $args );
 }
 add_action( 'init', 'msv_galleries_custom_postype', 0 );


// Add Styles to SMV Gallery Plugin
add_action( 
	'wp_enqueue_scripts', 
	'wpshout_enqueue_styles' 
);
// This function runs because of the add_action
function wpshout_enqueue_styles() {
	$file_url = plugins_url(
		'css/smv_gallery_styles.css', // File name
		__FILE__ 
					// the "current file"
	);
	// Actually load up the stylesheet
	wp_enqueue_style('sp_sytlesheet',$file_url);
}


 //Do Shortcode to display the images in frontend
 function smv_gallery($atts) {
	 
	$Content = "<style>\r\n";
	$Content .= "h3.demoClass {\r\n";
	$Content .= "color: #cf2e2e;\r\n";
	$Content .= "padding-top:30px;\r\n";
	$Content .= "text-align: center;\r\n";
	$Content .= "}\r\n";
	$Content .= "</style>\r\n";
	$Content .= '<h3 class="demoClass">Scrolling Portfolio - SMV Gallery Plugin</h3>';	 
    echo $Content;
	
	echo '<div class="demo-wrapper"><ul class="portfolio-items">';
	$args_events = array(
		'post_type' =>'msv_galleries', 
		'order' => 'DESC',
		'posts_per_page' => '-1',
	);

	$media_press_query = null;
	$media_press_query = new WP_Query($args_events);
	$results_current_page =  $media_press_query->post_count;

	if($media_press_query->have_posts() ) {
		while ($media_press_query->have_posts()) : $media_press_query->the_post();
		
		$get_feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		if($get_feat_image){  ?>
			<li class="item">
				<figure>
					<div class="view"> <img src="<?php echo plugin_dir_url( __FILE__ ); ?>/timthumb.php?src=<?php echo $get_feat_image; ?>&h=190&w=300&a=t" /> </div>
					<figcaption>
						<p><span><a href="<?php echo get_permalink(); ?>"><?php the_title(); ?></a></span></p>
						<p><span><?php the_excerpt(''); ?></span></p>
					</figcaption>
				</figure>
			</li>
		<?php } 
		endwhile;
		wp_reset_query();
	}	
	echo '</ul></div>';
 }
add_shortcode('smv-gallery-plugin', 'smv_gallery');


function add_this_script_footer(){ 
 wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . 'js/jquery-1.8.2.min.js' );
    wp_enqueue_script( 'my_custom_script1', plugin_dir_url( __FILE__ ) . 'js/modernizr-1.5.min.js' );
	wp_enqueue_script( 'my_custom_script2', plugin_dir_url( __FILE__ ) . 'js/jquery.mousewheel.js' );
	wp_enqueue_script( 'my_custom_script3', plugin_dir_url( __FILE__ ) . 'js/scripts.js' );
} 
//add script in wp_footer
add_action('wp_footer', 'add_this_script_footer');

/*Excerpt Custom code starts here*/
function smv_gallery_excerpt_label( $translation, $original ) {
    if ( 'Excerpt' == $original ) {
        return __( 'Enter Gallery Description' );
    } elseif ( false !== strpos( $original, 'Excerpts are optional hand-crafted summaries of your' ) ) {
        return __( 'Only 25 letters displays including space' );
    }
    return $translation;
}
add_filter( 'gettext', 'smv_gallery_excerpt_label', 10, 2 );

function my_excerpt_length($length){ return 25; } 
add_filter('excerpt_length', 'my_excerpt_length');
/*Excerpt Custom code ends here*/