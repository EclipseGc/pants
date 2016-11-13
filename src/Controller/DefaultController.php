<?php

namespace Drupal\pants\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\pants\PantsColor;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Default controller for the pants module.
 */
class DefaultController extends ControllerBase {

  /**
   * The pants color service.
   *
   * @var \Drupal\pants\PantsColor
   */
  protected $pantsColor;

  /**
   * DefaultController constructor.
   *
   * @param \Drupal\pants\PantsColor $pants_color
   *   The pants color service.
   */
  public function __construct(PantsColor $pants_color) {
    $this->pantsColor = $pants_color;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('pants.color'));
  }

  public function colorAccess() {
    return AccessResult::allowedIf(\Drupal::currentUser()->hasPermission('access pants color'));
  }

  public function color(AccountInterface $user) {
    return [
      '#markup' => $this->pantsColor->getPantsColor($user),
    ];
  }

}
