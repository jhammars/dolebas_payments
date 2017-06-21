<?php

/**
 * Implements hook_views_data_alter().
 */
function dolebas_payments_views_data_alter(array &$data) {

  $data['node']['dolebas_payments'] = array(
    'title' => t('Dolebas Payment Button'),
    'field' => array(
      'title' => t('Dolebas Payment Button'),
      'help' => t('Submit Payment'),
      'id' => 'dolebas_payments',
    ),
  );
}