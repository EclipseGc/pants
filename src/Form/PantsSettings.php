<?php

/**
 * @file
 * Contains \Drupal\pants\Form\PantsSettings.
 */

namespace Drupal\pants\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class PantsSettings extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'pants_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('pants.settings');

    $config->set('default_color', $form_state->getValue('default_color'));

    $config->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getValue('default_color') == 'green') {
      $form_state->setErrorByName('default_color', $this->t('The green color is not allowed.'));
    }

    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('pants.settings');

    $available_colors = $config->get('available_colors');
    $options = array_combine($available_colors, $available_colors);

    $form['default_color'] = [
      '#type' => 'select',
      '#title' => $this->t('Pants default color'),
      '#options' => $options,
      '#default_value' => $config->get('default_color'),
      '#description' => $this->t('Choose the default color to be used for user who have not selected a pant color.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['pants.settings'];
  }
}
