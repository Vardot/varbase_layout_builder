<?php

namespace Drupal\varbase_layout_builder\Plugin\Layout;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Layout\LayoutDefault;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Serialization\Yaml;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\bootstrap_styles\StylesGroup\StylesGroupManager;
use Drupal\bootstrap_layout_builder\Plugin\Layout\BootstrapLayout;

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
        else if ($this->configuration['container_wrapper']['bootstrap_styles']['background']['background_type'] == 'image'
          && !empty($this->configuration['container_wrapper']['bootstrap_styles']['background_media']['image']['media_id'])) {
          $edge2edge_background = TRUE;
        }
        else if ($this->configuration['container_wrapper']['bootstrap_styles']['background']['background_type'] == 'video'
          && !empty($this->configuration['container_wrapper']['bootstrap_styles']['background_media']['video']['media_id'])) {
          $edge2edge_background = TRUE;
        }
      }

      // Content.
      $content_classes = [];
      $edge2edge_content = FALSE;
      if ($this->configuration['container'] == 'w-100') {
        $edge2edge_content = TRUE;
        $this->configuration['container'] == 'container-fluid';
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
      else if ($edge2edge_content) {
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
      else if ($edge2edge_background) {
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

    $container_types = [
      'container-fluid' => $this->t('Full'),
      'w-100' => $this->t('Edge to Edge'),
      'container' => $this->t('Boxed'),
    ];

    $form['ui']['tab_content']['layout']['container_type'] = [
      '#type' => 'radios',
      '#title' => $this->t('Container type'),
      '#options' => $container_types,
      '#default_value' => !empty($this->configuration['container']) ? $this->configuration['container'] : 'container-fluid',
      '#attributes' => [
        'class' => ['blb_container_type'],
      ],
      "#weight" => -40,
    ];

    // Add icons to the container types.
    foreach ($form['ui']['tab_content']['layout']['container_type']['#options'] as $key => $value) {
      $form['ui']['tab_content']['layout']['container_type']['#options'][$key] = '<span class="input-icon ' . $key . '"></span>' . $value;
    }

    $gutter_types = [
      1 => $this->t('No Gutters'),
      0 => $this->t('With Gutters'),
    ];

    $form['ui']['tab_content']['layout']['remove_gutters'] = [
      '#type' => 'radios',
      '#title' => $this->t('Gutters'),
      '#options' => $gutter_types,
      '#default_value' => ((isset($this->configuration['remove_gutters'])) ? $this->configuration['remove_gutters'] : TRUE),
      '#attributes' => [
        'class' => ['blb_gutter_type'],
      ],
      "#weight" => -30,
    ];

    if (count($this->getPluginDefinition()->getRegionNames()) > 2) {
      $form['ui']['tab_content']['layout']['gutters_between'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Keep gutters between columns'),
        '#default_value' => ((isset($this->configuration['gutters_between'])) ? $this->configuration['gutters_between'] : TRUE),
        '#validated' => TRUE,
        '#attributes' => [
          'class' => ['field-gutters-between'],
        ],
        '#states' => [
          'visible' => [
            ':input[name="layout_settings[ui][tab_content][layout][remove_gutters]"]' => ['value' => '1'],
          ],
        ],
        "#weight" => -20,
      ];
    }

    // Add icons to the gutter types.
    foreach ($form['ui']['tab_content']['layout']['remove_gutters']['#options'] as $key => $value) {
      $form['ui']['tab_content']['layout']['remove_gutters']['#options'][$key] = '<span class="input-icon gutter-icon-' . $key . '"></span>' . $value;
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
        else if ($breakpoint_id == "breakpoint_sm" || $breakpoint_id == "breakpoint_xs") {
          $default_value = array_key_last($layout_options);
        }
        else if (count($layout_options) > 0) {
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
          "#weight" => -10,
        ];
      }
    }

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
    parent::submitConfigurationForm($form, $form_state);
    // The tabs structure.
    $layout_tab = ['ui', 'tab_content', 'layout'];
    $style_tab = ['ui', 'tab_content', 'appearance'];
    $settings_tab = ['ui', 'tab_content', 'settings'];

    // Gutters between.
    $this->configuration['gutters_between'] = $form_state->getValue(array_merge($layout_tab, ['gutters_between']));

    // Section header region classes
    $this->configuration['layout_regions_classes']['section_header'] = $form_state->getValue(array_merge($settings_tab, ['section_header_classes']));

    // Section header region classes
    $this->configuration['layout_regions_classes']['section_header'] = $form_state->getValue(array_merge($settings_tab, ['section_header_classes']));

    if (!$this->sectionSettingsIsHidden()) {
      $this->configuration['regions_classes']['section_header'] = $form_state->getValue(array_merge($settings_tab, ['regions', 'section_header_classes']));
      $this->configuration['regions_attributes']['section_header'] = Yaml::decode($form_state->getValue(array_merge($settings_tab, ['regions', 'section_header_attributes'])));
    }

    $breakpoints = $form_state->getValue(array_merge($layout_tab, ['breakpoints']));
    // Save breakpoints configuration.
    if ($breakpoints) {

      $first_layout_region_classes = [];
      foreach ($this->getPluginDefinition()->getRegionNames() as $key => $region_name) {

        if ($region_name == 'section_header') {

          $blb_settings = $this->configFactory->get('bootstrap_layout_builder.settings');
          $one_col_layout_class = 'col-12';
          if ($blb_settings->get('one_col_layout_class')) {
            $one_col_layout_class = $blb_settings->get('one_col_layout_class');
          }

          foreach($breakpoints as $breakpoint_key => $breakpoint_id) {
            $this->configuration['layout_regions_classes']['section_header'][$breakpoint_key] = $one_col_layout_class;
          }
        }
        else {
          $this->configuration['layout_regions_classes'][$region_name] = $this->getRegionClasses($key, $breakpoints);

          if (count($first_layout_region_classes) < 1 ) {
            $first_layout_region_classes = $this->configuration['layout_regions_classes'][$region_name];
          }
          else {
            foreach ($this->configuration['layout_regions_classes'][$region_name] as $region_key => $region_class) {
              if ($region_class == "col-xl-"
                || $region_class == "col-lg-"
                || $region_class == "col-md-"
                || $region_class == "col-sm-"
                || $region_class == "col-") {
                  $this->configuration['layout_regions_classes'][$region_name] = $first_layout_region_classes;
                break;
              }
            }
          }
        }
      }
    }
  }

}
