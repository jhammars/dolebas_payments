<?php

namespace Drupal\dolebas_payments\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'StripeElementsBlock' block.
 *
 * @Block(
 *  id = "stripe_elements_block",
 *  admin_label = @Translation("Stripe elements block"),
 * )
 */
class StripeElementsBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build['stripe_elements_block']['#type'] = 'inline_template';
    $build['stripe_elements_block']['#theme'] = 'stripe_elements';
    $build['stripe_elements_block']['#attached'] = array(
      'library' => array(
        'dolebas_payments/stripe-elements-library'
      ),
      'drupalSettings' => array(
        'node_type' => 'video',
        'amount' => 1111,
        'currency' => 'usd',
        'stripe_publishable_key' => 'pk_test_sizOaYRJSKPbGhj5blDXZm1d'
      )
    );
    $build['#cache']['max-age'] = 0;
    return $build;
  }

}
