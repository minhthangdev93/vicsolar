<?php
/**
 * Block spam comments / WooCommerce product reviews.
 *
 * @package electro-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hidden honeypot field (bots fill this; humans never see it).
 */
function electro_child_comment_spam_honeypot_field() {
	echo '<p class="vs-comment-hp" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;" aria-hidden="true">';
	echo '<label for="vs_comment_hp">' . esc_html__( 'Để trống', 'electro-child' ) . '</label>';
	echo '<input type="text" name="vs_comment_hp" id="vs_comment_hp" value="" tabindex="-1" autocomplete="off" />';
	echo '</p>';
}
add_action( 'comment_form', 'electro_child_comment_spam_honeypot_field', 1 );

/**
 * @return string[]
 */
function electro_child_comment_spam_reasons( $commentdata ) {
	$reasons = array();

	$author  = isset( $commentdata['comment_author'] ) ? trim( (string) $commentdata['comment_author'] ) : '';
	$email   = isset( $commentdata['comment_author_email'] ) ? trim( (string) $commentdata['comment_author_email'] ) : '';
	$content = isset( $commentdata['comment_content'] ) ? trim( (string) $commentdata['comment_content'] ) : '';

	if ( ! empty( $_POST['vs_comment_hp'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$reasons[] = 'honeypot';
	}

	// Bot pattern: Layla1612 + layla1612@gmail.com
	if ( preg_match( '/^[A-Za-z]+\d{3,5}$/', $author ) ) {
		$reasons[] = 'bot_author_name';
	}

	if ( '' !== $email && preg_match( '/^[a-z]+\d{3,5}@gmail\.com$/i', $email ) ) {
		$reasons[] = 'bot_author_email';
	}

	if ( '' !== $email && '' !== $author && 0 === strcasecmp( $email, $author . '@gmail.com' ) ) {
		$reasons[] = 'name_email_match';
	}

	// Comment is only a link (common spam).
	if ( '' !== $content && preg_match( '/^\s*https?:\/\/\S+\s*$/iu', $content ) ) {
		$reasons[] = 'link_only';
	}

	// Short-link / affiliate spam domains.
	if ( '' !== $content && preg_match(
		'#https?://(?:www\.)?(?:shorturl\.at|bit\.ly|tinyurl\.com|t\.co|goo\.gl|ow\.ly|is\.gd|buff\.ly|cutt\.ly|rb\.gy|s\.id|rebrand\.ly)#iu',
		$content
	) ) {
		$reasons[] = 'short_url';
	}

	// Multiple URLs in a short comment.
	if ( '' !== $content && preg_match_all( '#https?://#iu', $content, $matches ) && count( $matches[0] ) >= 2 ) {
		$reasons[] = 'multi_url';
	}

	// Product reviews with any external link are almost always spam.
	$post_id = isset( $commentdata['comment_post_ID'] ) ? (int) $commentdata['comment_post_ID'] : 0;
	if ( $post_id && 'product' === get_post_type( $post_id ) && preg_match( '#https?://#iu', $content ) ) {
		$reasons[] = 'product_review_link';
	}

	return array_unique( $reasons );
}

/**
 * Reject honeypot hits before save.
 *
 * @param array<string, mixed> $commentdata Comment data.
 * @return array<string, mixed>
 */
function electro_child_comment_spam_preprocess( $commentdata ) {
	if ( ! empty( $_POST['vs_comment_hp'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		wp_die( esc_html__( 'Không thể gửi bình luận.', 'electro-child' ), esc_html__( 'Lỗi', 'electro-child' ), array( 'response' => 403 ) );
	}

	return $commentdata;
}
add_filter( 'preprocess_comment', 'electro_child_comment_spam_preprocess', 1 );

/**
 * Mark suspicious comments as spam (WooCommerce reviews use the same table).
 *
 * @param int|string           $approved    Approval status.
 * @param array<string, mixed> $commentdata Comment data.
 * @return int|string
 */
function electro_child_comment_spam_approve( $approved, $commentdata ) {
	if ( 'spam' === $approved || 'trash' === $approved ) {
		return $approved;
	}

	$reasons = electro_child_comment_spam_reasons( $commentdata );
	if ( ! empty( $reasons ) ) {
		return 'spam';
	}

	return $approved;
}
add_filter( 'pre_comment_approved', 'electro_child_comment_spam_approve', 20, 2 );

/**
 * WooCommerce review form: rating required, reduce drive-by spam.
 *
 * @param array<string, mixed> $fields Default fields.
 * @return array<string, mixed>
 */
function electro_child_wc_review_require_rating( $fields ) {
	if ( ! is_product() ) {
		return $fields;
	}

	$fields['author']['required'] = true;
	$fields['email']['required']  = true;

	return $fields;
}
add_filter( 'comment_form_default_fields', 'electro_child_wc_review_require_rating' );

/**
 * Hold non-logged-in product reviews for moderation (extra safety).
 *
 * @param int|string           $approved    Approval status.
 * @param array<string, mixed> $commentdata Comment data.
 * @return int|string
 */
function electro_child_wc_review_moderate_guests( $approved, $commentdata ) {
	if ( is_user_logged_in() || 'spam' === $approved ) {
		return $approved;
	}

	$post_id = isset( $commentdata['comment_post_ID'] ) ? (int) $commentdata['comment_post_ID'] : 0;
	if ( $post_id && 'product' === get_post_type( $post_id ) ) {
		return 0;
	}

	return $approved;
}
add_filter( 'pre_comment_approved', 'electro_child_wc_review_moderate_guests', 30, 2 );
