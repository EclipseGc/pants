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
use Drupal\pants\PantsColor as PantsColorService;
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
   * The pants color service.
   *
   * @var \Drupal\pants\PantsColor
   */
  protected $pantsColor;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, UserStorageInterface $user_storage, PantsColorService $pants_color) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->userStorage = $user_storage;
    $this->pantsColor = $pants_color;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity.manager')->getStorage('user'),
      $container->get('pants.color')
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

    $url = Url::fromRoute('pants.color', ['user' => $user->id()]);
    $link = Link::fromTextAndUrl($this->t('See details'), $url);

    return [
      'color' => [
        '#markup' => $user->getDisplayName() . ' ' . $this->t(' pants are') . ' ' . $this->pantsColor->getPantsColor($user),
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
