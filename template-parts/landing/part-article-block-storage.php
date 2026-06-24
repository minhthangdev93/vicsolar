<?php
/**
 * Article block 8 — Pin lưu trữ.
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading = electro_child_landing_article_get( 's10_article_b8_heading' );
$para_1  = electro_child_landing_article_get( 's10_article_b8_para_1' );
$para_2  = electro_child_landing_article_get( 's10_article_b8_para_2' );
$para_3  = electro_child_landing_article_get( 's10_article_b8_para_3' );
$callout_title = electro_child_landing_article_get( 's10_article_b8_callout_title' );
$callout_body  = electro_child_landing_article_get( 's10_article_b8_callout_body' );
?>
<section id="h2-8" class="vns-a-sec">
	<?php if ( $heading ) : ?>
		<h2 id="h2-8-h"><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>
	<?php if ( $para_1 ) : ?>
		<p><?php echo esc_html( $para_1 ); ?></p>
	<?php endif; ?>
	<?php if ( $para_2 ) : ?>
		<p><?php echo esc_html( $para_2 ); ?></p>
	<?php endif; ?>
	<?php if ( $para_3 ) : ?>
		<p><?php echo esc_html( $para_3 ); ?></p>
	<?php endif; ?>
	<?php if ( $callout_title || $callout_body ) : ?>
		<div class="vns-callout">
			<?php if ( $callout_title ) : ?>
				<h3><?php echo esc_html( $callout_title ); ?></h3>
			<?php endif; ?>
			<?php if ( $callout_body ) : ?>
				<p><?php echo esc_html( $callout_body ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
