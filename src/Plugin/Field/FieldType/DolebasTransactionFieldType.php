<?php

namespace Drupal\dolebas_payments\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'dolebas_transaction_field_type' field type.
 *
 * @FieldType(
 *   id = "dolebas_transaction_field_type",
 *   label = @Translation("Dolebas Transaction"),
 *   description = @Translation("Dolebas Transaction"),
 *   default_widget = "dolebas_transaction_field_widget",
 *   default_formatter = "dolebas_transaction_field_formatter"
 * )
 */
class DolebasTransactionFieldType extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [
      'max_length' => 255,
      'is_ascii' => FALSE,
      'case_sensitive' => FALSE,
    ] + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    // Prevent early t() calls by using the TranslatableMarkup.
    $properties['value'] = DataDefinition::create('string')
      ->setLabel(new TranslatableMarkup('Text value'))
      ->setSetting('case_sensitive', $field_definition->getSetting('case_sensitive'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $schema = [
      'columns' => [
        'value' => [
          'type' => $field_definition->getSetting('is_ascii') === TRUE ? 'varchar_ascii' : 'varchar',
          'length' => (int) $field_definition->getSetting('max_length'),
          'binary' => $field_definition->getSetting('case_sensitive'),
        ],
      ],
    ];

    return $schema;
  }

//  /**
//   * {@inheritdoc}
//   */
//  public function getConstraints() {
//    $constraints = parent::getConstraints();
//
//    if ($max_length = $this->getSetting('max_length')) {
//      $constraint_manager = \Drupal::typedDataManager()->getValidationConstraintManager();
//      $constraints[] = $constraint_manager->create('ComplexData', [
//        'value' => [
//          'Length' => [
//            'max' => $max_length,
//            'maxMessage' => t('%name: may not be longer than @max characters.', [
//              '%name' => $this->getFieldDefinition()->getLabel(),
//              '@max' => $max_length
//            ]),
//          ],
//        ],
//      ]);
//    }
//
//    return $constraints;
//  }
//
//  /**
//   * {@inheritdoc}
//   */
//  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {
//    $random = new Random();
//    $values['value'] = $random->word(mt_rand(1, $field_definition->getSetting('max_length')));
//    return $values;
//  }
//
//  /**
//   * {@inheritdoc}
//   */
//  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
//    $elements = [];
//
//    $elements['max_length'] = [
//      '#type' => 'number',
//      '#title' => t('Maximum length'),
//      '#default_value' => $this->getSetting('max_length'),
//      '#required' => TRUE,
//      '#description' => t('The maximum length of the field in characters.'),
//      '#min' => 1,
//      '#disabled' => $has_data,
//    ];
//
//    return $elements;
//  }

//  /**
//   * {@inheritdoc}
//   */
//  public function isEmpty() {
//    $value = $this->get('value')->getValue();
//    return $value === NULL || $value === '';
//  }

  public function preSave() {
    //print $this->get('value')->getValue();exit;
    //print'<pre>';print_r('hello');exit;
    //parent::preSave();
    $entity = $this->getEntity();

    $chargetoken = $entity->field_stripe_token->value;
    $currency = $entity->field_currency->value;
    $amount = $entity->field_amount->value;

    $config = \Drupal::config('dolebas_payments.stripeconfig');
    $api_key = $config->get('stripe_api_key');
    \Stripe\Stripe::setApiKey($api_key);

    $charge = \Stripe\Charge::create(array('amount' => $amount, 'currency' => $currency, 'source' => $chargetoken));

    $chargestatus = $charge->status;

    $entity->field_status->value = $chargestatus;

  }

  public function postSave($update) {
    //$entity = $this->getEntity();
    //$uuid = $entity->uuid();


    //\Stripe\Charge::create(array('amount' => 1212, 'currency' => 'sek', 'source' => 'tok_1AV28rK8Wzv9nBKytRPnJueS'));

    //print'<pre>';print_r($chargetoken);exit;
    //drupal_set_message(''. print_r($api_key, TRUE) .'');


    //parent::postSave($update);
  }

}
