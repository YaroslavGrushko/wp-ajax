<?php 
/**
 * Plugin Name: YvgRacco
 * Description: Add some additional functionality to Raccocleaning theme.
 * Version: 1.0.0
 * Author: Yaroslav Grushko
 * Author URI: https://yvg.com.ua/
 * Text Domain: yvgracco
 * Domain Path: /languages/
 */

 if( !defined('YVGRACCO_VERSION') ){
	define('YVGRACCO_VERSION', '1.0.0');
}

if( !defined('YVGRACCO_DIR') ){
	define('YVGRACCO_DIR', plugin_dir_path( __FILE__ ));
}

class Yvg_Racco {

    function __construct(){
        // AJAX request from front-end:
        add_action("wp_ajax_frontend_submit_action" , array($this, "yvg_frontend_submit_action") );
        add_action("wp_ajax_nopriv_frontend_submit_action" , array($this, "yvg_frontend_submit_action") );

        // register custom post type 
		require_once('includes/register_customer.php');

		if(is_admin()){
			// plugin activation handler
			register_activation_hook( __FILE__, array($this, 'yvg_add_customer_user_type') );
			// plugin deactivation handler
			register_deactivation_hook( __FILE__, array($this, 'yvg_remove_customer_user_type') );
		}
	}

    // Customer user type:
    function yvg_add_customer_user_type() {
        add_role(
            'yvg_customer',
            'Customer',
            array(
                'read' => true,
                'edit_posts' => true
            )
        );
    }

    function yvg_remove_customer_user_type() {
        remove_role('yvg_customer');
    }

    function yvg_frontend_submit_action(){
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
    
                    // add application to admin panel
                    wp_insert_post(
                        array(
                            'post_title'  => $name,
                            'post_content'  => $content,
                            'post_type'   => 'yvg_application',
                            'post_status' => 'publish', /* Or "draft", if required */
                        )       
                    );
    
                    // send application to admin email
                    $this->yvg_mail_to_admin($content);
    
                    // create new user (customer type)
                    $user_id = wp_create_user( $name, '', $email );
                    $user_id_role = new WP_User($user_id);
                    $user_id_role->set_role('yvg_customer');
    
                    wp_die();
                }
        }
    }
    
    function yvg_mail_to_admin($content){
    
        $admin_mail = get_option('admin_email');
        $to = $admin_mail;
        $from = __( 'From ', 'yvgraccocleaning' ) . $admin_mail;
        $subject = __( 'Cleaning Application', 'yvgraccocleaning' );
    
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            $from,
        );
    
        $mail_content = str_replace("\n", "<br>", $content);
    
        wp_mail($to, $subject, $mail_content, $headers);
    }
}

new Yvg_Racco();