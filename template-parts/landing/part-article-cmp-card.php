<?php
/**
 * Article — comparison card (vns-cmp-*).
 *
 * @package electro-child
 *
 * @var array<string, mixed> $args {
 *     @type array<string, mixed> $card Template card from electro_child_landing_article_cmp_row_for_template().
 * }
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$card  = isset( $args['card'] ) && is_array( $args['card'] ) ? $args['card'] : array();
$style = in_array( (string) ( $card['style'] ?? 'a' ), array( 'a', 'b', 'c' ), true ) ? $card['style'] : 'a';

$card_class = 'vns-cmp-card vns-cmp-' . $style;
$tag_extra  = '';

if ( 'b' === $style ) {
	$tag_extra = ' vns-cmp-tag-blue';
} elseif ( 'c' === $style ) {
	$tag_extra = ' vns-cmp-tag-green';
}

$tag      = (string) ( $card['tag'] ?? '' );
$name     = (string) ( $card['name'] ?? '' );
$tagline  = (string) ( $card['tagline'] ?? '' );
$specs    = isset( $card['specs'] ) && is_array( $card['specs'] ) ? $card['specs'] : array();
$pros_title = (string) ( $card['pros_title'] ?? '' );
$pros_lines = isset( $card['pros_lines'] ) && is_array( $card['pros_lines'] ) ? $card['pros_lines'] : array();
?>
<div class="<?php echo esc_attr( $card_class ); ?>">
	<div class="vns-cmp-head">
		<?php if ( $tag ) : ?>
			<p><span class="vns-cmp-tag<?php echo esc_attr( $tag_extra ); ?>"><?php echo electro_child_landing_kses_content( $tag ); ?></span></p>
		<?php endif; ?>
		<?php if ( $name ) : ?>
			<h3 class="vns-cmp-name"><?php echo electro_child_landing_kses_content( $name ); ?></h3>
		<?php endif; ?>
		<?php if ( $tagline ) : ?>
			<p class="vns-cmp-tagline"><?php echo electro_child_landing_kses_content( $tagline ); ?></p>
		<?php endif; ?>
	</div>
	<?php if ( ! empty( $specs ) ) : ?>
		<dl class="vns-cmp-specs">
			<?php foreach ( $specs as $spec ) : ?>
				<?php
				if ( ! is_array( $spec ) ) {
					continue;
				}
				$label = trim( (string) ( $spec['label'] ?? '' ) );
				$value = trim( (string) ( $spec['value'] ?? '' ) );
				if ( '' === $label && '' === $value ) {
					continue;
				}
				?>
				<?php if ( $label ) : ?>
					<dt><?php echo electro_child_landing_kses_content( $label ); ?></dt>
				<?php endif; ?>
				<?php if ( $value ) : ?>
					<dd><?php echo electro_child_landing_kses_rich( $value ); ?></dd>
				<?php endif; ?>
			<?php endforeach; ?>
		</dl>
	<?php endif; ?>
	<?php if ( $pros_title || ! empty( $pros_lines ) ) : ?>
		<div class="vns-cmp-pros">
			<?php if ( $pros_title ) : ?>
				<p><strong class="vns-cmp-pros-title"><?php echo electro_child_landing_kses_content( $pros_title ); ?></strong></p>
			<?php endif; ?>
			<?php if ( ! empty( $pros_lines ) ) : ?>
				<ul>
					<?php foreach ( $pros_lines as $line ) : ?>
						<li><?php echo electro_child_landing_kses_content( $line ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
