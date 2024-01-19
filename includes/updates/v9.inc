<?php

/**
 * @file
 * Contains varbase_layout_builder_update_9###(s) hook updates.
 */

use Vardot\Installer\ModuleInstallerFactory;

/**
 * Issue #3272216: Switch to use the Module Installer Factory.
 *
 * In the Varbase Layout Builder module.
 */
function varbase_layout_builder_update_90001() {
  // Set the weight of the module after installation of list of modules.
  // To make sure that any hook or event subscriber worker
  // after all used modules.
  ModuleInstallerFactory::setModuleWeightAfterInstallation('varbase_layout_builder');
}

/**
 * Issue #3415842: The Layout Builder Block Sanitizer module was removed.
 *
 * As it is no longer supported. The 10.1.x branch is not using the module.
 */
function varbase_layout_builder_update_90002() {
  // The layout_builder_block_sanitizer module was removed from the 10.1.x branch.
}
