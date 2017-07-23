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
use Drupal\node\Entity\Node;
      
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

  public function preSave() {
    $entity = $this->getEntity();

    $chargetoken = $entity->field_dolebas_trans_charge_token->value;
    $currency = \Drupal::service('dolebas_payments.pricing')->getCurrency();
    $amount = \Drupal::service('dolebas_payments.pricing')->getPrice();
    $entity->field_dolebas_trans_amount->value = $amount;
    
    $processor = $entity->field_dolebas_trans_processor->value;

    // Check if there are any existing "upload_price" transactions with the same parent reference
    $parent_nid = $entity->field_dolebas_parent_ref->target_id;
    $query = \Drupal::entityQuery('node')
      ->condition('type', 'dolebas_transaction')
      ->condition('field_dolebas_trans_type', 'upload_price')
      ->condition('field_dolebas_trans_parent_ref.target_id', $parent_nid);
    $existing_transactions = $query->execute();

    $entity->field_dolebas_trans_status->value = $entity->field_dolebas_trans_status->value;
    if (count($existing_transactions) == 0 && $processor == 'Stripe') {
      $charge = \Drupal::service('dolebas_payments.general')->stripeCharge($amount, $currency, $chargetoken);
      $entity->field_dolebas_trans_status->value = $charge->status;
      
      $uuid = \Drupal::service('uuid')->generate();
      $node = Node::create(array(
          'type' => 'dolebas_user_email',
          'uuid' => $uuid,
          'title' => $uuid,
          'field_dolebas_user_email' => $charge->source->name,
          'field_dolebas_user_email_source' => 'StripeChargeObject',
      ));
      $node->save();
      
    }
  }
}
