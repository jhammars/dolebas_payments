<?php

namespace Drupal\dolebas_payments;

/**
 * Interface PaymentServiceInterface.
 *
 * @package Drupal\dolebas_payments
 */
interface PaymentServiceInterface {
  function doPayment($paymentInfo);

  function setAuthKeys($payment_gateway_type, array $auth_keys);

  function setPaymentGatewayType($payment_gateway_type);

  function getPaymentGatewayType();
}
