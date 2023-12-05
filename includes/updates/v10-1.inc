<?php

/**
 * @file
 * Contains varbase_layout_builder_update_101###(s) hook updates.
 */

/**
 * Issue #3326408: Switch from Layout Builder Modal module.
 *
 * To Layout Builder iFrame Modal module.
 */
function varbase_layout_builder_update_10101() {

  if (!\Drupal::moduleHandler()->moduleExists('layout_builder_modal')) {
    \Drupal::service('module_installer')->uninstall(['layout_builder_modal']);
  }

  if (!\Drupal::moduleHandler()->moduleExists('layout_builder_iframe_modal')) {
    \Drupal::service('module_installer')->install(['layout_builder_iframe_modal'], FALSE);
  }
}

/**
 * Issue #3383937: Remove the remaining optional config.
 *
 * For layout_builder_iframe_modal.settings config.
 */
function varbase_layout_builder_update_10102() {

  // Install Layout Builder Modal module if it was not enabled.
  if (!\Drupal::moduleHandler()->moduleExists('layout_builder_modal')) {
    \Drupal::service('module_installer')->install(['layout_builder_modal'], FALSE);
  }

  // Remove the leftover optional layout_builder_iframe_modal.settings
  // config if the module was uninstalled.
  if (!\Drupal::moduleHandler()->moduleExists('layout_builder_iframe_modal')) {
    \Drupal::configFactory()->getEditable('layout_builder_iframe_modal.settings')->delete();
  }
}
