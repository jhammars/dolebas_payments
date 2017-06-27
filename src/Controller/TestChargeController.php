<?php

namespace Drupal\dolebas_payments\Controller;

use Drupal\Core\Controller\ControllerBase;

class TestChargeController extends ControllerBase {


  /**
   * Display the markup.
   *
   * @return array
   */
  public function testCharge() {

//    $config = \Drupal::config('dolebas_payments.api_keys');
//    $stripe_api_pk = $config->get('stripe_api_pk');
//
//    $uuid_service = \Drupal::service('uuid');
//    $random_uuid = $uuid_service->generate();
//
//    $build['stripe_elements_block']['#type'] = 'inline_template';
//    //$build['stripe_elements_block']['#theme'] = 'stripe_elements';
//    $build['stripe_elements_block']['#theme'] = 'stripe_checkout';
//    $build['stripe_elements_block']['#attached'] = array(
//      'library' => array(
//        //'dolebas_payments/stripe-elements-library'
//        'dolebas_payments/stripe-checkout'
//      ),
//      'drupalSettings' => array(
//        'amount' => 1234,
//        'currency' => 'usd',
//        'stripe_publishable_key' => $stripe_api_pk,
//        'transaction_uuid' => $random_uuid
//      )
//    );
//    $build['#cache']['max-age'] = 0;
//    return $build;

    //$dolebas_transaction_status = 'test';
//    $publish_nid = 47;
//
//    $query = \Drupal::entityQuery('node')
//      ->condition('type', 'dolebas_transaction')
//      ->condition('field_dolebas_parent_reference.target_id', $publish_nid);
//    $nids = $query->execute();
//    $nid = reset($nids);
//
//    $node = \Drupal\node\Entity\Node::load($nid);
//    $dolebas_transaction_status = $node->field_status->value;
//
//    return array(
//      '#type' => 'markup',
//      '#markup' => $this->t('hi' . $nid . $dolebas_transaction_status),
//    );

//      // create new unpublished node
//      $new_node = \Drupal\node\Entity\Node::create(array(
//        'title' => 'Dolebas Parent',
//        'status' => 0,
//        'type' => 'dolebas_parent',
//      ));
//      $new_node->save();

//    $parent_node = \Drupal\node\Entity\Node::load(66);
//    $status = $parent_node->status->value;
//
//      return array(
//        '#type' => 'markup',
//        '#markup' => $this->t('hi'. $status),
//      );

//    $entity = \Drupal\node\Entity\Node::load(78);
//    $parent_nid = $entity->field_dolebas_parent_reference->target_id;
//    $query = \Drupal::entityQuery('node')
//      ->condition('type', 'dolebas_transaction')
//      ->condition('field_dolebas_transaction_type', 'upload_price')
//      ->condition('field_dolebas_parent_reference.target_id', $parent_nid);
//    $nid_array = $query->execute();

    //$trasaction_nid = reset($nid_array);


//    $previous_url = \Drupal::request()->server->get('HTTP_REFERER');
//    $test = $_SERVER['HTTP_REFERER'];

//    return new \Symfony\Component\HttpFoundation\RedirectResponse('/node');

//    $url_raw = 'http://develop.kbox.site/user';
//    $encoded_url = bin2hex($url_raw);
//    $decoded_url = hex2bin($encoded_url);
//
//    $build['return_this'] = [
//      '#type' => 'markup',
//      '#markup' => $this->t('url raw ' . $url_raw . ' encoded ' . $encoded_url . ' decoded ' . $decoded_url)
//    ];
//    $build['#cache']['max-age'] = 0;
//
//    return $build;

//    $build['publisher_handler']['#attached'] = [
//      // Attach the .js library
//      'library' => [
//        'dolebas_publisher/publisher-handler'
//      ],
//      // Attach parameters to the .js library
//      'drupalSettings' => [
//        'parent_nid' => '$parent_nid',
//      ]
//    ];
//
//    $commands = array();
//    $commands[] = array('command' => 'reloadPage');
//
//    return array('#type' => 'ajax', '#commands' => $commands);
//    $commands = array();
//    $commands[] = array('command' => 'reloadPage');
//
//    $build['cloudinary_uploader_block']['#type'] = 'ajax';
//    $build['cloudinary_uploader_block']['#commands'] = $commands;
//    $build['cloudinary_uploader_block']['#attached'] = array(
//      // Attach the .js library
//      'library' => [
//        'dolebas_publisher/publisher-handler'
//      ],
//      // Attach parameters to the .js library
//      'drupalSettings' => [
//        'parent_nid' => '$parent_nid',
//      ]
//    );
//
//    return $build;

          return array(
            '#type' => 'markup',
            '#markup' => $this->t('hi'),
          );

  }
}

