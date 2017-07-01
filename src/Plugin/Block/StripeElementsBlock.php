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

    $config = \Drupal::config('dolebas_config.config');
    $stripe_api_pk = $config->get('stripe_api_pk');

    $transaction_uuid = \Drupal::service('uuid')->generate();

    $build['stripe_elements_block']['#type'] = 'inline_template';
    $build['stripe_elements_block']['#theme'] = 'stripe_elements';
    $build['stripe_elements_block']['#attached'] = array(
      'library' => array(
        'dolebas_payments/stripe-elements-library'
      ),
      'drupalSettings' => array(
        'node_type' => 'dolebas_transaction',
        'amount' => 1111,
        'currency' => 'usd',
        'stripe_publishable_key' => $stripe_api_pk,
        'transaction_uuid' => $transaction_uuid,
      )
    );
    $build['#cache']['max-age'] = 0;
    return $build;
  }

}
