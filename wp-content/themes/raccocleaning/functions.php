<?php
function raccocleaning_get_theme_version(){
	$theme = wp_get_theme();
	$theme_version = $theme->get('Version');
	return $theme_version;
}

/* Main scripts and styles */
 function raccocleaning_scripts(){
	$theme_version = raccocleaning_get_theme_version();
	// Theme's stylesheets:
	wp_enqueue_style( 'raccocleaning-style', get_template_directory_uri() . '/style.css', array(), $theme_version, 'all' );
	wp_enqueue_style( 'raccocleaning-critical-style', get_template_directory_uri() . '/assets/css/critical.css', array(), $theme_version, 'all' );
	wp_enqueue_style( 'raccocleaning-app-style', get_template_directory_uri() . '/assets/css/app.css', array(), $theme_version, 'all' );
	wp_enqueue_style( 'raccocleaning-fancybox-style', get_template_directory_uri() . '/assets/css/jquery.fancybox.min.css', array(), $theme_version, 'all' );

	// Theme's javascript files:
	wp_enqueue_script( 'jquery-js', get_template_directory_uri() . '/assets/js/jquery/3.6.0/jquery.min.js', $theme_version, false );
	wp_enqueue_script( 'lottie-js', get_template_directory_uri() . '/assets/js/lottie.min.js', array( 'jquery-js' ), $theme_version, false );
	wp_enqueue_script( 'inputmask-js', get_template_directory_uri() . '/assets/js/jquery.inputmask.min.js', array( 'jquery-js' ), $theme_version, true );
	wp_enqueue_script( 'jquery-validate-js', get_template_directory_uri() . '/assets/js/jquery.validate.min.js', array( 'jquery-js' ), $theme_version, true );
	wp_enqueue_script( 'additional-methods-js', get_template_directory_uri() . '/assets/js/additional-methods.min.js', array( 'jquery-js' ), $theme_version, true );
	wp_enqueue_script( 'jquery-fancybox-js', get_template_directory_uri() . '/assets/js/jquery.fancybox.min.js', array( 'jquery-js' ), $theme_version, true );
	wp_enqueue_script( 'swiper-js', get_template_directory_uri() . '/assets/js/swiper.js', array( 'jquery-js' ), $theme_version, true );
	// main js file
	wp_enqueue_script( 'app-js', get_template_directory_uri() . '/assets/js/app.js', array( 'jquery-js', 'jquery-validate-js' ), $theme_version, true );
	$variables = array(
        'ajaxurl' => admin_url( 'admin-ajax.php' )
    );
    wp_localize_script('app-js', "variables", $variables);
}
add_action( 'wp_enqueue_scripts', 'raccocleaning_scripts' );

/* YVG_Applications */
if( !class_exists('YVG_Applications') ){
	class YVG_Applications{
		public $post_type;
		
		function __construct(){
			$this->post_type = 'yvg_application';
			add_action('init', array($this, 'register_post_type'));
			add_action('init', array($this, 'exclude_yvg_application_from_post_search'));
		}
		
		function register_post_type(){
			$labels = array(
				'name' 				=> esc_html_x( 'Applications', 'general name', 'themeplugin' ),
				'singular_name' 	=> esc_html_x( 'Application', 'singular name', 'themeplugin' ),
				'add_new' 			=> esc_html_x( 'Add New', 'logo', 'themeplugin' ),
				'add_new_item' 		=> esc_html__( 'Add New', 'themeplugin' ),
				'edit_item' 		=> esc_html__( 'Edit Application', 'themeplugin' ),
				'new_item' 			=> esc_html__( 'New Application', 'themeplugin' ),
				'all_items' 		=> esc_html__( 'All Applications', 'themeplugin' ),
				'view_item' 		=> esc_html__( 'View Application', 'themeplugin' ),
				'search_items' 		=> esc_html__( 'Search Application', 'themeplugin' ),
				'not_found' 		=> esc_html__( 'No Applications', 'themeplugin' ),
				'not_found_in_trash'=> esc_html__( 'No Applications In Trash', 'themeplugin' ),
				'parent_item_colon' => '',
				'menu_name' 		=> esc_html__( 'Application', 'themeplugin' )
			);
			$args = array(
				'labels' 			=> $labels,
				'public' 			=> true,
				'publicly_queryable'=> true,
				'show_ui' 			=> true,
				'show_in_menu' 		=> true,
				'query_var' 		=> true,
				'rewrite' 			=> array( 'slug' => $this->post_type ),
				'capability_type' 	=> 'post',
				'has_archive' 		=> false,
				'hierarchical' 		=> false,
				'supports' 			=> array( 'title', 'editor' ),
				'menu_position' 	=> 5,
			);
			register_post_type( $this->post_type, $args );
		}
		
		// exclude yvg_application from post search results 
		function exclude_yvg_application_from_post_search() {
			global $wp_post_types;
			if (post_type_exists('yvg_application')) {
				// exclude from search results
				$wp_post_types['yvg_application']->exclude_from_search = true;
			}
		}
	}
}

new YVG_Applications();

// AJAX request from front-end:
add_action("wp_ajax_frontend_submit_action" , "frontend_submit_action");
add_action("wp_ajax_nopriv_frontend_submit_action" , "frontend_submit_action");

function frontend_submit_action(){
	if( 
		$_POST['name'] && 
		$_POST['tel'] && 
		$_POST['email'] && 
		$_POST['address'] && 
		$_POST['service'] && 
		$_POST['square'] && 
		$_POST['bedrooms'] && 
		$_POST['bathrooms']
		){
			$name = sanitize_text_field( wp_unslash( $_POST['name'] ) );
			$tel = sanitize_text_field( wp_unslash( $_POST['tel'] ) );
			$email = sanitize_text_field( wp_unslash( $_POST['email'] ) );
			$address = sanitize_text_field( wp_unslash( $_POST['address'] ) );
			$service = sanitize_text_field( wp_unslash( $_POST['service'] ) );
			$square = sanitize_text_field( wp_unslash( $_POST['square'] ) );
			$bedrooms = sanitize_text_field( wp_unslash( $_POST['bedrooms'] ) );
			$bathrooms = sanitize_text_field( wp_unslash( $_POST['bathrooms'] ) );

			if ( $name && $tel && $email && $address && $service && $square && $bedrooms && $bathrooms ) {
				$content ="
Name: " . $name . "\n			
Telephone: " . $tel . "\n
Email: " . $email . "\n
Address: " . $address . "\n
Service: " . $service . "\n
Square: " . $square . "\n
Bedrooms: " . $bedrooms . "\n
Bathrooms: " . $bathrooms . "\n";

				wp_insert_post(
					array(
						'post_title'  => $name,
						'post_content'  => $content,
						'post_type'   => 'yvg_application',
						'post_status' => 'publish', /* Or "draft", if required */
					)       
				);
				wp_die();
			}
	}
    
}