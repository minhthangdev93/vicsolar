<?php
/**
 * Landing — Gọi ngay + Nhắn Zalo (sau mỗi section).
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cta_phone       = preg_replace( '/\s+/', '', (string) electro_child_landing_get( 'cta_phone' ) );
$cta_phone_label = electro_child_landing_get( 'cta_phone_label' );
$cta_zalo_url    = electro_child_landing_get( 'cta_zalo_url' );
$cta_zalo_label  = electro_child_landing_get( 'cta_zalo_label' );
$tel_href        = $cta_phone ? 'tel:' . $cta_phone : '';
?>
<div class="vs-section-contact-cta">
	<div class="vs-section-contact-cta__inner">
		<?php if ( $tel_href && $cta_phone_label ) : ?>
			<a class="vs-section-contact-btn vs-section-contact-call" href="<?php echo esc_attr( $tel_href ); ?>"><?php echo esc_html( $cta_phone_label ); ?></a>
		<?php endif; ?>
		<?php if ( $cta_zalo_url && $cta_zalo_label ) : ?>
			<a class="vs-section-contact-btn vs-section-contact-zalo" href="<?php echo esc_attr( electro_child_landing_esc_href( $cta_zalo_url ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $cta_zalo_label ); ?></a>
		<?php endif; ?>
	</div>
</div>
