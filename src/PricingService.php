<?php

namespace Drupal\dolebas_payments;
use Drupal\Core\Entity\EntityTypeManager;

/**
 * Class PricingService.
 *
 * @package Drupal\dolebas_payments
 */
class PricingService {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Constructs a new PricingService object.
   */
  public function __construct(EntityTypeManager $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  function getPrice() {
    return 100;
  }
  
  function getCurrency() {
    return 'usd';
  }

}
