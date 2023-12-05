<?php

/**
 * @file
 * Contains vlplb_update_8###(s) hook updates.
 */

/**
 * Issue #3175756: Fix deprecated function getCurrentUserId replaced.
 *
 *  By getDefaultEntityOwner in
 *  core.base_field_override.node.landing_page_lb.uid config.
 */
function vlplb_update_8101() {
  $config_factory = \Drupal::service('config.factory');
  $node_uid_configs = $config_factory->listAll('core.base_field_override.node.');

  // Update for core.base_field_override.node.landing_page_lb.uid
  // And all content types.
  foreach ($node_uid_configs as $node_uid_config) {
    if (substr($node_uid_config, -4) === ".uid") {

      $node_uid_config_factory = $config_factory->getEditable($node_uid_config);
      $default_value_callback = $node_uid_config_factory->get('default_value_callback');

      if (isset($default_value_callback)
        && !empty($default_value_callback)
        && $default_value_callback === 'Drupal\node\Entity\Node::getCurrentUserId') {

        $node_uid_config_factory->set('default_value_callback', 'Drupal\node\Entity\Node::getDefaultEntityOwner')->save(TRUE);
      }
    }
  }

}
