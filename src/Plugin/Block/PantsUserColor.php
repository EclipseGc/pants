<?php

/**
 * @file
 * Contains \Drupal\pants\Plugin\Block\ChangePants.
 */

namespace Drupal\pants\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\user\UserStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the PantsUserColor block.
 *
 * @Block(
 *   id = "pants_user_color",
 *   admin_label = @Translation("Specific user's pants color")
 * )
 */
class PantsUserColor extends BlockBase  implements ContainerFactoryPluginInterface {

  /**
   * The user storage.
   *
   * @var \Drupal\user\UserStorageInterface
   */
  protected $userStorage;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UserStorageInterface $user_storage) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->userStorage = $user_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.manager')->getStorage('user')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array(
      'user' => 1,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);

    $users = $this->userStorage->loadMultiple();

    $options = array();
    foreach ($users as $user) {
      if ($user->id() != 0) {
        $options[$user->id()] = $user->getDisplayName();
      }
    }
    $form['user'] = array(
      '#type' => 'select',
      '#options' => $options,
      '#default_value' => $this->configuration['user'],
      '#title' => $this->t('Select the user to display his pants color'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['user'] = $form_state->getValue('user');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $user = $this->userStorage->load($this->configuration['user']);

    $config = \Drupal::config('pants.settings');
    $pants_color = isset($user->pants_color->value) ? $user->pants_color->value : $config->get('default_color');

    $url = Url::fromRoute('pants.color', ['user' => $user->id()]);
    $link = Link::fromTextAndUrl($this->t('See details'), $url);

    return [
      'color' => [
        '#markup' => $user->getDisplayName() . ' ' . $this->t(' pants are') . ' ' . $pants_color,
      ],
      'link' => [
        '#prefix' => '<br/>',
        '#markup' => $link->toString(),
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    $user = $this->userStorage->load($this->configuration['user']);
    return $user->getCacheTags();
  }

}
