<?php

/**
 * @file
 * Contains varbase_layout_builder_update_101###(s) hook updates.
 */

use Drupal\Core\Recipe\Recipe;
use Drupal\Core\Recipe\RecipeRunner;
use Vardot\Installer\ModuleInstallerFactory;

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

/**
 * Issue #3409288: Add permission to layout builder actions for sections and blocks.
 *
 * To Layout Builder Advanced Permissions module.
 */
function varbase_layout_builder_update_101003() {
  $module_path = Drupal::service('module_handler')->getModule('varbase_layout_builder')->getPath();
  $recipe = Recipe::createFromDirectory($module_path . '/recipes/updates/varbase_layout_builder_update_101003');
  RecipeRunner::processRecipe($recipe);
}

/**
 * Issue #3518161: Fix conflict of order between Layout Builder Perms and Asymmetric Translation.
 *
 * Set a higher module weight for Layout Builder Asymmetric Translation module.
 */
function varbase_layout_builder_update_101004() {
  if (\Drupal::service('module_handler')->moduleExists('layout_builder_at')
    && \Drupal::service('module_handler')->moduleExists('layout_builder_perms')) {

    // Set a higher module weight for layout_builder_at.
    // To Ensure layout_builder_at's plugins is registered after the one from layout_builder_perms.
    ModuleInstallerFactory::setModuleWeightAfterInstallation('layout_builder_at', '', ['layout_builder_perms']);
  }
}
