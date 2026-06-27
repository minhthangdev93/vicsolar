<?php
/**
 * Article block 5 — 6 thành phần.
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading = electro_child_landing_article_get( 's10_article_b5_heading' );
$intro   = electro_child_landing_article_get( 's10_article_b5_intro' );
$footer  = electro_child_landing_article_get( 's10_article_b5_footer' );
$rows    = electro_child_landing_article_get_repeater( 's10_article_b5_features' );
?>
<section id="h2-5" class="vns-a-sec">
	<?php if ( $heading ) : ?>
		<h2 id="h2-5-h"><?php echo electro_child_landing_kses_content( $heading ); ?></h2>
	<?php endif; ?>
	<?php if ( $intro ) : ?>
		<p><?php echo electro_child_landing_kses_content( $intro ); ?></p>
	<?php endif; ?>
	<?php if ( ! empty( $rows ) ) : ?>
		<div class="vns-features">
			<?php foreach ( $rows as $row ) : ?>
				<?php $feat = electro_child_landing_article_feature_row_for_template( $row ); ?>
				<div class="vns-feature">
					<?php if ( $feat['tag'] ) : ?>
						<p><span class="vns-feature-tag tag-<?php echo esc_attr( $feat['tag_style'] ); ?>"><?php echo electro_child_landing_kses_content( $feat['tag'] ); ?></span></p>
					<?php endif; ?>
					<?php if ( $feat['title'] ) : ?>
						<h3 class="vns-feature-title"><?php echo electro_child_landing_kses_content( $feat['title'] ); ?></h3>
					<?php endif; ?>
					<?php if ( $feat['desc'] ) : ?>
						<p class="vns-feature-desc"><?php echo electro_child_landing_kses_content( $feat['desc'] ); ?></p>
					<?php endif; ?>
					<?php if ( $feat['spec'] ) : ?>
						<div class="vns-feature-spec"><?php echo electro_child_landing_kses_content( $feat['spec'] ); ?></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php if ( $footer ) : ?>
		<p><?php echo electro_child_landing_kses_content( $footer ); ?></p>
	<?php endif; ?>
</section>
