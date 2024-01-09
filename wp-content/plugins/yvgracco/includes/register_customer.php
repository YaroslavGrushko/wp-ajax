<?php 
if( !defined('ABSPATH')) {
	exit;
}

/* YVG_Applications (in admin) */
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