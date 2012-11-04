<?php
/*
Plugin Name: OptionTree Attachments Checkbox
Plugin URI: http://purethemes.net
Description: Quickly and easily exclude images from attachment list
Version: 0.1
Author: purethemes
Author URI: http://themeforest.net/user/purethemes
License: GPL2
*/


function post_attachment_checkbox_type($types) {
	$new_option = array('post_attachments_checkbox' => 'Attachments Checkbox');
	$result = array_merge($types, $new_option);
	return $result;
}

add_filter('ot_option_types_array', 'post_attachment_checkbox_type');


function otie_admin_scripts() {
	wp_enqueue_style( 'otie-style',  plugin_dir_url( __FILE__ ) . 'css/ot-image-exl.css');	 
	wp_enqueue_script( 'otie-script',  plugin_dir_url( __FILE__ ) . 'js/ot-image-exl.js', array( 'jquery' ) );

}
add_action( 'admin_enqueue_scripts', 'otie_admin_scripts' );




function ot_type_attachments_checkbox( $args = array() ) {
	/* turns arguments array into variables */
	extract( $args );
	global $post;

	$current_post_id = $post->ID;

	/* verify a description */
	$has_desc = $field_desc ? true : false;

	/* format setting outer wrapper */
	echo '<div class="format-setting type-post_attachments_checkbox type-checkbox ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

	/* description */
	echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

	/* format setting inner wrapper */
	echo '<div class="format-setting-inner">';

	/* setup the post types */
	$post_type = isset( $field_post_type ) ? explode( ',', $field_post_type ) : array( 'post' );
	global $pagenow;
	if($pagenow == 'themes.php' ) {
		$args = array(
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'post_mime_type' => 'image',
			'posts_per_page' => '-1',
			'order' => 'ASC',
			'orderby' => 'menu_order'
			);
	} else {
		$args = array(
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'post_parent' => $current_post_id,
			'post_mime_type' => 'image',
			'posts_per_page' => '-1',
			'order' => 'ASC',
			'orderby' => 'menu_order'
			);
	}

	/* query posts array */
	$query = new WP_Query( $args  );

	/* has posts */
	if ( $query->have_posts() ) {
		$count = 0;
		echo '<input id="this_field_id" type="hidden" value="'. esc_attr( $field_id ).'" />' ;
		echo '<input id="this_field_name" type="hidden" value="'. esc_attr( $field_name ).'" />' ;
		echo '<ul id="option-tree-attachments-list">';
		while ( $query->have_posts() ) {
			$query->the_post();
			echo '<li>';
			$thumbnail = wp_get_attachment_image_src( $query->post->ID, 'thumbnail');
			echo '<img  src="' . $thumbnail[0] . '" alt="' . apply_filters('the_title', $image->post_title). '"/>';
			echo '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $count ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $count ) . '" value="' . esc_attr( get_the_ID() ) . '" ' . ( isset( $field_value[$count] ) ? checked( $field_value[$count], get_the_ID(), false ) : '' ) . ' class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '" />';
			echo '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $count ) . '">' . get_the_title() . '</label>';
			echo '</li>';
			$count++;
		} 
		echo "</ul>";
	} else {
		echo '<p>' . __( 'No Posts Found', 'option-tree' ) . '</p>';
	}
	echo '<a title="Update" class="option-tree-attachments-update option-tree-ui-button blue right hug-right" href="#">Update</a>';
	echo '</div>';

	echo '</div>';
}

function ot_type_attachments_ajax_update() {
	if ( !empty( $_POST['post_id'] ) )  {
			$args = array(
					'post_type' => 'attachment',
					'post_status' => 'inherit',
					'post_parent' => $_POST['post_id'],
					'post_mime_type' => 'image',
					'posts_per_page' => '-1',
					'order' => 'ASC',
					'orderby' => 'menu_order',
					'exclude'     => get_post_thumbnail_id($_POST['post_id'])
				);				
				
			
			$return = '';						
				/* query posts array */
	$query = new WP_Query( $args  );
	$post_type = isset( $field_post_type ) ? explode( ',', $field_post_type ) : array( 'post' );
	/* has posts */
	if ( $query->have_posts() ) {
		$count = 0;
		$field_id = $_POST['field_id'];
		$field_name = $_POST['field_name'];
		while ( $query->have_posts() ) {
			$query->the_post();
			$return .= '<li>';
			$thumbnail = wp_get_attachment_image_src( $query->post->ID, 'thumbnail');
			$return .=  '<img  src="' . $thumbnail[0] . '" alt="' . apply_filters('the_title', $image->post_title). '"/>';
			$return .=  '<input type="checkbox" name="' . esc_attr( $field_name ) . '[' . esc_attr( $count ) . ']" id="' . esc_attr( $field_id ) . '-' . esc_attr( $count ) . '" value="' . esc_attr( get_the_ID() ) . '" ' . ( isset( $field_value[$count] ) ? checked( $field_value[$count], get_the_ID(), false ) : '' ) . ' class="option-tree-ui-checkbox ' . esc_attr( $field_class ) . '" />';
			$return .=  '<label for="' . esc_attr( $field_id ) . '-' . esc_attr( $count ) . '">' . get_the_title() . '</label>';
			$return .=  '</li>';
			$count++;
		} 
	
	} else {
		$return .=  '<p>' . __( 'No Posts Found', 'option-tree' ) . '</p>';
	}			
			echo $return;
			exit();
	}
}

add_action( 'wp_ajax_attachments_update', 'ot_type_attachments_ajax_update' );

?>