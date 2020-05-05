<?php

namespace Drupal\music_contact_us\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class MusicContactUsForm.
 */
class MusicContactUsForm extends FormBase {

  /**
   * Drupal\Core\Form\FormBuilderInterface definition.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * Drupal\Core\Form\FormValidatorInterface definition.
   *
   * @var \Drupal\Core\Form\FormValidatorInterface
   */
  protected $formValidator;

  /**
   * Drupal\Core\Form\FormSubmitterInterface definition.
   *
   * @var \Drupal\Core\Form\FormSubmitterInterface
   */
  protected $formSubmitter;

  /**
   * Drupal\Core\Form\FormErrorHandlerInterface definition.
   *
   * @var \Drupal\Core\Form\FormErrorHandlerInterface
   */
  protected $formErrorHandler;

  /**
   * The country manager.
   *
   * @var \Drupal\Core\Locale\CountryManagerInterface
   */
  protected $countryManager;


  /**
   * Drupal\Core\Database\Driver\mysql\Connection definition.
   *
   * @var \Drupal\Core\Database\Driver\mysql\Connection
   */
  protected $database;

  /**
   * Drupal\Core\Database\Driver\mysql\Connection definition.
   *
   * @var Drupal\Core\Messenger\Messenger
   */

  protected $messenger;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->formBuilder = $container->get('form_builder');
    $instance->formValidator = $container->get('form_validator');
    $instance->formSubmitter = $container->get('form_submitter');
    $instance->formErrorHandler = $container->get('form_error_handler');
    $instance->countryManager = $container->get('country_manager');
    $instance->database = $container->get('database');
    $instance->messenger = $container->get('messenger');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'music_contact_us_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $list = $this->countryManager->getList();
    $countries = [];
    foreach ($list as $key => $value) {
      $val = $value->__toString();
      $countries[$key] = $val;
    }

    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First Name'),
      '#description' => $this->t('Please fill the First name.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#required' => TRUE,
      '#weight' => '0',
    ];
    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last Name'),
      '#description' => $this->t('Please fill the last name.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#required' => TRUE,
      '#weight' => '1',
    ];
    $form['email_id'] = [
      '#type' => 'email',
      '#title' => $this->t('Email Id'),
      '#description' => $this->t('Please fill the email id.'),
      '#required' => TRUE,
      '#weight' => '2',
    ];
    $form['date_of_birth'] = [
      '#type' => 'date',
      '#title' => $this->t('Date of birth'),
      '#weight' => '3',
    ];
    $form['phone_no'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone no'),
      '#required' => TRUE,
      '#weight' => '4',
    ];
    $form['country'] = [
      '#type' => 'select',
      '#title' => $this->t('Country'),
      '#options' => $countries,
      '#attributes' => ['class' => ['country-detect']],
      '#required' => TRUE,
      '#weight' => '5',
    ];
    $form['query'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Query'),
      '#weight' => '6',
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#weight' => '7',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    foreach ($form_state->getValues() as $key => $value) {
      // @TODO: Validate fields.
      $key = $key;
      $value = $value;
      if (strlen($form_state->getValue('phone_no')) < 3) {
        $form_state->setErrorByName('phone_no', $this->t('The phone number is too short. Please enter a full phone number.'));
      }

      // Get the email value from the field.
      $mail = $form_state->getValue('email');
      // Test the format of the email.
      if (!empty($mail)) {
        if (!$this->email_validator->isValid($mail)) {
          $form_state->setErrorByName('email', $this->t('The %email is not valid email.', ['%email' => $mail]));
        }
      }

    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    $this->messenger->addMessage($this->t("Thank you for the feedback!"));
    $conn = Database::getConnection();
    $conn->insert('music_contact_us')->fields(
      [
        'first_name' => $form_state->getValue('first_name'),
        'last_name' => $form_state->getValue('last_name'),
        'email_id' => $form_state->getValue('email_id'),
        'date_of_birth' => $form_state->getValue('date_of_birth'),
        'phone_no' => $form_state->getValue('phone_no'),
        'country' => $form_state->getValue('country'),
        'query' => $form_state->getValue('query'),
      ]
    )->execute();
    $url = Url::fromRoute('music_contact_us.getdetails');
    $form_state->setRedirectUrl($url);
  }

}
