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

//    $config = \Drupal::config('dolebas_payments.stripeconfig');
//    $api_key = $config->get('stripe_api_key');
//    \Stripe\Stripe::setApiKey($api_key);
////    \Stripe\Charge::create(array('amount' => 4321, 'currency' => 'sek', 'source' => 'tok_1AV1P9K8Wzv9nBKy3eFOU5XX'));
//
//    \Stripe\Charge::create(array('amount' => 4321, 'currency' => 'sek', 'source' => 'tok_1AV1eiK8Wzv9nBKyXaG4jJMa'));

    $uuid_service = \Drupal::service('uuid');
    $random_uuid = $uuid_service->generate();

    $build['stripe_elements_block']['#type'] = 'inline_template';
    $build['stripe_elements_block']['#theme'] = 'stripe_elements';
    $build['stripe_elements_block']['#attached'] = array(
      'library' => array(
        'dolebas_payments/stripe-elements-library'
      ),
      'drupalSettings' => array(
        'amount' => 1234,
        'currency' => 'usd',
        'stripe_publishable_key' => 'pk_test_sizOaYRJSKPbGhj5blDXZm1d',
        'transaction_uuid' => $random_uuid
      )
    );
    $build['#cache']['max-age'] = 0;
    return $build;

  }

}

