<?php
/**
 * WooCommerce Measurement Price Calculator
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Measurement Price Calculator to newer
 * versions in the future. If you wish to customize WooCommerce Measurement Price Calculator for your
 * needs please refer to http://docs.woothemes.com/document/measurement-price-calculator/ for more information.
 *
 * @package   WC-Measurement-Price-Calculator/Templates
 * @author    SkyVerge
 * @copyright Copyright (c) 2012-2016, SkyVerge, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

/**
 * Product page measurement quantity calculator
 *
 * @version 3.1.2
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

$actual_amount_text = apply_filters(
	'wc_measurement_price_calculator_actual_amount_text',
	$product_measurement->get_unit_label() ?
		/* translators: Placeholders: %1$s - measurement label, %2$s - measurement unit label */
		sprintf( __( 'Actual %1$s (%2$s)', 'woocommerce-measurement-price-calculator' ), __( $product_measurement->get_label(), 'woocommerce-measurement-price-calculator' ), __( $product_measurement->get_unit_label(), 'woocommerce-measurement-price-calculator' ) ) :
		/* translators: Placeholders: %s - measurement label */
		sprintf( __( 'Actual %s', 'woocommerce-measurement-price-calculator' ), __( $product_measurement->get_label(), 'woocommerce-measurement-price-calculator' ) )
);

?>
<table id="price_calculator" class="<?php echo $product->product_type . "_price_calculator" ?>">
	<?php foreach ( $measurements as $measurement ) : ?>
		<?php if ( $measurement->is_editable() ) : ?>
			<tr>
				<td>
					<label for="<?php echo $measurement->get_name(); ?>_needed">
					<?php echo ( $measurement->get_unit_label() ?
							/* translators: Placeholders: %1$s - measurement label, %2$s - measurement unit label */
							sprintf( __( '%1$s (%2$s)', 'woocommerce-measurement-price-calculator' ), $measurement->get_label(), __( $measurement->get_unit_label(), 'woocommerce-measurement-price-calculator' ) ) :
							__( $measurement->get_label(), 'woocommerce-measurement-price-calculator' )
						);
					?>
					</label>
				</td>
				<td><input type="text" data-unit="<?php echo esc_attr( $measurement->get_unit() ); ?>" data-common-unit="<?php echo esc_attr( $measurement->get_unit_common() ); ?>" name="<?php echo esc_attr( $measurement->get_name() ); ?>_needed" id="<?php echo esc_attr( $measurement->get_name() ); ?>_needed" class="amount_needed" autocomplete="off" /></td>
			</tr>
		<?php endif; ?>
	<?php endforeach; ?>
	<tr><td><?php echo $actual_amount_text; ?></td><td><span id="<?php echo esc_attr( $product_measurement->get_name() ); ?>_actual" class="amount_actual" data-unit="<?php echo esc_attr( $product_measurement->get_unit() ); ?>"><?php echo $product_measurement->get_value(); ?></span></td></tr>
	<tr><td><?php esc_html_e( 'Total Price', 'woocommerce-measurement-price-calculator' ); ?></td><td><span class="total_price"><?php echo $total_price; ?></span></td></tr>
</table>
