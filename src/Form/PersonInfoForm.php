<?php

declare(strict_types=1);

namespace Drupal\person_info_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a user sign up form.
 */
final class PersonInfoForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'person_info_form_person_info';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['name_first'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First name'),
      '#placeholder' => $this->t('Enter first name'),
      '#required' => TRUE,
    ];

    $form['name_last'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last name'),
      '#placeholder' => $this->t('Enter last name'),
      '#required' => TRUE,
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email address'),
      '#placeholder' => $this->t('Enter Email address'),
      '#required' => TRUE,
    ];

    $form['phone_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Phone type'),
      '#required' => TRUE,
      '#options' => [
        'mobile' => $this->t('Mobile'),
        'home' => $this->t('Home'),
        'business' => $this->t('Business'),
      ],
    ];

    $form['phone_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone number'),
      '#placeholder' => $this->t('Enter phone number'),
      '#pattern' => '[0-9]{3}[0-9]{3}[0-9]{4}',
      '#required' => TRUE,
      '#attributes' => [
        'title' => $this->t('Please enter a 10-digit phone number without spaces or special characters. Example: 1112223333'),
      ],
    ];

    $form['consent_sms'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('I agree to receiving SMS messages'),
      '#states' => [
        'visible' => [
          ':input[name="phone_type"]' => ['value' => 'mobile'],
        ],
      ],
    ];

    $form['colors_favorite'] = [
      '#type' => 'select',
      '#title' => $this->t('Favorite color(s)'),
      '#options' => [
        'red' => $this->t('Red'),
        'blue' => $this->t('Blue'),
        'green' => $this->t('Green'),
        'yellow' => $this->t('Yellow'),
        'other' => $this->t('Other'),
      ],
      '#multiple' => TRUE,
    ];

    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    parent::validateForm($form, $form_state);

    // Validate at least two colors have been selected.
    $colors_favorite = $form_state->getValue('colors_favorite');
    if (is_array($colors_favorite) && count($colors_favorite) < 2) {
      $form_state->setErrorByName('colors_favorite', $this->t('Please select at least two favorite colors.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $this->messenger()->addStatus($this->t('Thank you for signing up!'));
    $this->logger('person_info_form')->info($this->t(
      'Submission received: %submission'),
      ['%submission' => json_encode($form_state->getValues())]
    );
  }

}
