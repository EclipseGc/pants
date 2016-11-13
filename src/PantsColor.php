<?php

namespace Drupal\pants;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\user\UserInterface;

/**
 * A stateless service for retrieving pants color.
 */
class PantsColor {

  /**
   * The pants settings configuration.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * PantsColor constructor.
   */
  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->config = $configFactory->get('pants.settings');
  }

  /**
   * Retrieves the pants color of a particular user.
   *
   * @param \Drupal\user\UserInterface $user
   *   The user to check pants color.
   *
   * @return string
   */
  public function getPantsColor(UserInterface $user) {
    return $user->hasField('pants_color') && isset($user->pants_color->value) ? $user->pants_color->value : $this->getDefaultPantsColor();
  }

  /**
   * Retrieves the default pants color.
   *
   * @return string
   */
  public function getDefaultPantsColor() {
    return $this->config->get('default_color');
  }

}
