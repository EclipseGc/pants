<?php /**
 * @file
 * Contains \Drupal\pants\Controller\DefaultController.
 */

namespace Drupal\pants\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Default controller for the pants module.
 */
class DefaultController extends ControllerBase {


  public function colorAccess() {
    return AccessResult::allowedIf(\Drupal::currentUser()->hasPermission('access pants color'));
  }

  public function color(AccountInterface $user) {
    $config = \Drupal::config('pants.settings');
    $pants_color = isset($user->pants_color->value) ? $user->pants_color->value : $config->get('default_color');

    return [
      '#markup' => $pants_color,
    ];
  }
}
