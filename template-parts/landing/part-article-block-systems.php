<?php
/**
 * Article block 4 — 3 loại hệ thống.
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading = electro_child_landing_article_get( 's10_article_b4_heading' );
$intro   = electro_child_landing_article_get( 's10_article_b4_intro' );
$cards   = electro_child_landing_article_get_repeater( 's10_article_b4_cards' );
?>
<section id="h2-4" class="vns-a-sec">
	<?php if ( $heading ) : ?>
		<h2 id="h2-4-h"><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>
	<?php if ( $intro ) : ?>
		<p><?php echo esc_html( $intro ); ?></p>
	<?php endif; ?>
	<?php if ( ! empty( $cards ) ) : ?>
		<div class="vns-cmp3">
			<?php
			foreach ( $cards as $row ) {
				get_template_part(
					'template-parts/landing/part',
					'article-cmp-card',
					array( 'card' => electro_child_landing_article_cmp_row_for_template( $row ) )
				);
			}
			?>
		</div>
	<?php endif; ?>
</section>
