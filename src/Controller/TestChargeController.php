<?php

namespace Drupal\dolebas_payments\Controller;

use Drupal\Core\Controller\ControllerBase;

class TestChargeController extends ControllerBase {


  /**
   * Display the markup.
   *
   * @return array
   */
  public function testCharge() {

    $config = \Drupal::config('dolebas_payments.api_keys');
    $stripe_api_pk = $config->get('stripe_api_pk');

    $uuid_service = \Drupal::service('uuid');
    $random_uuid = $uuid_service->generate();

    $build['stripe_elements_block']['#type'] = 'inline_template';
    //$build['stripe_elements_block']['#theme'] = 'stripe_elements';
    $build['stripe_elements_block']['#theme'] = 'stripe_checkout';
    $build['stripe_elements_block']['#attached'] = array(
      'library' => array(
        //'dolebas_payments/stripe-elements-library'
        'dolebas_payments/stripe-checkout'
      ),
      'drupalSettings' => array(
        'amount' => 1234,
        'currency' => 'usd',
        'stripe_publishable_key' => $stripe_api_pk,
        'transaction_uuid' => $random_uuid
      )
    );
    $build['#cache']['max-age'] = 0;
    return $build;

  }

}

