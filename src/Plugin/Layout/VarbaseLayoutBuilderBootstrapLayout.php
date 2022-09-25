<?php

namespace Drupal\varbase_layout_builder\Plugin\Layout;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Serialization\Yaml;
use Drupal\bootstrap_layout_builder\Plugin\Layout\BootstrapLayout;
use Drupal\Component\Utility\Html;

/**
 * A layout from our bootstrap layout builder.
 *
 * @Layout(
 *   id = "bootstrap_layout_builder",
 *   deriver = "Drupal\varbase_layout_builder\Plugin\Deriver\VarbaseLayoutBuilderBootstrapLayoutDeriver"
 * )
 */
class VarbaseLayoutBuilderBootstrapLayout extends BootstrapLayout {

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $build = parent::build($regions);

    // Row classes and attributes.
    $section_classes = [];
    if ($this->configuration['section_classes']) {
      $section_classes = explode(' ', $this->configuration['section_classes']);
      $build['#attributes']['class'] = $section_classes;
    }

    if (!empty($this->configuration['section_attributes'])) {
      $section_attributes = $this->configuration['section_attributes'];
      $build['#attributes'] = NestedArray::mergeDeep($build['#attributes'] ?? [], $section_attributes);
    }

    // The default section header one col layout.
    $blb_settings = $this->configFactory->get('bootstrap_layout_builder.settings');
    $one_col_layout_class = 'col-12';
    if ($blb_settings->get('one_col_layout_class')) {
      $one_col_layout_class = $blb_settings->get('one_col_layout_class');
    }

    $this->configuration['layout_regions_classes']['section_header'][] = $one_col_layout_class;

    // The default one col layout class.
    if (count($this->getPluginDefinition()->getRegionNames()) == 2) {
      $this->configuration['layout_regions_classes']['blb_region_col_1'][] = $one_col_layout_class;
    }

    // Regions classes and attributes.
    if ($this->configuration['regions_classes']) {
      foreach ($this->getPluginDefinition()->getRegionNames() as $region_name) {
        $region_classes = $this->configuration['regions_classes'][$region_name];
        if ($this->configuration['layout_regions_classes'] && isset($this->configuration['layout_regions_classes'][$region_name])) {
          $build[$region_name]['#attributes']['class'] = $this->configuration['layout_regions_classes'][$region_name];
        }
        $build[$region_name]['#attributes']['class'][] = $region_classes;
      }

    }

    if ($this->configuration['regions_attributes']) {
      foreach ($this->getPluginDefinition()->getRegionNames() as $region_name) {
        $region_attributes = $this->configuration['regions_attributes'][$region_name];
        if (!empty($region_attributes)) {
          $build[$region_name]['#attributes'] = NestedArray::mergeDeep($build[$region_name]['#attributes'] ?? [], $region_attributes);
        }
      }

      $section_header_region_attributes = $this->configuration['regions_attributes']['section_header'];
      if (!empty($section_header_region_attributes)) {
        $build['section_header']['#attributes'] = NestedArray::mergeDeep($build['section_header']['#attributes'] ?? [], $section_header_region_attributes);
      }
    }

    // Container.
    if ($this->configuration['container']) {

      // Edge2Edge Background.
      $background_classes = [];
      $edge2edge_background = FALSE;
      if (isset($this->configuration['container_wrapper']['bootstrap_styles']['background_edgetoedge'])
        && $this->configuration['container_wrapper']['bootstrap_styles']['background_edgetoedge'] == 1
        && isset($this->configuration['container_wrapper']['bootstrap_styles']['background']['background_type'])) {

        if ($this->configuration['container_wrapper']['bootstrap_styles']['background']['background_type'] == 'color'
          && !empty($this->configuration['container_wrapper']['bootstrap_styles']['background_color']['class'])
          && $this->configuration['container_wrapper']['bootstrap_styles']['background_color']['class'] !== '_none') {
          $edge2edge_background = TRUE;
        }
        elseif ($this->configuration['container_wrapper']['bootstrap_styles']['background']['background_type'] == 'image'
          && !empty($this->configuration['container_wrapper']['bootstrap_styles']['background_media']['image']['media_id'])) {
          $edge2edge_background = TRUE;
        }
        elseif ($this->configuration['container_wrapper']['bootstrap_styles']['background']['background_type'] == 'video'
          && !empty($this->configuration['container_wrapper']['bootstrap_styles']['background_media']['video']['media_id'])) {
          $edge2edge_background = TRUE;
        }
      }

      // Content.
      $content_classes = [];
      $edge2edge_content = FALSE;
      if ($this->configuration['container'] == 'w-100') {
        $edge2edge_content = TRUE;
        $this->configuration['container'] = 'container-fluid';
      }

      // 1 column.
      $one_column = TRUE;
      if (count($this->getPluginDefinition()->getRegionNames()) > 2) {
        // 2 columns.
        $one_column = FALSE;
      }

      // Gutters.
      $with_gutter = TRUE;
      if ($this->configuration['remove_gutters'] == 1) {
        $with_gutter = FALSE;
      }

      // Edge2Edge Background and Edge2Edge Content.
      if ($edge2edge_background && $edge2edge_content) {
        // 1 Column.
        if ($one_column) {
          // With Gutter.
          if ($with_gutter) {
            $background_classes[] = 'bg-edge2edge';
            $content_classes[] = 'bg-edge2edge';
            $content_classes[] = 'container-fluid';
          }
          // Without Gutter.
          else {
            $background_classes[] = 'bg-edge2edge';
            $content_classes[] = 'bg-edge2edge';
            $content_classes[] = 'no-container';
          }
        }
        // 2+ Columns.
        else {
          // With Gutter.
          if ($with_gutter) {
            $background_classes[] = 'bg-edge2edge';
            $content_classes[] = 'bg-edge2edge';
            $content_classes[] = 'container-fluid';
          }
          // Without Gutter.
          else {
            $background_classes[] = 'bg-edge2edge';
            $content_classes[] = 'bg-edge2edge';
            $content_classes[] = 'no-container';
          }
        }
      }
      // Normal Background and Edge2Edge Content.
      elseif ($edge2edge_content) {
        // 1 Column.
        if ($one_column) {
          // With Gutter.
          if ($with_gutter) {
            $content_classes[] = 'bg-edge2edge';
            $content_classes[] = 'container';
          }
          // Without Gutter.
          else {
            $content_classes[] = 'bg-edge2edge';
          }
        }
        // 2+ Columns.
        else {
          // With Gutter.
          if ($with_gutter) {
            $content_classes[] = 'bg-edge2edge';
            $content_classes[] = 'container';
          }
          // Without Gutter.
          else {
            $content_classes[] = 'bg-edge2edge';
          }
        }
      }
      // Edge2Edge Background and Full-width Content.
      elseif ($edge2edge_background) {
        // 1 Column.
        if ($one_column) {
          // With Gutter.
          if ($with_gutter) {
            $background_classes[] = 'bg-edge2edge';
            $content_classes[] = 'container';
            $content_classes[] = 'gx-3';
          }
          // Without Gutter.
          else {
            $background_classes[] = 'bg-edge2edge';
            $content_classes[] = 'container';
          }
        }
        // 2+ Columns.
        else {
          // With Gutter.
          if ($with_gutter) {
            $background_classes[] = 'bg-edge2edge';
            $content_classes[] = 'container';
            $content_classes[] = 'gx-3';
          }
          // Without Gutter.
          else {
            $background_classes[] = 'bg-edge2edge';
            $content_classes[] = 'container';
          }
        }
      }
      // Normal Background (or NO Background) and Full-width Content.
      else {
        // 1 Column.
        if ($one_column) {
          // With Gutter.
          if ($with_gutter) {
            $content_classes[] = 'container';
          }
          // Without Gutter.
          else {
            $content_classes[] = 'no-container';
          }
        }
        // 2+ Columns.
        else {
          // With Gutter.
          if ($with_gutter) {
            $content_classes[] = 'container';
          }
          // Without Gutter.
          else {
            $content_classes[] = 'no-container';
          }
        }
      }

      $theme_wrappers = [
        'blb_container' => [
          '#attributes' => [
            'class' => $content_classes,
          ],
        ],
        'blb_container_wrapper' => [
          '#attributes' => [
            'class' => $background_classes,
          ],
        ],
      ];

      if (!empty($this->configuration['container_wrapper_id'])) {
        $container_wrapper_id = $this->configuration['container_wrapper_id'];
        $theme_wrappers['blb_container_wrapper']['#attributes']['id'] = $container_wrapper_id;
      }

      if ($this->configuration['container_wrapper_classes']) {
        $theme_wrappers['blb_container_wrapper']['#attributes']['class'][] = $this->configuration['container_wrapper_classes'];
      }

      if (!empty($this->configuration['container_wrapper_attributes'])) {
        $wrapper_attributes = $this->configuration['container_wrapper_attributes'];
        $theme_wrappers['blb_container_wrapper']['#attributes'] = NestedArray::mergeDeep($theme_wrappers['blb_container_wrapper']['#attributes'] ?? [], $wrapper_attributes);
      }

      $build['#theme_wrappers'] = $theme_wrappers;

      // Build dynamic styles.
      $build = $this->stylesGroupManager->buildStyles(
        $build,
      // storage.
        $this->configuration['container_wrapper']['bootstrap_styles'],
      // Theme wrapper that we need to apply styles to it.
        'blb_container_wrapper'
      );
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    // VLB layout defaults.
    $vlb_layout_defaults = \Drupal::config('varbase_layout_builder.layout_defaults');

    // Container type defaults.
    $container_type_defaults = $vlb_layout_defaults->get('container_type');

    // Container types.
    $container_types = [];
    if (isset($container_type_defaults['form_options'])) {
      $container_types = $container_type_defaults['form_options'];
    }

    // Container type default value.
    $container_type_default_value = '';
    if (!empty($this->configuration['container'])) {
      $container_type_default_value = $this->configuration['container'];
    }
    elseif (isset($container_type_defaults['default_value'])) {
      $container_type_default_value = $container_type_defaults['default_value'];
    }

    // Container type weight.
    $container_type_weight = '';
    if (isset($container_type_defaults['weight'])) {
      $container_type_weight = $container_type_defaults['weight'];
    }

    $form['ui']['tab_content']['layout']['container_type'] = [
      '#type' => 'radios',
      '#title' => $this->t('Container type'),
      '#options' => $container_types,
      '#default_value' => $container_type_default_value,
      '#attributes' => [
        'class' => ['blb_container_type'],
      ],
      "#weight" => $container_type_weight,
    ];

    // Add icons to the container types.
    foreach ($form['ui']['tab_content']['layout']['container_type']['#options'] as $key => $value) {
      $form['ui']['tab_content']['layout']['container_type']['#options'][$key] = '<span class="input-icon ' . $key . '"></span>' . $value;
    }

    // Container width defaults.
    $container_width_defaults = $vlb_layout_defaults->get('container_width');

    // Container width options.
    $container_widths = [];
    if (isset($container_width_defaults['form_options'])) {
      $container_widths = $container_width_defaults['form_options'];
    }

    // Container width default value.
    $container_width_default_value = '';
    if (!empty($this->configuration['container_width'])) {
      $container_width_default_value = $this->configuration['container_width'];
    }
    elseif (isset($container_width_defaults['default_value'])) {
      $container_width_default_value = $container_width_defaults['default_value'];
    }

    // Container width weight.
    $container_width_weight = '';
    if (isset($container_width_defaults['weight'])) {
      $container_width_weight = $container_width_defaults['weight'];
    }

    // Container width for Boxed container type.
    $form['ui']['tab_content']['layout']['container_width'] = [
      '#type' => 'radios',
      '#title' => $this->t('Container width'),
      '#options' => $container_widths,
      '#default_value' => $container_width_default_value,
      '#attributes' => [
        'class' => ['vlb_container_width'],
      ],
      '#states' => [
        'visible' => [
          ':input[name="layout_settings[ui][tab_content][layout][container_type]"]' => ['value' => 'container'],
        ],
      ],
      "#weight" => $container_width_weight,
    ];

    // Add icons to Container width.
    foreach ($form['ui']['tab_content']['layout']['container_width']['#options'] as $key => $value) {
      $form['ui']['tab_content']['layout']['container_width']['#options'][$key] = '<span class="input-icon ' . $key . '"></span>' . $value;
    }

    // Remove gutters defaults.
    $remove_gutters_defaults = $vlb_layout_defaults->get('remove_gutters');

    // Gutters types.
    $gutter_types = [];
    if (isset($remove_gutters_defaults['form_options'])) {
      $gutter_types = $remove_gutters_defaults['form_options'];
    }

    // Remove gutters default value.
    $remove_gutters_default_value = 1;
    if (isset($this->configuration['remove_gutters'])
      && $this->configuration['remove_gutters'] !== NULL) {
      $remove_gutters_default_value = $this->configuration['remove_gutters'];
    }
    elseif (isset($remove_gutters_defaults['default_value'])) {
      $remove_gutters_default_value = $remove_gutters_defaults['default_value'];
    }

    // Remove gutters weight.
    $remove_gutters_weight = '';
    if (isset($remove_gutters_defaults['weight'])) {
      $remove_gutters_weight = $remove_gutters_defaults['weight'];
    }

    $form['ui']['tab_content']['layout']['remove_gutters'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gutters'),
      '#options' => $gutter_types,
      '#default_value' => $remove_gutters_default_value,
      '#attributes' => [
        'class' => ['blb_gutter_type'],
      ],
      "#weight" => $remove_gutters_weight,
    ];

    // Add icons to the gutter types.
    foreach ($form['ui']['tab_content']['layout']['remove_gutters']['#options'] as $key => $value) {
      $form['ui']['tab_content']['layout']['remove_gutters']['#options'][$key] = '<span class="input-icon gutter-icon-' . $key . '"></span>' . $value;
    }

    // When number of coloumns for the layout is 2 and more.
    // And Removes Gutters was checked.
    // Then show the Gutters between checkbox.
    if (count($this->getPluginDefinition()->getRegionNames()) > 2) {

      // Gutters between defaults.
      $gutters_between_defaults = $vlb_layout_defaults->get('gutters_between');

      // Gutters between default value.
      $gutters_between_default_value = TRUE;
      if (!empty($this->configuration['gutters_between'])) {
        $gutters_between_default_value = $this->configuration['gutters_between'];
      }
      elseif (isset($gutters_between_defaults['default_value'])) {
        $gutters_between_default_value = $gutters_between_defaults['default_value'];
      }

      // Gutters between weight.
      $gutters_between_weight = '';
      if (isset($gutters_between_defaults['weight'])) {
        $gutters_between_weight = $gutters_between_defaults['weight'];
      }

      $form['ui']['tab_content']['layout']['gutters_between'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Keep gutters between columns'),
        '#default_value' => $gutters_between_default_value,
        '#validated' => TRUE,
        '#attributes' => [
          'class' => ['vlb_gutters_between'],
        ],
        '#states' => [
          'visible' => [
            ':input[name="layout_settings[ui][tab_content][layout][remove_gutters]"]' => ['value' => '1'],
          ],
        ],
        "#weight" => $gutters_between_weight,
      ];
    }

    $layout_id = $this->getPluginDefinition()->id();
    $breakpoints = $this->entityTypeManager->getStorage('blb_breakpoint')->getQuery()->sort('weight', 'ASC')->execute();
    foreach ($breakpoints as $breakpoint_id) {
      $breakpoint = $this->entityTypeManager->getStorage('blb_breakpoint')->load($breakpoint_id);
      $layout_options = $breakpoint->getLayoutOptions($layout_id);
      if ($layout_options) {
        $default_value = '';
        if ($this->configuration['breakpoints'] && isset($this->configuration['breakpoints'][$breakpoint_id])) {
          $default_value = $this->configuration['breakpoints'][$breakpoint_id];
        }
        elseif ($breakpoint_id == "breakpoint_sm" || $breakpoint_id == "breakpoint_xs") {
          $default_value = array_key_last($layout_options);
        }
        elseif (count($layout_options) > 0) {
          $default_value = array_key_first($layout_options);
        }

        $form['ui']['tab_content']['layout']['breakpoints'][$breakpoint_id] = [
          '#type' => 'radios',
          '#title' => $breakpoint->label(),
          '#options' => $layout_options,
          '#default_value' => $default_value,
          '#validated' => TRUE,
          '#attributes' => [
            'class' => ['blb_breakpoint_cols'],
          ],
        ];
      }
    }

    if (isset($form['ui']['tab_content']['layout']['breakpoints'])) {
      // Breakpoints defaults.
      $breakpoints_defaults = $vlb_layout_defaults->get('breakpoints');

      // Breakpoints weight.
      if (isset($breakpoints_defaults['weight'])) {
        $form['ui']['tab_content']['layout']['breakpoints']['#weight'] = $breakpoints_defaults['weight'];
      }
    }

    $form['ui']['tab_content']['settings']['container']['container_wrapper_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Container wrapper ID'),
      '#description' => $this->t('A unique identifier for the container.<br>Allowed characters are letters, numbers, underscores, and dashes.'),
      '#default_value' => $this->configuration['container_wrapper_id'],
      '#weight' => -1,
    ];

    $form['ui']['tab_content']['settings']['regions']['section_header_classes'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Section header classes'),
      '#default_value' => $this->configuration['regions_classes']['section_header'],
      "#weight" => -30,
    ];

    $region_attributes = $this->configuration['regions_attributes']['section_header'];
    $form['ui']['tab_content']['settings']['regions']['section_header_attributes'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Section header attributes (YAML)'),
      '#default_value' => empty($region_attributes) ? '' : Yaml::encode($region_attributes),
      '#attributes' => ['class' => ['auto-size']],
      '#rows' => 1,
      '#element_validate' => [[$this, 'validateYaml']],
      "#weight" => -20,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {

    // The tabs structure.
    $layout_tab = ['ui', 'tab_content', 'layout'];
    $style_tab = ['ui', 'tab_content', 'appearance'];
    $settings_tab = ['ui', 'tab_content', 'settings'];

    // Save section label.
    $this->configuration['label'] = $form_state->getValue(array_merge($settings_tab, ['label']));

    // Container type.
    $this->configuration['container'] = $form_state->getValue(array_merge($layout_tab, ['container_type']));

    if ($this->configuration['container'] == 'container') {
      $this->configuration['container_width'] = $form_state->getValue(array_merge($layout_tab, ['container_width']));
    }
    else {
      $this->configuration['container_width'] = '';
    }

    // Styles tab.
    $this->configuration['container_wrapper']['bootstrap_styles'] = $this->stylesGroupManager->submitStylesFormElements($form['ui']['tab_content']['appearance'], $form_state, $style_tab, $this->configuration['container_wrapper']['bootstrap_styles'], 'bootstrap_layout_builder.styles');

    // Container classes from advanced mode.
    if (!$this->sectionSettingsIsHidden()) {
      $this->configuration['container_wrapper_classes'] = $form_state->getValue(array_merge($settings_tab, ['container', 'container_wrapper_classes']));
      $this->configuration['container_wrapper_attributes'] = Yaml::decode($form_state->getValue(array_merge($settings_tab, ['container', 'container_wrapper_attributes'])));
    }

    // Gutter Classes.
    $this->configuration['remove_gutters'] = $form_state->getValue(array_merge($layout_tab, ['remove_gutters']));

    // Gutters between.
    $this->configuration['gutters_between'] = $form_state->getValue(array_merge($layout_tab, ['gutters_between']));

    // Container wrapper ID
    $this->configuration['container_wrapper_id'] = HTML::cleanCssIdentifier($form_state->getValue(array_merge($settings_tab, ['container', 'container_wrapper_id'])), [' ' => '-']);

    // Row classes from advanced mode.
    if (!$this->sectionSettingsIsHidden()) {
      $this->configuration['section_classes'] = $form_state->getValue(array_merge($settings_tab, ['row', 'section_classes']));
      $this->configuration['section_attributes'] = Yaml::decode($form_state->getValue(array_merge($settings_tab, ['row', 'section_attributes'])));
    }

    $breakpoints = $form_state->getValue(array_merge($layout_tab, ['breakpoints']));
    // Save breakpoints configuration.
    if ($breakpoints) {
      $this->saveBreakpoints($breakpoints);
      foreach ($this->getPluginDefinition()->getRegionNames() as $key => $region_name) {
        // Save layout region classes.
        $this->configuration['layout_regions_classes'][$region_name] = $this->getRegionClasses($key, $breakpoints);
        // Cols classes from advanced mode.
        if (!$this->sectionSettingsIsHidden()) {
          $this->configuration['regions_classes'][$region_name] = $form_state->getValue(array_merge($settings_tab, ['regions', $region_name . '_classes']));
          $this->configuration['regions_attributes'][$region_name] = Yaml::decode($form_state->getValue(array_merge($settings_tab, ['regions', $region_name . '_attributes'])));
        }
      }
    }

    // Section header region classes.
    $this->configuration['layout_regions_classes']['section_header'] = $form_state->getValue(array_merge($settings_tab, ['section_header_classes']));

    // Section header region attributes.
    $this->configuration['regions_attributes']['section_header'] = $form_state->getValue(array_merge($settings_tab, ['section_header_attributes']));

    if (!$this->sectionSettingsIsHidden()) {
      $this->configuration['regions_classes']['section_header'] = $form_state->getValue(array_merge($settings_tab, ['regions', 'section_header_classes']));
      $this->configuration['regions_attributes']['section_header'] = Yaml::decode($form_state->getValue(array_merge($settings_tab, ['regions', 'section_header_attributes'])));
    }

    $blb_settings = $this->configFactory->get('bootstrap_layout_builder.settings');
    $one_col_layout_class = 'col-12';
    if ($blb_settings->get('one_col_layout_class')) {
      $one_col_layout_class = $blb_settings->get('one_col_layout_class');
    }

    if (isset($breakpoints) && is_array($breakpoints) && count($breakpoints) > 0) {
      foreach ($breakpoints as $breakpoint_key => $breakpoint_id) {
        $this->configuration['layout_regions_classes']['section_header'][] = $one_col_layout_class;
      }
    }

    // Cols classes from advanced mode.
    if (!$this->sectionSettingsIsHidden()) {
      $this->configuration['regions_classes']['section_header'] = $form_state->getValue(array_merge($settings_tab, ['regions', 'section_header_classes']));
      $this->configuration['regions_attributes']['section_header'] = Yaml::decode($form_state->getValue(array_merge($settings_tab, ['regions', 'section_header_attributes'])));
    }

    $first_layout_region_classes = [];
    foreach ($this->getPluginDefinition()->getRegionNames() as $key => $region_name) {

      if (is_countable($first_layout_region_classes)) {
        if (count($first_layout_region_classes) < 1) {
          if (isset($this->configuration['layout_regions_classes'])
            && isset($this->configuration['layout_regions_classes'][$region_name])
            && is_array($this->configuration['layout_regions_classes'][$region_name])
            && count($this->configuration['layout_regions_classes'][$region_name]) > 0) {

            $first_layout_region_classes = $this->configuration['layout_regions_classes'][$region_name];
          }
        }
        else {
          if (isset($this->configuration['layout_regions_classes'])
            && isset($this->configuration['layout_regions_classes'][$region_name])
            && is_array($this->configuration['layout_regions_classes'][$region_name])
            && count($this->configuration['layout_regions_classes'][$region_name]) > 0) {

            foreach ($this->configuration['layout_regions_classes'][$region_name] as $region_key => $region_class) {
              if ($region_class == "col-xl-"
                || $region_class == "col-lg-"
                || $region_class == "col-md-"
                || $region_class == "col-sm-"
                || $region_class == "col-") {

                if (isset($this->configuration['layout_regions_classes'][$region_name][$region_key])
                  && isset($first_layout_region_classes[$region_key])) {

                  $this->configuration['layout_regions_classes'][$region_name][$region_key] = $first_layout_region_classes[$region_key];
                }
              }
            }
          }
        }
      }
    }

  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $default_configuration = parent::defaultConfiguration();

    return $default_configuration + [
      'container_wrapper_id' => '',
    ];
  }

}
