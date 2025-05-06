<?php

declare(strict_types=1);

namespace Drupal\person_info_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PersonInfoFormAdminController extends ControllerBase {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a new controller.
   */
  public function __construct(Connection $database) {
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Returns a render array for the submissions admin page.
   */
  public function submissionsPage() {
    $header = [
      ['data' => $this->t('ID')],
      ['data' => $this->t('First Name')],
      ['data' => $this->t('Last Name')],
      ['data' => $this->t('Email')],
      ['data' => $this->t('Phone Type')],
      ['data' => $this->t('Phone Number')],
      ['data' => $this->t('Consent to Receive SMS Messages')],
      ['data' => $this->t('Favorite Color(s)')],
      ['data' => $this->t('Created')],
    ];

    $query = $this->database->select('person_info_form_submissions', 'p')
      ->fields('p', [
        'id',
        'name_first',
        'name_last',
        'email',
        'phone_type',
        'phone_number',
        'consent_sms',
        'colors_favorite',
        'created'
      ])
      ->orderBy('created', 'DESC')
      ->range(0, 50);

    $rows = [];
    foreach ($query->execute() as $record) {
      $rows[] = [
        $record->id,
        $record->name_first,
        $record->name_last,
        $record->email,
        $record->phone_type,
        $record->phone_number,
        $record->consent_sms,
        $record->colors_favorite,
        \Drupal::service('date.formatter')->format($record->created, 'short'),
      ];
    }

    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No submissions found.'),
    ];
  }
}
