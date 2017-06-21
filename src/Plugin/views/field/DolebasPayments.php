<?php

/**
 * @file
 * Definition of Drupal\dolebas_payments\Plugin\views\field\DolebasPayments.
 */

namespace Drupal\dolebas_payments\Plugin\Views\Field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;


/**
 * Field handler.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("dolebas_payments")
 */
class DolebasPayments extends FieldPluginBase {

  /**
   * @{inheritdoc}
   */
  public function query() {
    // Leave empty to avoid a query on this field.
  }

  /**
   * @{inheritdoc}
   */
  public function render(ResultRow $values) {

    // get nid from another field that must be included in the view
    $nid = strip_tags($this->view->field['nid']->original_value);

    $config = \Drupal::config('dolebas_payments.api_keys');
    $stripe_api_pk = $config->get('stripe_api_pk');

    $transaction_uuid = \Drupal::service('uuid')->generate();

//    $build['stripe_elements_block']['#type'] = 'inline_template';
    $build['stripe_elements_block']['#theme'] = 'stripe_checkout';
    $build['stripe_elements_block']['#attached'] = array(
      'library' => array(
        'dolebas_payments/stripe-checkout'
      ),
      'drupalSettings' => array(
        'amount' => 1234,
        'currency' => 'usd',
        'stripe_publishable_key' => $stripe_api_pk,
        'transaction_uuid' => $transaction_uuid,
        'parent_nid' => $nid,
      )
    );
    $build['#cache']['max-age'] = 0;

    // when the status field of the transaction node = succeeded, create a new revision
    return $build;

  }
}
