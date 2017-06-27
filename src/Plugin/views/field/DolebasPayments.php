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

    // Get nid from parent node included in the view
    $parent_nid = strip_tags($this->view->field['nid']->original_value);

    // Configure purchase item
    $transaction_type = 'upload_price';
    $amount = 1234;
    $currency = 'usd';
    $processor = 'Stripe';

    //$parent_nid = 128;
    // Check if the item is already purchased
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'dolebas_transaction')
      ->condition('field_dolebas_trans_type', 'upload_price')
      ->condition('field_dolebas_trans_parent_ref.target_id', $parent_nid);
    $existing_transactions = $query->execute();
//    $existing_transactions = 0;

    // If the item is not already purchased...
    if (count($existing_transactions) == 0) {

      // Get the api key
      $config = \Drupal::config('dolebas_payments.api_keys');
      $stripe_api_pk = $config->get('stripe_api_pk');

      // Generate a random uuid
      $transaction_uuid = \Drupal::service('uuid')->generate();

      // Output the purchase button
      $build['stripe_elements_block']['#theme'] = 'stripe_checkout';

      $build['stripe_elements_block']['#attached'] = [
        // Attach the .js library
        'library' => [
          'dolebas_payments/stripe-checkout'
        ],
        // Attach parameters to the .js library
        'drupalSettings' => [
          'amount' => $amount,
          'currency' => $currency,
          'stripe_publishable_key' => $stripe_api_pk,
          'transaction_uuid' => $transaction_uuid,
          'parent_nid' => $parent_nid,
          'transaction_type' => $transaction_type,
          'processor' => $processor,
          // TODO: Add 'amount_paid' (calculates sum of amounts from parent_nid children)
        ]
      ];
      $build['#cache']['max-age'] = 0;

      return $build;

    } else {
      return array(
        '#type' => 'markup',
        // TODO: Get payment status from existing transaction and print it here
        '#markup' => $this->t('Existing transactions present'),
      );
    }
  }
}
