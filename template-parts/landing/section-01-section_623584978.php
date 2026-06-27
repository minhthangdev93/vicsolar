<?php
/**
 * Landing section: Hero + Pháp lý (Section 01).
 *
 * @package electro-child
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$s01_bg_url    = electro_child_landing_image_url( 's01_bg_image' );
$s01_title     = electro_child_landing_get( 's01_title' );
$s01_intro_1   = electro_child_landing_get( 's01_intro_1' );
$s01_intro_2   = electro_child_landing_get( 's01_intro_2' );
$s01_youtube   = electro_child_landing_youtube_embed_url( electro_child_landing_get( 's01_youtube_url' ) );
$s01_vid_title = electro_child_landing_get( 's01_video_title' );
?>
<section class="section dark" id="section_623584978">
	<div class="section-bg fill">
		<?php if ( $s01_bg_url ) : ?>
			<img
				fetchpriority="high"
				decoding="async"
				width="2560"
				height="1365"
				src="<?php echo esc_url( $s01_bg_url ); ?>"
				class="bg attachment-original size-original"
				alt=""
			>
		<?php endif; ?>
	</div>

	<div class="section-content relative">
		<div class="row row-large row-dashed" id="row-74533632">
			<div id="col-1905933084" class="col small-12 large-12">
				<div class="col-inner">
					<div id="text-483275844" class="text vs-hero-head">
						<div class="vs-hero-head__inner">
							<h1 class="vs-hero-title"><?php echo esc_html( $s01_title ); ?></h1>
							<div class="vs-hero-title-rule" aria-hidden="true">
								<span class="vs-hero-title-rule__line vs-hero-title-rule__line--wide"></span>
							</div>
						</div>
					</div>

					<?php if ( $s01_intro_1 ) : ?>
						<p class="vs-hero-intro"><?php echo esc_html( $s01_intro_1 ); ?></p>
					<?php endif; ?>

					<?php if ( $s01_intro_2 ) : ?>
						<p class="vs-hero-intro"><?php echo esc_html( $s01_intro_2 ); ?></p>
					<?php endif; ?>

					<div class="row" id="row-1630317366">
						<div id="col-354254191" class="col small-12 large-12">
							<div class="col-inner text-left box-shadow-5 vs-legal-card">
								<div class="is-border"></div>

								<div id="text-200102960" class="text vs-legal-block">
									<div class="vs-legal-block__inner">
										<div class="vs-legal-flex">
											<div class="vs-legal-text">
												<?php
												$legal_badge = electro_child_landing_get( 's01_legal_badge' );
												if ( $legal_badge ) :
													?>
													<div class="vs-legal-badge"><?php echo esc_html( $legal_badge ); ?></div>
												<?php endif; ?>

												<h3 class="vs-legal-heading"><?php echo esc_html( electro_child_landing_get( 's01_legal_title' ) ); ?></h3>

												<?php
												$legal_desc = electro_child_landing_get( 's01_legal_desc' );
												if ( $legal_desc ) :
													?>
													<div class="vs-legal-desc"><?php echo esc_html( $legal_desc ); ?></div>
												<?php endif; ?>

												<ul class="vs-legal-bullets">
													<?php
													$bullet_1 = electro_child_landing_get( 's01_legal_bullet_1' );
													$bullet_2 = electro_child_landing_get( 's01_legal_bullet_2' );
													if ( $bullet_1 ) :
														?>
														<li class="vs-legal-bullets__item">
															<span class="vs-legal-bullets__dot" aria-hidden="true"></span>
															<div class="vs-legal-bullets__text"><?php echo electro_child_landing_kses_rich( $bullet_1 ); ?></div>
														</li>
													<?php endif; ?>
													<?php if ( $bullet_2 ) : ?>
														<li class="vs-legal-bullets__item">
															<span class="vs-legal-bullets__dot" aria-hidden="true"></span>
															<div class="vs-legal-bullets__text"><?php echo electro_child_landing_kses_rich( $bullet_2 ); ?></div>
														</li>
													<?php endif; ?>
												</ul>

												<div class="vs-legal-actions-wrap">
													<?php
													$legal_actions_title = electro_child_landing_get( 's01_legal_actions_title' );
													if ( $legal_actions_title ) :
														?>
														<div class="vs-legal-actions-title"><?php echo esc_html( $legal_actions_title ); ?></div>
													<?php endif; ?>

													<div class="vs-legal-actions">
													<?php
													$zalo_label = electro_child_landing_get( 's01_zalo_label' );
													$zalo_url   = electro_child_landing_get( 's01_zalo_url' );
													if ( $zalo_label && $zalo_url ) :
														?>
														<a class="vs-legal-btn vs-legal-btn--zalo" href="<?php echo esc_attr( electro_child_landing_esc_href( $zalo_url ) ); ?>" target="_blank" rel="noopener noreferrer">
															<span aria-hidden="true">💬</span>
															<span><?php echo esc_html( $zalo_label ); ?></span>
														</a>
													<?php endif; ?>

													<?php
													$call_label = electro_child_landing_get( 's01_call_label' );
													$call_phone = preg_replace( '/\s+/', '', (string) electro_child_landing_get( 's01_call_phone' ) );
													if ( $call_label && $call_phone ) :
														?>
														<a class="vs-legal-btn vs-legal-btn--call" href="<?php echo esc_attr( 'tel:' . $call_phone ); ?>">
															<span aria-hidden="true">📞</span>
															<span><?php echo esc_html( $call_label ); ?></span>
														</a>
													<?php endif; ?>
													</div>
												</div>
											</div>

											<?php if ( $s01_youtube ) : ?>
												<div id="video-nghi-dinh-58" class="vs-legal-video">
													<div class="vs-legal-video__header">
														<div class="vs-legal-video__label"><?php echo esc_html( electro_child_landing_get( 's01_video_label' ) ); ?></div>
														<div class="vs-legal-video__badge"><?php echo esc_html( electro_child_landing_get( 's01_video_badge' ) ); ?></div>
													</div>
													<div class="vs-legal-video__frame">
														<div class="vs-legal-video__ratio">
															<iframe
																loading="lazy"
																title="<?php echo esc_attr( $s01_vid_title ); ?>"
																src="<?php echo esc_url( $s01_youtube ); ?>"
																allowfullscreen
															></iframe>
														</div>
													</div>
												</div>
											<?php endif; ?>
										</div>

										<?php
										$legal_note = electro_child_landing_get( 's01_legal_note' );
										if ( $legal_note ) :
											?>
											<div class="vs-legal-note">
												<div class="vs-legal-note__icon" aria-hidden="true"><span>i</span></div>
												<div class="vs-legal-note__text"><?php echo esc_html( $legal_note ); ?></div>
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
