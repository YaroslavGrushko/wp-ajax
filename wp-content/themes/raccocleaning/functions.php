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