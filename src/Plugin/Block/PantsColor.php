<?php

/**
 * @file
 * Contains \Drupal\pants\Plugin\Block\ChangePants.
 */

namespace Drupal\pants\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use Drupal\Core\Link;

/**
 * Provides the PantsColor block.
 *
 * @Block(
 *   id = "pants_color",
 *   admin_label = @Translation("Current user's pants color"),
 *   context = {
 *     "user" = @ContextDefinition("entity:user", label = @Translation("Current User"))
 *   }
 * )
 */
class PantsColor extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $user = $this->getContextValue('user');

    $config = \Drupal::config('pants.settings');
    $pants_color = isset($user->pants_color->value) ? $user->pants_color->value : $config->get('default_color');

    $url = Url::fromRoute('pants.color', ['user' => $user->id()]);
    $link = Link::fromTextAndUrl($this->t('See details'), $url);

    return [
      'color' => [
        '#markup' => $this->t('Your pants are') . ' ' . $pants_color,
      ],
      'link' => [
        '#prefix' => '<br/>',
        '#markup' => $link->toString(),
      ],
    ];
  }

}
