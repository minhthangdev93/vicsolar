<?php
/**
 * Block spam comments on blog posts and WooCommerce product reviews.
 *
 * @package electro-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @param int $post_id Post ID.
 * @return bool
 */
function electro_child_comment_spam_is_blog_post( $post_id ) {
	return $post_id > 0 && 'post' === get_post_type( $post_id );
}

/**
 * @param int $post_id Post ID.
 * @return bool
 */
function electro_child_comment_spam_is_product( $post_id ) {
	return $post_id > 0 && 'product' === get_post_type( $post_id );
}

/**
 * Hidden honeypot + submit timestamp (bots submit instantly).
 */
function electro_child_comment_spam_honeypot_field() {
	echo '<p class="vs-comment-hp" style="position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;" aria-hidden="true">';
	echo '<label for="vs_comment_hp">' . esc_html__( 'Để trống', 'electro-child' ) . '</label>';
	echo '<input type="text" name="vs_comment_hp" id="vs_comment_hp" value="" tabindex="-1" autocomplete="off" />';
	echo '<input type="hidden" name="vs_comment_ts" value="' . esc_attr( (string) time() ) . '" />';
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
	$url     = isset( $commentdata['comment_author_url'] ) ? trim( (string) $commentdata['comment_author_url'] ) : '';
	$content = isset( $commentdata['comment_content'] ) ? trim( (string) $commentdata['comment_content'] ) : '';
	$post_id = isset( $commentdata['comment_post_ID'] ) ? (int) $commentdata['comment_post_ID'] : 0;
	$plain   = trim( wp_strip_all_tags( $content ) );

	if ( ! empty( $_POST['vs_comment_hp'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$reasons[] = 'honeypot';
	}

	if ( isset( $_POST['vs_comment_ts'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$submitted_at = (int) $_POST['vs_comment_ts']; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		if ( $submitted_at > 0 && ( time() - $submitted_at ) < 3 ) {
			$reasons[] = 'too_fast';
		}
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

	// Disposable / throwaway inboxes.
	if ( '' !== $email && preg_match(
		'/@(?:mailinator\.com|tempmail\.|guerrillamail|10minutemail|yopmail\.com|discard\.|trashmail\.)/i',
		$email
	) ) {
		$reasons[] = 'disposable_email';
	}

	// Comment is only a link (common spam).
	if ( '' !== $content && preg_match( '/^\s*https?:\/\/\S+\s*$/iu', $content ) ) {
		$reasons[] = 'link_only';
	}

	// Short-link / affiliate spam domains.
	if ( '' !== $content && preg_match(
		'#https?://(?:www\.)?(?:shorturl\.at|bit\.ly|tinyurl\.com|t\.co|goo\.gl|ow\.ly|is\.gd|buff\.ly|cutt\.ly|rb\.gy|s\.id|rebrand\.ly|clk\.|lnk\.to)#iu',
		$content
	) ) {
		$reasons[] = 'short_url';
	}

	// Multiple URLs in a short comment.
	if ( '' !== $content && preg_match_all( '#https?://#iu', $content, $matches ) && count( $matches[0] ) >= 2 ) {
		$reasons[] = 'multi_url';
	}

	// HTML link injection.
	if ( '' !== $content && preg_match( '/<a\s+[^>]*href\s*=/iu', $content ) ) {
		$reasons[] = 'html_link';
	}

	// Product reviews: any external link.
	if ( electro_child_comment_spam_is_product( $post_id ) && preg_match( '#https?://#iu', $content ) ) {
		$reasons[] = 'product_review_link';
	}

	// Blog post comments — stricter rules.
	if ( electro_child_comment_spam_is_blog_post( $post_id ) ) {
		if ( '' !== $url ) {
			$reasons[] = 'post_author_url';
		}

		if ( preg_match( '#https?://#iu', $content ) || preg_match( '#\bwww\.[a-z0-9-]+\.[a-z]{2,}\b#iu', $content ) ) {
			$reasons[] = 'post_body_link';
		}

		if ( '' !== $plain && mb_strlen( $plain ) < 12 ) {
			$reasons[] = 'post_too_short';
		}

		// SEO / pharma spam keywords (Latin).
		if ( '' !== $plain && preg_match(
			'/\b(?:viagra|cialis|casino|porn|escort|forex|crypto\s*signal|buy\s+followers|seo\s+service)\b/iu',
			$plain
		) ) {
			$reasons[] = 'post_spam_keyword';
		}

		// Mostly non-Latin / Vietnamese content with links is rare on this site.
		if ( '' !== $plain && preg_match( '#https?://#iu', $content ) ) {
			$latin_vn = preg_match_all( '/[a-zA-Zàáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ\s\d]/iu', $plain, $safe_chars );
			$total    = mb_strlen( $plain );
			if ( $total > 0 && ( $latin_vn / $total ) < 0.5 ) {
				$reasons[] = 'post_link_gibberish';
			}
		}
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
 * Mark suspicious comments as spam.
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
 * Hide website URL field on blog posts (bots abuse it; readers rarely need it).
 *
 * @param array<string, mixed> $fields Default fields.
 * @return array<string, mixed>
 */
function electro_child_post_comment_remove_url_field( $fields ) {
	if ( is_singular( 'post' ) || ( is_singular() && 'post' === get_post_type() ) ) {
		unset( $fields['url'] );
	}

	return $fields;
}
add_filter( 'comment_form_default_fields', 'electro_child_post_comment_remove_url_field', 20 );

/**
 * Hold guest comments on posts and product reviews for manual approval.
 *
 * @param int|string           $approved    Approval status.
 * @param array<string, mixed> $commentdata Comment data.
 * @return int|string
 */
function electro_child_comment_moderate_guests( $approved, $commentdata ) {
	if ( is_user_logged_in() || 'spam' === $approved ) {
		return $approved;
	}

	$post_id = isset( $commentdata['comment_post_ID'] ) ? (int) $commentdata['comment_post_ID'] : 0;
	if ( ! $post_id ) {
		return $approved;
	}

	$post_type = get_post_type( $post_id );
	if ( in_array( $post_type, array( 'post', 'product' ), true ) ) {
		return 0;
	}

	return $approved;
}
add_filter( 'pre_comment_approved', 'electro_child_comment_moderate_guests', 30, 2 );
