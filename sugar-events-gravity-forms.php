<?php

/**
 * Plugin Name:       Sugar Calendar - Gravity Forms Bridge
 * Plugin URI:        https://sugarcalendar.com
 * Description:       Create event registration forms with Gravity Forms
 * Author:            Sandhills Development, LLC
 * Author URI:        https://sandhillsdev.com
 * Version:           1.2.0
 * Text Domain:       sugar-event-calendar-gravity-forms
 * Requires PHP:      5.6.20
 * Requires at least: 5.1
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

/**
 * Load text domain
 *
 * @since 1.0
 */
function scgf_load_plugin_textdomain() {
	load_plugin_textdomain( 'sugar-event-calendar-gravity-forms' );
}
add_action( 'init', 'scgf_load_plugin_textdomain' );

/**
 * Add fields to meta box
 *
 * @since 1.0
 */
function scgf_add_forms_meta_box() {

	// Get the global post
	$post = get_post();

	$selected_form = get_post_meta( $post->ID, 'sc_event_gf_form',             true );
	$title         = get_post_meta( $post->ID, 'sc_event_gf_form_title',       true ) ? true : false;
	$description   = get_post_meta( $post->ID, 'sc_event_gf_form_description', true ) ? true : false;

	// Make sure Gravity Forms is available (avoids fatal error)
	$forms = class_exists( 'RGFormsModel' )
		? RGFormsModel::get_forms( null, 'title' )
		: array();

	echo '<tr class="sc_meta_box_row">';

		echo '<th class="sc_meta_box_td" valign="top"><label for="sc_event_gf_forms">' . __( 'Gravity Forms', 'sugar-event-calendar-gravity-forms' ) . '</label></th>';

		if ( count( $forms ) > 0 ) {

			echo '<td class="sc_meta_box_td">';

				echo '<select name="sc_event_gf_form">';

					echo '<option value="none">' . __( 'None', 'sugar-event-calendar-gravity-forms' ) . '</option>';

					foreach ( $forms as $form ) {

						echo '<option value="' . esc_attr( $form->id ) . '" ' . selected( $selected_form, $form->id, false ) . '>' . esc_html( $form->title ) . '</option>';

					}

				echo '</select>&nbsp;';

				echo '<span class="description">' . __( 'Select a form to display on the details page for this event.', 'sugar-event-calendar-gravity-forms' ) . '</span><br/>';

				echo '<p class="scgf_form_title">';
					echo '<input type="checkbox" name="sc_event_gf_form_title" value="1" ' . checked( $title, true, false ) . '/>&nbsp;';
					echo '<span class="description">' . __( 'Show the form title?', 'sugar-event-calendar-gravity-forms' ) . '</span><br/>';
				echo '</p>';

				echo '<p class="scgf_form_desc">';
					echo '<input type="checkbox" name="sc_event_gf_form_description" value="1" ' . checked( $description, true, false ) . '/>&nbsp;';
					echo '<span class="description">' . __( 'Show the form description?', 'sugar-event-calendar-gravity-forms' ) . '</span><br/>';
				echo '</p>';

					echo '<input type="hidden" name="scgf_meta_box_nonce" value="' . esc_attr( wp_create_nonce( basename( __FILE__ ) ) ) . '" />';

			echo '</td>';

		} else {

			echo '<td class="sc_meta_box_td">';
				echo '<p>' . __( 'You have not created any forms yet.', 'sugar-event-calendar-gravity-forms' ) . '</p>';
			echo '</td>';

		}

	echo '</tr>';
}
add_action( 'sc_event_meta_box_after', 'scgf_add_forms_meta_box' );

/**
 * Save Gravity Form Field
 *
 * Save data from meta box.
 *
 * @access private
 * @since  1.0
 * @return void
*/
function scgf_meta_box_save( $post_id = 0 ) {

	// verify nonce
	if ( empty( $_POST['scgf_meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['scgf_meta_box_nonce'], basename( __FILE__ ) ) ) {
		return;
	}

	// check bulk
	if ( isset( $_REQUEST['bulk_edit'] ) ) {
		return;
	}

	// Get the global post
	$post = get_post();

	// check revision, autosave, ajax
	if ( wp_is_post_revision( $post ) || wp_is_post_autosave( $post ) || wp_doing_ajax() ) {
		return;
	}

	// check permissions
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Sanitize values
	$form_id     = sanitize_text_field( $_POST['sc_event_gf_form'] );
	$title       = isset( $_POST['sc_event_gf_form_title']       ) ? true : false;
	$description = isset( $_POST['sc_event_gf_form_description'] ) ? true : false;

	// Update values
	update_post_meta( $post_id, 'sc_event_gf_form',             $form_id     );
	update_post_meta( $post_id, 'sc_event_gf_form_title',       $title       );
	update_post_meta( $post_id, 'sc_event_gf_form_description', $description );
}
add_action( 'save_post', 'scgf_meta_box_save' );

/**
 * Display Gravity Form
 *
 * @since 1.0
 */
function scgf_show_form( $event_id ) {

	// Bail if no Gravity Forms (avoids fatal error)
	if ( ! function_exists( 'gravity_form' ) ) {
		return;
	}

	// Get the form
	$form = get_post_meta( $event_id, 'sc_event_gf_form', true );

	// Bail if no form
	if ( empty( $form ) || ( 'none' === $form ) ) {
		return;
	}

	// Get form attributes
	$title        = get_post_meta( $event_id, 'sc_event_gf_form_title',       true ) ? true : false;
	$description  = get_post_meta( $event_id, 'sc_event_gf_form_description', true ) ? true : false;

	// Filter values
	$field_values = apply_filters( 'scgf_gravity_form_field_values', array(
		'event_id'    => $event_id,
		'event_title' => get_the_title( $event_id ),
	) );

	// Display the form
	gravity_form( $form, $title, $description, false, $field_values, true );
}
add_action( 'sc_after_event_content', 'scgf_show_form' );
