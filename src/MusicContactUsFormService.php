<?php

namespace Drupal\music_contact_us;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Core\Locale\CountryManagerInterface;

/**
 * Class MusicContactUsFormService.
 */
class MusicContactUsFormService implements ContainerInjectionInterface {

  /**
   * Drupal\Core\Database\Driver\mysql\Connection definition.
   *
   * @var \Drupal\Core\Database\Driver\mysql\Connection
   */
  protected $database;

  /**
   * Drupal\Core\Session\AccountProxyInterface definition.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * Drupal\Component\Utility\EmailValidatorInterface definition.
   *
   * @var \Drupal\Component\Utility\EmailValidatorInterface
   */
  protected $emailValidator;

  /**
   * The country manager.
   *
   * @var \Drupal\Core\Locale\CountryManagerInterface
   */
  protected $countryManager;

  /**
   * Constructs a new MusicContactUsFormService object.
   */
  public function __construct(Connection $database, AccountProxyInterface $current_user, EmailValidatorInterface $email_validator, CountryManagerInterface $country_manager) {
    $this->database = $database;
    $this->currentUser = $current_user;
    $this->emailValidator = $email_validator;
    $this->countryManager = $country_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
          $container->get('database'),
          $container->get('current_user'),
          $container->get('email.validator'),
          $container->get('country_manager')
      );
  }

}
