<?php

/**
 * @file
 * Contains the page callback for the Pants module.
 */

/**
 * Page callback for pants/color/%user.
 *
 * @param stdClass $account
 *   A user object.
 */
function pants_color($account) {
  $pants_color = isset($account->pants_color[LANGUAGE_NONE][0]['value']) ? $account->pants_color[LANGUAGE_NONE][0]['value'] : variable_get('pants_default_color', 'blue');

  return array(
    '#markup' => $pants_color,
  );
}