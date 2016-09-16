<?php

/**
 * @file
 * Pants administration UI.
 */

/**
 * Form callback for the pants settings form.
 */
function pants_settings() {
  $available_colors = variable_get('pants_available_colors', array());
  $options = array_combine($available_colors, $available_colors);

  $form['pants_default_color'] = array(
    '#type' => 'select',
    '#options' => $options,
    '#title' => t('Pants default color'),
    '#default_value' => variable_get('pants_default_color', 'blue'),
    '#description' => t('Choose the default color to be used for user who have not selected a pant color.'),
  );

  $form['#validate'][] = 'pants_settings_validate';

  return system_settings_form($form);
}

/**
 * Validate callback for the pants settings form.
 */
function pants_settings_validate($form, &$form_state) {
  if ($form_state['values']['pants_default_color'] == 'green') {
    form_set_error('default_color', t('The green color is not allowed.'));
  }
}
