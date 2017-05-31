<?php

namespace Drupal\dolebas_payments\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Stripe\Balance;
use Stripe\BalanceTransaction;
use Stripe\Stripe;

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
    ];
    $form['fee'] = [
      '#type' => 'number',
      '#title' => $this->t('Fee'),
      '#description' => $this->t('Fee'),
    ];
    $form['fee_email'] = [
      '#type' => 'email',
      '#title' => $this->t('Fee Email'),
      '#description' => $this->t('Fee Email'),
    ];
    $form['receiver_email'] = [
      '#type' => 'email',
      '#title' => $this->t('Receiver Email'),
      '#description' => $this->t('Receiver Email'),
    ];
    $form['sender_email'] = [
      '#type' => 'email',
      '#title' => $this->t('Sender Email'),
      '#description' => $this->t('Sender Email'),
    ];
    $form['processor'] = [
      '#type' => 'radios',
      '#title' => $this->t('Processor'),
      '#options' => array(
        'hosted' => 'Hosted',
        'redirect' => 'Redirect'
      ),
      '#default_value' => 'redirect',
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
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
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

    $config = \Drupal::config('dolebas_payments.stripeconfig');
    $api_key = $config->get('stripe_api_key');
    \Stripe\Stripe::setApiKey($api_key);
    $bal = \Stripe\Balance::retrieve();

    //$bal_list = BalanceTransaction::all(array("limit" => 3));
    //print'<pre>';print_r($bal);exit;

    $token = \Stripe\Token::create(array(
      "card" => array(
        "number" => "4242424242424242",
        "exp_month" => 5,
        "exp_year" => 2018,
        "cvc" => "314"
      )
    ));
    //print'<pre>';print_r($token->id);exit;

    $charge = \Stripe\Charge::create(array('amount' => $form_state->getValue('amount'), 'currency' => $form_state->getValue('currency'), 'source' => $token));
    //print '<pre>';print_r($charge);exit;

  }

}
