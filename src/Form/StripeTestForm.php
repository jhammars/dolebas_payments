<?php

namespace Drupal\dolebas_payments\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\dolebas_payments\PaymentService;
use Drupal\dolebas_payments\PaymentServiceInterface;
use Stripe\Balance;
use Stripe\BalanceTransaction;
use Stripe\Stripe;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class StripeTestForm.
 *
 * @package Drupal\dolebas_payments\Form
 */
class StripeTestForm extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'stripe_test_form';
  }

  /**
   * Class constructor.
   */
  public function __construct(PaymentService $paymentService) {
    $this->paymentService = $paymentService;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['currency'] = [
      '#type' => 'radios',
      '#title' => $this->t('Currency'),
      '#options' => array(
        'usd' => 'USD',
        'sek' => 'SEK',
      ),
      '#default_value' => 'sek',
      '#maxlength' => 64,
      '#size' => 64,
    ];
    $form['amount'] = [
      '#type' => 'number',
      '#title' => $this->t('Amount'),
      '#description' => $this->t('Amount'),
      '#default_value' => 1000,
    ];
    $form['fee'] = [
      '#type' => 'number',
      '#title' => $this->t('Fee'),
      '#description' => $this->t('Fee'),
      '#default_value' => 10,
    ];
    $form['fee_email'] = [
      '#type' => 'email',
      '#title' => $this->t('Fee Email'),
      '#description' => $this->t('Fee Email'),
      '#default_value' => 'example@example.com',
    ];
    $form['receiver_email'] = [
      '#type' => 'email',
      '#title' => $this->t('Receiver Email'),
      '#description' => $this->t('Receiver Email'),
      '#default_value' => 'example@example.com',
    ];
    $form['sender_email'] = [
      '#type' => 'email',
      '#title' => $this->t('Sender Email'),
      '#description' => $this->t('Sender Email'),
      '#default_value' => 'example@example.com',
    ];
    $form['processor'] = [
      '#type' => 'radios',
      '#title' => $this->t('Processor'),
      '#options' => array(
        'stripe_hosted_card' => 'Stripe/Card (hosted)',
        'stripe_3dsecure' => 'Stripe/3dSecure (redirect)',
        'paypal_xx' => 'PayPal/XX'
      ),
      '#default_value' => 'stripe_hosted_card',
      '#description' => $this->t('Processor'),
      '#maxlength' => 64,
      '#size' => 64,
    ];
    $form['payment_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Payment Id'),
      '#description' => $this->t('Payment Id'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => 123,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit backend input parameters'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    foreach ($form_state->getValues() as $key => $value) {
      drupal_set_message($key . ': ' . $value);
    }

    // get payment_message from somewhere
    $payment_message = 'This is a message from dolebas_payments';
    drupal_set_message(''. print_r($payment_message, TRUE) .'');

    // get payment_id from form input value
    $payment_id = $form_state->getValue(['payment_id']);
    drupal_set_message(''. print_r($payment_id, TRUE) .'');

    if ($form_state->getValue('processor') == 'stripe_hosted_card') {
      $this->stripeHostedTest($form, $form_state);
    }
    elseif ($form_state->getValue('processor') == 'stripe_3dsecure') {
      $this->stripe3dsecure($form, $form_state);
    }
    elseif($form_state->getValue('processor') == 'paypal_xx') {
      $this->paypalTest($form, $form_state);
    }

  }

  private function stripeHostedTest(array &$form, FormStateInterface $form_state) {
    $config = \Drupal::config('dolebas_config.config');
    $stripe_api_sk = $config->get('stripe_api_sk');
    \Stripe\Stripe::setApiKey($stripe_api_sk);
    $card =  array(
      "number" => "4242424242424242",
      "exp_month" => 5,
      "exp_year" => 2018,
      "cvc" => "314"
    );
    //$this->paymentService->
    $token = \Stripe\Token::create(array(
      "card" => array(
        "number" => "4242424242424242",
        "exp_month" => 5,
        "exp_year" => 2018,
        "cvc" => "314"
      )
    ));
    $charge = $this->paymentService->stripeCharge($form_state->getValue('amount'), $form_state->getValue('currency'), $token);
    //print'<pre>';print_r($token->id);exit;


    //$charge = \Stripe\Charge::create(array('amount' => $form_state->getValue('amount'), 'currency' => $form_state->getValue('currency'), 'source' => $token));

    //$charge = \Stripe\Charge::create(array('amount' => $form_state->getValue('amount'), 'currency' => $form_state->getValue('currency'), 'source' => $token));
    //print '<pre>';print_r($charge);exit;
  }

  private function paypalTest(array &$form, FormStateInterface $form_state) {
    //@todo
  }

  private function stripe3dsecure($form, $form_state) {
    //@todo
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
    // Load the service required to construct this class.
      $container->get('dolebas_payments.general')
    );
  }

}
