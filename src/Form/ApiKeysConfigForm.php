<?php

namespace Drupal\dolebas_payments\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ApiKeysConfigForm.
 *
 * @package Drupal\dolebas_payments\Form
 */
class ApiKeysConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'dolebas_payments.api_keys',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'api_keys_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('dolebas_payments.api_keys');
    $form['stripe_api_sk'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Stripe Api Secret Key'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('stripe_api_sk'),
    ];
    $form['stripe_api_pk'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Stripe Api Publishable Key'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('stripe_api_pk'),
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

    $this->config('dolebas_payments.api_keys')
      ->set('stripe_api_sk', $form_state->getValue('stripe_api_sk'))
      ->save();
    $this->config('dolebas_payments.api_keys')
      ->set('stripe_api_pk', $form_state->getValue('stripe_api_pk'))
      ->save();
  }

}
