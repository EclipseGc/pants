<?php

/**
 * @file
 * Contains \Drupal\pants\Plugin\Block\ChangePants.
 */

namespace Drupal\pants\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\pants\PantsColor as PantsColorService;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
class PantsColor extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The service for determining pants color.
   *
   * @var \Drupal\pants\PantsColor
   */
  protected $pantsColor;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static($configuration, $plugin_id, $plugin_definition, $container->get('pants.color'));
  }

  /**
   * PantsColor constructor.
   *
   * @param array $configuration
   *   The plugin configuration.
   * @param string $plugin_id
   *   The plugin id.
   * @param mixed $plugin_definition
   *   The plugin definition.
   * @param \Drupal\pants\PantsColor $pants_color
   *   The pants color service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, PantsColorService $pants_color) {
    $this->pantsColor = $pants_color;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }


  /**
   * {@inheritdoc}
   */
  public function build() {
    $user = $this->getContextValue('user');

    $url = Url::fromRoute('pants.color', ['user' => $user->id()]);
    $link = Link::fromTextAndUrl($this->t('See details'), $url);

    return [
      'color' => [
        '#markup' => $this->t('Your pants are') . ' ' . $this->pantsColor->getPantsColor($user),
      ],
      'link' => [
        '#prefix' => '<br/>',
        '#markup' => $link->toString(),
      ],
    ];
  }

}
