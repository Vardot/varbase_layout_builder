<?php

/**
 * @file
 * Contains varbase_layout_builder_update_100###(s) hook updates.
 */

use Symfony\Component\Yaml\Yaml;
use Drupal\Core\Config\InstallStorage;
use Vardot\Installer\ModuleInstallerFactory;

/**
 * Issue #3210657: Add Boxed options ( Wide, Medium, Narrow, Tiny )
 */
function varbase_layout_builder_update_10001() {
  $module_path = Drupal::service('module_handler')->getModule('varbase_layout_builder')->getPath();

  // Install configs path.
  $install_path = $module_path . '/' . InstallStorage::CONFIG_INSTALL_DIRECTORY;
  if (is_dir($install_path)) {

    // Have forced configs import.
    $forced_configs_import = [
      'varbase_layout_builder.layout_defaults',
    ];

    foreach ($forced_configs_import as $config_name) {

      $config_path = $install_path . '/' . $config_name . '.yml';
      if (file_exists($config_path)) {
        $config_content = file_get_contents($config_path);
        $config_data = (array) Yaml::parse($config_content);
        $config_factory = \Drupal::configFactory()->getEditable($config_name);
        $config_factory->setData($config_data)->save(TRUE);
      }
    }
  }
}

/**
 * Issue #3218194: Add Vertical and Horizontal alignments.
 *
 * To section styling settings.
 */
function varbase_layout_builder_update_10002() {
  $config_factory = \Drupal::configFactory()->getEditable('bootstrap_styles.settings');
  $config_factory->set('horizontal_alignment', "justify-content-start|Align start\r\njustify-content-center|Align center\r\njustify-content-end|Align end");
  $config_factory->set('vertical_alignment', "align-items-start|Align top\r\nalign-items-center|Align middle\r\nalign-items-end|Align bottom");
  $config_factory->save();
}

/**
 * Issue #3244849: Fix border color (side) variation classes.
 *
 * In Bootstrap Styles for Varbase Layout Builder default configs.
 */
function varbase_layout_builder_update_10003() {
  $config_factory = \Drupal::configFactory()->getEditable('bootstrap_styles.settings');

  // Only update if the border_left_color current config
  // value is not changed from the old config.
  $current_border_left_color = $config_factory->get('border_left_color');
  $old_border_left_color = "border-start border-primary|Primary\r\nborder-start border-secondary|Secondary\r\nborder-start border-success|Success\r\nborder-start border-danger|Danger\r\nborder-start border-warning|Warning\r\nborder-start border-info|Info\r\nborder-start border-light|Light\r\nborder-start border-dark|Dark\r\nborder-start border-white|White";
  $new_border_left_color = "border-start border-start-primary|Primary\r\nborder-start border-start-secondary|Secondary\r\nborder-start border-start-success|Success\r\nborder-start border-start-danger|Danger\r\nborder-start border-start-warning|Warning\r\nborder-start border-start-info|Info\r\nborder-start border-start-light|Light\r\nborder-start border-start-dark|Dark\r\nborder-start border-start-white|White";
  if ($current_border_left_color === $old_border_left_color) {
    $config_factory->set('border_left_color', $new_border_left_color);
    $config_factory->save();
  }

  // Only update if the border_top_color current config
  // value is not changed from the old config.
  $current_border_top_color = $config_factory->get('border_top_color');
  $old_border_top_color = "border-top border-primary|Primary\r\nborder-top border-secondary|Secondary\r\nborder-top border-success|Success\r\nborder-top border-danger|Danger\r\nborder-top border-warning|Warning\r\nborder-top border-info|Info\r\nborder-top border-light|Light\r\nborder-top border-dark|Dark\r\nborder-top border-white|White";
  $new_border_top_color = "border-top border-top-primary|Primary\r\nborder-top border-top-secondary|Secondary\r\nborder-top border-top-success|Success\r\nborder-top border-top-danger|Danger\r\nborder-top border-top-warning|Warning\r\nborder-top border-top-info|Info\r\nborder-top border-top-light|Light\r\nborder-top border-top-dark|Dark\r\nborder-top border-top-white|White";
  if ($current_border_top_color === $old_border_top_color) {
    $config_factory->set('border_top_color', $new_border_top_color);
    $config_factory->save();
  }

  // Only update if the border_right_color current
  // config value is not changed from the old config.
  $current_border_right_color = $config_factory->get('border_right_color');
  $old_border_right_color = "border-end border-primary|Primary\r\nborder-end border-secondary|Secondary\r\nborder-end border-success|Success\r\nborder-end border-danger|Danger\r\nborder-end border-warning|Warning\r\nborder-end border-info|Info\r\nborder-end border-light|Light\r\nborder-end border-dark|Dark\r\nborder-end border-white|White";
  $new_border_right_color = "border-end border-end-primary|Primary\r\nborder-end border-end-secondary|Secondary\r\nborder-end border-end-success|Success\r\nborder-end border-end-danger|Danger\r\nborder-end border-end-warning|Warning\r\nborder-end border-end-info|Info\r\nborder-end border-end-light|Light\r\nborder-end border-end-dark|Dark\r\nborder-end border-end-white|White";
  if ($current_border_right_color === $old_border_right_color) {
    $config_factory->set('border_right_color', $new_border_right_color);
    $config_factory->save();
  }

  // Only update if the border_bottom_color current
  // config value is not changed from the old config.
  $current_border_bottom_color = $config_factory->get('border_bottom_color');
  $old_border_bottom_color = "border-bottom border-primary|Primary\r\nborder-bottom border-secondary|Secondary\r\nborder-bottom border-success|Success\r\nborder-bottom border-danger|Danger\r\nborder-bottom border-warning|Warning\r\nborder-bottom border-info|Info\r\nborder-bottom border-light|Light\r\nborder-bottom border-dark|Dark\r\nborder-bottom border-white|White";
  $new_border_bottom_color = "border-bottom border-bottom-primary|Primary\r\nborder-bottom border-bottom-secondary|Secondary\r\nborder-bottom border-bottom-success|Success\r\nborder-bottom border-bottom-danger|Danger\r\nborder-bottom border-bottom-warning|Warning\r\nborder-bottom border-bottom-info|Info\r\nborder-bottom border-bottom-light|Light\r\nborder-bottom border-bottom-dark|Dark\r\nborder-bottom border-bottom-white|White";
  if ($current_border_bottom_color === $old_border_bottom_color) {
    $config_factory->set('border_bottom_color', $new_border_bottom_color);
    $config_factory->save();
  }
}

/**
 * Issue #3272216: Switch to use the Module Installer Factory.
 *
 * In the Varbase Layout Builder module.
 */
function varbase_layout_builder_update_10004() {
  // Set the weight of the module after installation of list of modules.
  // To make sure that any hook or event subscriber works after
  // all used modules.
  ModuleInstallerFactory::setModuleWeightAfterInstallation('varbase_layout_builder');
}

/**
 * Issue #3272221: Prevent anonymous users from accessing.
 *
 * The Sections Library page.
 */
function varbase_layout_builder_update_10005() {
  if (\Drupal::service('module_handler')->moduleExists('section_library')) {
    $viewSectionLibrary = \Drupal::service('config.factory')->getEditable('views.view.section_library');
    $permission = $viewSectionLibrary->get('display.default.display_options.access.options.perm');

    if ($permission === 'access content') {
      $viewSectionLibrary->set('display.default.display_options.access.options.perm', 'view section library templates')->save(TRUE);
    }
  }
}

/**
 * Issue #3337351: Fix empty/missing box shadow configs.
 *
 * For the Bootstrap Styles.
 */
function varbase_layout_builder_update_10006() {
  if (\Drupal::service('module_handler')->moduleExists('bootstrap_styles')) {
    $bootstrap_styles_settings = \Drupal::service('config.factory')->getEditable('bootstrap_styles.settings');
    $bootstrap_styles_settings->set('box_shadow', "shadow-sm|Small\r\nshadow|Regular\r\nshadow-lg|Large")->save(TRUE);
  }
}
