<?php

namespace Drupal\music_contact_us\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the music_contact_us module.
 */
class MusicContactUsControllerTest extends WebTestBase {

  /**
   * Drupal\Core\Database\Driver\mysql\Connection definition.
   *
   * @var \Drupal\Core\Database\Driver\mysql\Connection
   */
  protected $database;

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "music_contact_us MusicContactUsController's controller functionality",
      'description' => 'Test Unit for module music_contact_us and controller MusicContactUsController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests music_contact_us functionality.
   */
  public function testMusicContactUsController() {
    // Check that the basic functions of module music_contact_us.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
