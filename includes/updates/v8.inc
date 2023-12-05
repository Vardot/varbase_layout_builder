<?php

/**
 * @file
 * Contains varbase_layout_builder_update_8###(s) hook updates.
 */

/**
 * Issue #3104761: Add and enable [Entity Blocks] module ( ~1.0 )
 */
function varbase_layout_builder_update_8101() {
  if (!\Drupal::moduleHandler()->moduleExists('entity_block')) {
    \Drupal::service('module_installer')->install(['entity_block'], FALSE);
  }
}

/**
 * Issue #3169416: Theme Layout Builder Modal and Media Library to look like.
 *
 * Claro admin theme.
 */
function varbase_layout_builder_update_8102() {

  if (!version_compare(\Drupal::VERSION, '8.8.0', 'lt')) {
    if (!\Drupal::service('theme_handler')->themeExists('claro')) {

      // Install Claro theme.
      \Drupal::service('theme_installer')->install(['claro'], FALSE);
    }

    if (\Drupal::moduleHandler()->moduleExists('media_library_theme_reset')) {
      \Drupal::service('module_installer')->uninstall(['media_library_theme_reset'], FALSE);
    }

    $config_factory = \Drupal::service('config.factory')->getEditable('varbase_layout_builder.settings');
    $config_factory->set('use_claro', 1)->save(TRUE);
  }
}

/**
 * Issue #3170980: Add Layout Builder Component Attributes module.
 */
function varbase_layout_builder_update_8103() {
  if (!\Drupal::moduleHandler()->moduleExists('layout_builder_component_attributes')) {
    \Drupal::service('module_installer')->install(['layout_builder_component_attributes'], FALSE);
  }
}
