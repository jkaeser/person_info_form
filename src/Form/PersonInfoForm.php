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
    $class_base = $form['#attributes']['class'][0];

    $form['header'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => $class_base . '__header',
      ],
    ];

    $form['header']['heading'] = [
      '#type' => 'html_tag',
      '#tag' => 'h2',
      '#value' => $this->t('Sign Up'),
      '#attributes' => [
        'class' => $class_base . '__heading',
      ],
    ];
    $form['header']['subheading'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#value' => $this->t('Enter your credentials'),
      '#attributes' => [
        'class' => $class_base . '__subheading',
      ],
    ];

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

    $form['#attached']['library'][] = 'person_info_form/core';

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
    $this->messenger()->addStatus($this->t('Success! Thank you for your submission.'));

    // Log form submission.
    $this->logger('person_info_form')->info($this->t(
      'Submission received: %submission'),
      ['%submission' => json_encode($form_state->getValues())]
    );

    // Store form submission.
    $connection = \Drupal::database();
    $connection->insert('person_info_form_submissions')
      ->fields([
        'name_first' => $form_state->getValue('name_first'),
        'name_last' => $form_state->getValue('name_last'),
        'email' => $form_state->getValue('email'),
        'phone_type' => $form_state->getValue('phone_type'),
        'phone_number' => $form_state->getValue('phone_number'),
        'consent_sms' => (int) $form_state->getValue('consent_sms'),
        'colors_favorite' => implode(',', $form_state->getValue('colors_favorite')),
        'created' => \Drupal::time()->getRequestTime(),
      ])
      ->execute();
  }

}
