<?php

namespace Drupal\dolebas_payments;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Class PaymentService.
 *
 * @package Drupal\dolebas_payments
 */
class PaymentService {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Constructs a new PaymentService object.
   */
  public function __construct(EntityTypeManager $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
    $this->setStripeApiKeys();
  }

  /**
   * Get stripe api_keys settings and set api keys for \Stripe\Stripe object.
   */
  function setStripeApiKeys() {
    $config = \Drupal::config('dolebas_config.config');
    $stripe_api_sk = $config->get('stripe_api_sk');
    \Stripe\Stripe::setApiKey($stripe_api_sk);
  }

  /**
   * Create Charge object using stripe token source.
   *
   * Amount to be charged.
   * @param $amount
   *
   * Currency of the amount.
   * @param $currency
   *
   * Stripe token, can represent Card.
   * @param $token
   *
   * Returns \Stripe\Charge object
   * @return \Stripe\Charge
   */
  function stripeCharge($amount, $currency, $token) {
    return \Stripe\Charge::create(array('amount' => $amount, 'currency' => $currency, 'source' => $token));
  }

  // Code under construction
  function charge($processor, $amount, $currency, $token) {
    switch ($processor) {
      case 'Stripe':
        $this->stripeCharge($amount, $currency, $token);
        break;
      default:
        break;
    }
  }



}
