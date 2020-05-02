<?php

namespace Drupal\music_contact_us\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MusicContactUsController.
 */
class MusicContactUsController extends ControllerBase {

  /**
   * Drupal\Core\Database\Driver\mysql\Connection definition.
   *
   * @var \Drupal\Core\Database\Driver\mysql\Connection
   */
  protected $database;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->database = $container->get('database');
    return $instance;
  }

  /**
   * Get the details.
   *
   * @return string
   *   Return the data.
   */
  public function getDetails() {
    // Create table header.
    $header_table = [
      'id' => $this->t('SrNo'),
      'first_name' => $this->t('First Name'),
      'last_name' => $this->t('Last Name'),
      'email_id' => $this->t('Email'),
      'date_of_birth' => $this->t('DOB'),
      'phone_no' => $this->t('Phone no'),
      'country' => $this->t('Country'),
      'query' => $this->t('Query'),
    ];

    // Select records from table.
    $query = $this->database->select('music_contact_us', 'm');
    $query->fields('m',
    ['id', 'first_name', 'last_name',
      'email_id', 'date_of_birth', 'phone_no',
      'country', 'query',
    ]);
    $results = $query->execute()->fetchAll();
    $rows = [];
    foreach ($results as $data) {
      // Print the data from table.
      $rows[] = [
        'id' => $data->id,
        'first_name' => $data->first_name,
        'last_name' => $data->last_name,
        'email_id' => $data->email_id,
        'date_of_birth' => $data->date_of_birth,
        'phone_no' => $data->phone_no,
        'country' => $data->country,
        'query' => $data->query,
      ];
    }
    // Display data in site.
    $form['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#rows' => $rows,
      '#empty' => $this->t('No data found'),
    ];
    return $form;
  }

}
