<?php
/**
 * Article block 7 — Inverter.
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading = electro_child_landing_article_get( 's10_article_b7_heading' );
$intro   = electro_child_landing_article_get( 's10_article_b7_intro' );
$cards   = electro_child_landing_article_get_repeater( 's10_article_b7_cards' );
$answer  = electro_child_landing_article_get( 's10_article_b7_answer' );
?>
<section id="h2-7" class="vns-a-sec">
	<?php if ( $heading ) : ?>
		<h2 id="h2-7-h"><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>
	<?php if ( $intro ) : ?>
		<p><?php echo esc_html( $intro ); ?></p>
	<?php endif; ?>
	<?php if ( ! empty( $cards ) ) : ?>
		<div class="vns-cmp2">
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
	<?php if ( $answer ) : ?>
		<div class="vns-answer">
			<p class="vns-answer-text"><?php echo electro_child_landing_kses_rich( $answer ); ?></p>
		</div>
	<?php endif; ?>
</section>
