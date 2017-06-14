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
//      'drupalSettings' => array(
//        'nid' => $nid,
//        'token' => $token,
//        'uuid' => $uuid,
//        'project_id' => $project_id
//      )
    );
    $build['#cache']['max-age'] = 0;
    return $build;
    return array(
      '#type' => 'inline_template',
      '#theme' => 'stripe_elements',
      '#attached' => array(
        'library' => array(
          'dolebas_payments/stripe-elements-library'
        ),
      ),
    );
  }

}
