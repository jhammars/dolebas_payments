<?php

namespace Drupal\dolebas_payments;
use Drupal\Core\Entity\EntityTypeManager;
use Stripe\Stripe;

/**
 * Class PaymentService.
 *
 * @package Drupal\dolebas_payments
 */
class PaymentService implements PaymentServiceInterface {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Payment gateway type.
   */
  protected $paymentGatewayType;

  /**
   * Payment Gateway Handler.
   */
  protected $paymentGatewayHandler;

  /**
   * Constructs a new PaymentService object.
   */
  public function __construct(EntityTypeManager $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  function doPayment($paymentInfo) {

  }

  function setAuthKeys($payment_gateway_type, array $auth_keys) {

  }

  function setPaymentGatewayType($payment_gateway_type) {
    $this->paymentGatewayType = $payment_gateway_type;
  }

  function getPaymentGatewayType() {
    return $this->paymentGatewayType;
  }


}
