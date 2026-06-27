<?php
/**
 * Article block 6 — Aiko vs Panasonic.
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading = electro_child_landing_article_get( 's10_article_b6_heading' );
$badge   = electro_child_landing_article_get( 's10_article_b6_badge' );
$intro   = electro_child_landing_article_get( 's10_article_b6_intro' );
$cards   = electro_child_landing_article_get_repeater( 's10_article_b6_cards' );
$brand   = electro_child_landing_article_get( 's10_article_b6_spec_brand' );
$model   = electro_child_landing_article_get( 's10_article_b6_spec_model' );
$meta    = electro_child_landing_article_get( 's10_article_b6_spec_meta' );
$spec_rows = electro_child_landing_article_get_repeater( 's10_article_b6_spec_rows' );
$closing = electro_child_landing_article_get( 's10_article_b6_closing' );
$exp_label = electro_child_landing_article_get( 's10_article_b6_exp_label' );
$exp_quote = electro_child_landing_article_get( 's10_article_b6_exp_quote' );
?>
<section id="h2-6" class="vns-a-sec">
	<?php if ( $heading ) : ?>
		<h2 id="h2-6-h">
			<?php echo electro_child_landing_kses_content( $heading ); ?>
			<?php if ( $badge ) : ?>
				<span class="vns-a-badge vns-badge-bestseller"><?php echo electro_child_landing_kses_content( $badge ); ?></span>
			<?php endif; ?>
		</h2>
	<?php endif; ?>
	<?php if ( $intro ) : ?>
		<p><?php echo electro_child_landing_kses_content( $intro ); ?></p>
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
	<?php if ( $brand || $model || ! empty( $spec_rows ) ) : ?>
		<div class="vns-spec">
			<div class="vns-spec-head">
				<div class="vns-spec-brand">
					<?php if ( $brand ) : ?>
						<p><span class="vns-spec-logo-tag"><?php echo electro_child_landing_kses_content( $brand ); ?></span></p>
					<?php endif; ?>
					<?php if ( $model ) : ?>
						<h3 class="vns-spec-model"><?php echo electro_child_landing_kses_content( $model ); ?></h3>
					<?php endif; ?>
				</div>
				<?php if ( $meta ) : ?>
					<div class="vns-spec-meta"><?php echo electro_child_landing_kses_content( $meta ); ?></div>
				<?php endif; ?>
			</div>
			<?php if ( ! empty( $spec_rows ) ) : ?>
				<table class="vns-spec-table">
					<tbody>
						<?php foreach ( $spec_rows as $row ) : ?>
							<tr>
								<?php if ( ! electro_child_landing_acf_value_is_empty( $row['spec_col1_label'] ?? '' ) ) : ?>
									<th><?php echo electro_child_landing_kses_content( (string) $row['spec_col1_label'] ); ?></th>
								<?php endif; ?>
								<?php if ( ! electro_child_landing_acf_value_is_empty( $row['spec_col1_value'] ?? '' ) ) : ?>
									<td><?php echo electro_child_landing_kses_rich( (string) $row['spec_col1_value'] ); ?></td>
								<?php endif; ?>
								<?php if ( ! electro_child_landing_acf_value_is_empty( $row['spec_col2_label'] ?? '' ) ) : ?>
									<th><?php echo electro_child_landing_kses_content( (string) $row['spec_col2_label'] ); ?></th>
								<?php endif; ?>
								<?php if ( ! electro_child_landing_acf_value_is_empty( $row['spec_col2_value'] ?? '' ) ) : ?>
									<td><?php echo electro_child_landing_kses_rich( (string) $row['spec_col2_value'] ); ?></td>
								<?php endif; ?>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php if ( $closing ) : ?>
		<p><?php echo electro_child_landing_kses_rich( $closing ); ?></p>
	<?php endif; ?>
	<?php if ( $exp_label || $exp_quote ) : ?>
		<div class="vns-experience">
			<div class="vns-experience-header">
				<span class="vns-experience-icon">⭐</span><br>
				<?php if ( $exp_label ) : ?>
					<span class="vns-experience-label"><?php echo electro_child_landing_kses_content( $exp_label ); ?></span>
				<?php endif; ?>
			</div>
			<?php if ( $exp_quote ) : ?>
				<p><?php echo electro_child_landing_kses_content( $exp_quote ); ?></p>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</section>
