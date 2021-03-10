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
 *   deriver = "Drupal\bootstrap_layout_builder\Plugin\Deriver\BootstrapLayoutDeriver"
 * )
 */
class VarbaseLayoutBuilderBootstrapLayout extends BootstrapLayout {

  /**
   * {@inheritdoc}
   */
  public function build(array $regions) {
    $build = parent::build($regions);


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
      if (count($this->getPluginDefinition()->getRegionNames()) > 1) {
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

    $tabs = [
      [
        'machine_name' => 'layout',
        'icon' => 'layout.svg',
        'title' => $this->t('Layout'),
        'active' => TRUE,
      ],
      [
        'machine_name' => 'appearance',
        'icon' => 'appearance.svg',
        'title' => $this->t('Style'),
      ],
      [
        'machine_name' => 'settings',
        'icon' => 'settings.svg',
        'title' => $this->t('Settings'),
      ],
    ];

    $form['ui']['tab_content']['layout']['section_title'] = [
      '#type' => 'textfield',
      '#default_value' => !empty($this->configuration['section_title']) ? $this->configuration['section_title'] : '',
      '#title' => $this->t('Section title'),
      "#weight" => -50,
    ];

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

    if (count($this->getPluginDefinition()->getRegionNames()) > 1) {
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

    // Container wrapper styling.
    $form['ui']['tab_content']['appearance'] = $this->stylesGroupManager->buildStylesFormElements($form['ui']['tab_content']['appearance'], $form_state, $this->configuration['container_wrapper']['bootstrap_styles'], 'bootstrap_layout_builder.styles');

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

    // Save section title.
    $this->configuration['section_title'] = $form_state->getValue(array_merge($layout_tab, ['section_title']));

    // Gutters between.
    $this->configuration['gutters_between'] = $form_state->getValue(array_merge($layout_tab, ['gutters_between']));

  }

}
