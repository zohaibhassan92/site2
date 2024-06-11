<?php
namespace Happy_Addons_Pro\Widget\Skins\Product_Carousel;

use Elementor\Skin_Base;

defined( 'ABSPATH' ) || die();

class Classic extends Skin_Base {

	public function get_id() {
		return 'classic';
	}

	public function get_title() {
		return __( 'Classic', 'happy-addons-pro' );
	}

	public function print_quick_view_button( $product_id ) {
		$url = add_query_arg(
			[
				'action'     => 'ha_show_product_quick_view',
				'product_id' => $product_id,
				'nonce'      => wp_create_nonce( 'ha_show_product_quick_view' ),
			],
			admin_url( 'admin-ajax.php' )
		);

		printf(
			'<a href="#" data-mfp-src="%s" class="ha-pqv-btn" data-modal-class="ha-pqv--%s"><i class="far fa-eye"></i> <span>%s</span></a>',
			esc_url( $url ),
			$this->parent->get_id(),
			esc_html__( 'Quick View', 'happy-addons-pro' )
		);
	}

	public function render() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			printf( '<div class="ha-product-carousel-error">%s</div>', __( 'Please Install/Activate Woocommerce Plugin.', 'happy-addons-pro' ) );

			return;
		}

		$settings = $this->parent->get_settings_for_display();
		$loop = $this->parent->get_query();

		$this->parent->add_render_attribute(
			'wrapper',
			'class',
			[
				'ha-product-carousel-wrapper',
				'ha-layout-' . $this->get_id(),
				'ha-product-carousel-' . $this->get_id(),
			]
		);
		?>

		<div <?php $this->parent->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			if ( $loop->have_posts() ) :
				add_filter( 'woocommerce_product_add_to_cart_text', [ $this->parent, 'custom_add_to_cart_text' ], 10, 2 );

				while ( $loop->have_posts() ) : $loop->the_post();
					global $product;
					?>
					<article class="ha-product-carousel-item" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">
						<div class="ha-product-carousel-item-inner">
							<div class="ha-product-carousel-image">
								<a href="<?php the_permalink(); ?>">
									<?php echo woocommerce_get_product_thumbnail( $settings['post_image_size'] ); ?>
								</a>

								<?php if ( $settings['product_on_sale_show'] == 'yes' ) : ?>
									<div class="ha-product-carousel-on-sale"><?php woocommerce_show_product_loop_sale_flash(); ?></div>
								<?php endif; ?>

								<?php if ( $settings['product_quick_view_show'] == 'yes' ) : ?>
									<div class="ha-product-carousel-quick-view-wrap">
										<?php $this->print_quick_view_button( $product->get_id() ); ?>
									</div>
								<?php endif; ?>
							</div>

							<?php if ( $settings['product_ratings_show'] == 'yes' && $product->get_average_rating() ) : ?>
								<div class="ha-product-carousel-ratings"><?php woocommerce_template_loop_rating();  ?></div>
							<?php endif; ?>

							<h2 class="ha-product-carousel-title">
								<a href="<?php the_permalink(); ?>">
									<?php the_title(); ?>
								</a>
							</h2>

							<div class="ha-product-carousel-price"><?php echo $product->get_price_html(); ?></div>

							<?php if ( $settings['product_add_to_cart_show'] == 'yes' ) : ?>
								<div class="ha-product-carousel-add-to-cart">
									<?php woocommerce_template_loop_add_to_cart(); ?>
								</div>
							<?php endif; ?>
						</div>
					</article>

				<?php
				endwhile;

				wp_reset_postdata();

				remove_filter( 'woocommerce_product_add_to_cart_text', [ $this->parent, 'custom_add_to_cart_text' ], 10, 2 );

			else :
				if ( is_admin() ) {
					return printf( '<div class="ha-product-carousel-error">%s</div>', __( 'Nothing Found. Please Add Products.', 'happy-addons-pro' ) );
				}
			endif;
			?>
		</div>

		<?php
	}
}
