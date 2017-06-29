<?php

namespace Drupal\dolebas_payments\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\Core\TypedData\DataDefinitionInterface;
use Drupal\Core\TypedData\TypedDataInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
    $entity = $this->getEntity();

    $chargetoken = $entity->field_dolebas_trans_charge_token->value;
    $currency = $entity->field_dolebas_trans_currency->value;
    $amount = $entity->field_dolebas_trans_amount->value;
    $processor = $entity->field_dolebas_trans_processor->value;

    // Check if there are any existing "upload_price" transactions with the same parent reference
    $parent_nid = $entity->field_dolebas_parent_ref->target_id;
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'dolebas_transaction')
      ->condition('field_dolebas_trans_type', 'upload_price')
      ->condition('field_dolebas_trans_parent_ref.target_id', $parent_nid);
    $existing_transactions = $query->execute();

    if (count($existing_transactions) == 0 && $processor == 'Stripe') {
      $charge = \Drupal::service('dolebas_payments.general')->stripeCharge($amount, $currency, $chargetoken);
      $entity->field_dolebas_trans_status->value = $charge->status;
    }
  }
}
