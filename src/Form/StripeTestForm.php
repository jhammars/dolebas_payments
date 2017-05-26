<?php

namespace Drupal\dolebas_payments\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
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
      '#type' => 'textfield',
      '#title' => $this->t('Currency'),
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
      '#type' => 'textfield',
      '#title' => $this->t('Processor'),
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
    Stripe::setApiKey($api_key);

    $charge = \Stripe\Charge::create(array('amount' => $form_state->getValue('amount'), 'currency' => $form_state->getValue('currency'), 'source' => 'pk_test_sizOaYRJSKPbGhj5blDXZm1d' ));
    //print '<pre>';print_r($charge);exit;

  }

}
