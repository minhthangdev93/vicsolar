<?php
/**
 * Article block — Cách chọn đơn vị lắp điện uy tín.
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$heading   = electro_child_landing_article_get( 's10_article_choose_heading' );
$intro     = electro_child_landing_article_get( 's10_article_choose_intro' );
$criteria  = electro_child_landing_article_get_repeater( 's10_article_choose_criteria' );
$caption   = electro_child_landing_article_get( 's10_article_choose_table_caption' );
$table_rows = electro_child_landing_article_get_repeater( 's10_article_choose_table_rows' );
$recommend = electro_child_landing_article_get( 's10_article_choose_recommend' );
?>
<section id="h2-choose" class="vns-a-sec">
	<div style="margin: 2rem 0; padding: 0;">
		<?php if ( $heading ) : ?>
			<h2 style="font-size: 1.5rem; font-weight: bold; color: #1e293b; margin-bottom: 1rem;"><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		<?php if ( $intro ) : ?>
			<p style="color: #374151; line-height: 1.75; margin-bottom: 1rem;"><?php echo esc_html( $intro ); ?></p>
		<?php endif; ?>
		<?php if ( ! empty( $criteria ) ) : ?>
			<div style="display: grid; grid-template-columns: repeat(auto-fit,minmax(280px,1fr)); gap: 12px; margin-bottom: 1.5rem;">
				<?php foreach ( $criteria as $item ) : ?>
					<?php
					$tag   = (string) ( $item['choose_tag'] ?? '' );
					$title = (string) ( $item['choose_title'] ?? '' );
					$body  = (string) ( $item['choose_body'] ?? '' );
					if ( '' === trim( $title ) && '' === trim( $body ) ) {
						continue;
					}
					?>
					<div style="background: #fff7ed; border: 1px solid #fed7aa; border-radius: 10px; padding: 1rem 1.25rem;">
						<?php if ( $tag ) : ?>
							<div style="background: #9a3412; color: #fff; font-size: 11px; font-weight: bold; letter-spacing:.06em; padding: 3px 10px; border-radius: 4px; display: inline-block; margin-bottom:.5rem;"><?php echo esc_html( $tag ); ?></div>
						<?php endif; ?>
						<?php if ( $title ) : ?>
							<div style="font-weight: 600; color: #1e293b; margin-bottom:.375rem;"><?php echo esc_html( $title ); ?></div>
						<?php endif; ?>
						<?php if ( $body ) : ?>
							<div style="font-size:.875rem; color: #374151; line-height: 1.6;"><?php echo electro_child_landing_kses_rich( $body ); ?></div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $table_rows ) ) : ?>
			<div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
				<div class="vns-tbl-wrap" data-vns-table="">
					<div class="vns-tbl-topscroll"><div class="vns-tbl-topscroll-inner">&nbsp;</div></div>
					<div class="vns-tbl-hint">← Vuốt ngang để xem đầy đủ →</div>
					<div class="vns-tbl">
						<table style="width: 100%; border-collapse: collapse; font-size:.9rem;">
							<?php if ( $caption ) : ?>
								<caption style="background: #fff7ed; color: #9a3412; font-size:.8rem; font-weight: 600; padding: 8px; text-align: left; border: 1px solid #fed7aa; border-bottom: none;"><?php echo esc_html( $caption ); ?></caption>
							<?php endif; ?>
							<thead>
								<tr>
									<th style="background: #fff7ed; color: #9a3412; font-weight: bold; padding: 10px 14px; border: 1px solid #fed7aa; text-align: left;" scope="col">Tiêu chí</th>
									<th style="background: #fff7ed; color: #9a3412; font-weight: bold; padding: 10px 14px; border: 1px solid #fed7aa; text-align: left;" scope="col">VicSolar</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ( $table_rows as $index => $row ) : ?>
									<?php
									$criterion = (string) ( $row['choose_criterion'] ?? '' );
									$value     = (string) ( $row['choose_value'] ?? '' );
									if ( '' === trim( $criterion ) && '' === trim( $value ) ) {
										continue;
									}
									$bg = 0 === $index % 2 ? '#fff' : '#fffbf5';
									?>
									<tr style="background: <?php echo esc_attr( $bg ); ?>;">
										<td style="padding: 10px 14px; border: 1px solid #fed7aa; color: #374151;"><?php echo esc_html( $criterion ); ?></td>
										<td style="padding: 10px 14px; border: 1px solid #fed7aa; color: #166534; font-weight: 600;"><?php echo esc_html( $value ); ?></td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php if ( $recommend ) : ?>
			<div style="background: #f0fdf4; border-left: 4px solid #16a34a; padding: 1rem 1.25rem; margin-top: 1rem; border-radius: 0 8px 8px 0;">
				<p style="margin: 0; color: #166534; font-size:.9rem; line-height: 1.6;"><?php echo electro_child_landing_kses_rich( $recommend ); ?></p>
			</div>
		<?php endif; ?>
	</div>
</section>
