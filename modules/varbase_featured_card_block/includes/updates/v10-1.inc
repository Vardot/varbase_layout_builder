<?php

/**
 * @file
 * Contains varbase_featured_card_block_101###(s) hook updates.
 */

/**
 * Issue #3487440: Fix Featured Card Block Responsiveness on Mobile by Moving Image Above Text.
 *
 * To Featured Card Block.
 */
function varbase_featured_card_block_update_10101() {
  // Change columns layout for featured card block.
  // when old config is not changed.
  $old_config_array = [
    'style_size' => '',
    'card_border' => 1,
    'media_position' => '',
    'columns_xs_size' => '06_06',
    'columns_sm_size' => '',
    'columns_md_size' => '',
    'columns_lg_size' => '',
    'columns_xl_size' => '',
    'columns_xxl_size' => '',
    'utility_classes' => '',
    'card_attributes' => '',
    'content_attributes' => '',
    'row_attributes' => '',
    'start_column_attributes' => '',
    'end_column_attributes' => '',
  ];

  $current_block_config = \Drupal::service('config.factory')->getEditable('core.entity_view_display.block_content.varbase_featured_card_block.default');
  $current_config_array = $current_block_config->get('third_party_settings.ds.layout.settings.pattern.settings');

  // Check if the current configuration matches the defined old configuration.
  if ($old_config_array === $current_config_array) {
    $new_config_array = $current_block_config->get();

    // Update specific values in the new configuration.
    $new_config_array['third_party_settings']['ds']['layout']['settings']['pattern']['settings']['columns_xs_size'] = '12_12';
    $new_config_array['third_party_settings']['ds']['layout']['settings']['pattern']['settings']['columns_lg_size'] = '06_06';

    // Set and save the new configuration.
    $current_block_config->setData($new_config_array)->save();
  }
}
