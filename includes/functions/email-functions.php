<?php
/**
 * Set of functions that deals with the emails of the plugin.
 *
 * @package     wp-user-manager
 * @copyright   Copyright (c) 2018, Alessandro Tesoro
 * @license     https://opensource.org/licenses/gpl-license GNU Public License
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Retrieve the list of available email templates.
 *
 * @return array
 */
function wpum_get_email_templates() {
	return WPUM()->emails->get_templates();
}

/**
 * Retrieve a formatted list of all registered email tags.
 *
 * @return string
 */
function wpum_get_emails_tags_list() {
	$list = '';
	$email_tags = WPUM()->emails->get_tags();
	if( count( $email_tags ) > 0 ) {
		foreach( $email_tags as $email_tag ) {
			$list .= '{' . $email_tag['tag'] . '} - ' . $email_tag['description'] . '<br />';
		}
	}
	return $list;
}

/**
 * Parse the {website} tag into the email to display the site url.
 *
 * @param string $user_id
 * @return void
 */
function wpum_email_tag_website( $user_id ) {
	return home_url();
}

/**
 * Parse the {sitename} tag into the email to display the site name.
 *
 * @param string $user_id
 * @return void
 */
function wpum_email_tag_sitename( $user_id ) {
	return esc_html( get_bloginfo( 'name' ) );
}

/**
 * Parse the {username} tag into the email to display the user's username.
 *
 * @param string $user_id
 * @return void
 */
function wpum_email_tag_username( $user_id ) {

	$user     = get_user_by( 'id', $user_id );
	$username = '';

	if( $user instanceof WP_User ) {
		$username = $user->data->user_login;
	}

	return $username;
}

/**
 * Parse the {email} tag into the email to display the user's email.
 *
 * @param string $user_id
 * @return void
 */
function wpum_email_tag_email( $user_id ) {

	$user     = get_user_by( 'id', $user_id );
	$email = '';

	if( $user instanceof WP_User ) {
		$email = $user->data->user_email;
	}

	return $email;
}

/**
 * Parse the {firstname} tag into the email to display the user's first name.
 *
 * @param string $user_id
 * @return void
 */
function wpum_email_tag_firstname( $user_id ) {

	$firstname = get_user_meta( $user_id, 'first_name', true );

	return $firstname;
}

/**
 * Parse the {lastname} tag into the email to display the user's last name.
 *
 * @param string $user_id
 * @return void
 */
function wpum_email_tag_lastname( $user_id ) {

	$firstname = get_user_meta( $user_id, 'last_name', true );

	return $firstname;
}

/**
 * Parse the {login_page_url} tag into the email to display the site login page url.
 *
 * @param string $user_id
 * @return void
 */
function wpum_email_tag_login_page_url( $user_id = false ) {

	$login_page_url = wpum_get_core_page_id( 'login' );
	$login_page_url = get_permalink( $login_page_url );

	$url = '<a href="'. esc_url( $login_page_url ) .'">' . esc_html( $login_page_url ) . '</a>';

	return $url;
}

/**
 * Retrieve registered emails
 *
 * @return array
 */
function wpum_get_registered_emails() {

	$emails = [
		'registration_confirmation' => [
			'status'            => 'active',
			'name'              => esc_html__( 'Registration confirmation' ),
			'description'       => esc_html__( 'This is the email that is sent to the user upon successful registration.' ),
			'recipient'         => esc_html__( 'User\'s email.' ),
		],
		'password_recovery_request' => [
			'status'            => 'active',
			'name'              => esc_html__( 'Password recovery request' ),
			'description'       => esc_html__( 'This is the email that is sent to the visitor upon password reset request.' ),
			'recipient'         => esc_html__( 'Email address of the requested user.' ),
		]
	];

	return apply_filters( 'wpum_registered_emails', $emails );

}

/**
 * Retrieve data of a stored email from the database.
 *
 * @param string|boolean $email_id
 * @param string|boolean $field_id
 * @return void
 */
function wpum_get_email_field( $email_id = false, $field_id = false ) {

	if( ! $email_id || ! $field_id ) {
		return false;
	}

	$output = false;
	$stored_email = get_option( 'wpum_email', false );

	if( ! empty( $stored_email ) && is_array( $stored_email ) && array_key_exists( $email_id, $stored_email ) ) {
		$found_email = $stored_email[ $email_id ];
		if( array_key_exists( $field_id, $found_email ) ) {
			$output = $found_email[ $field_id ];
		}
	}

	return $output;

}

/**
 * Retrieve details about emails stored into the database.
 *
 * @param string $email_id
 * @return void
 */
function wpum_get_email( $email_id = false ) {

	$email = false;

	if( ! $email_id ) {
		return false;
	}

	$emails = get_option( 'wpum_email', false );

	if( array_key_exists( $email_id, $emails ) && is_array( $emails[ $email_id ] ) ) {
		$email = $emails[ $email_id ];
	}

	return $email;

}
