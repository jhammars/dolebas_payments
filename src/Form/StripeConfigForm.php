<?php

namespace Drupal\dolebas_payments\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class StripeConfigForm.
 *
 * @package Drupal\dolebas_payments\Form
 */
class StripeConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'dolebas_payments.stripeconfig',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'stripe_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dolebas_payments.stripeconfig');
    $form['stripe_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Stripe Api key'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('stripe_api_key'),
    ];
    return parent::buildForm($form, $form_state);
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
    parent::submitForm($form, $form_state);

    $this->config('dolebas_payments.stripeconfig')
      ->set('stripe_api_key', $form_state->getValue('stripe_api_key'))
      ->save();
  }

}
