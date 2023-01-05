<?php

namespace Drupal\varbase_layout_builder\Element;

use Drupal\layout_builder\Element\LayoutBuilder;
use Drupal\layout_builder\SectionStorageInterface;
use Drupal\Core\Url;

/**
 * Alters the Layout Builder UI element.
 */
class VarbaseLayoutBuilderUX extends LayoutBuilder {

  /**
   * {@inheritdoc}
   */
  protected function buildAdministrativeSection(SectionStorageInterface $section_storage, $delta) {
    $build = parent::buildAdministrativeSection($section_storage, $delta);

    $storage_type = $section_storage->getStorageType();
    $storage_id = $section_storage->getStorageId();
    $section = $section_storage->getSection($delta);

    $layout = $section->getLayout();
    $layout_settings = $section->getLayoutSettings();
    $section_label = !empty($layout_settings['label']) ? $layout_settings['label'] : $this->t('Section @section', ['@section' => $delta + 1]);

    $layout_definition = $layout->getPluginDefinition();

    $region_labels = $layout_definition->getRegionLabels();

    $section_label = $build['#attributes']['aria-label'];

    foreach ($layout_definition->getRegions() as $region => $info) {
      if ($region == 'section_header') {
        $build['layout-builder__section']['section_header']['layout_builder_add_block']['link'] = [
          '#type' => 'link',
          '#title' => $this->t('Add heading <span class="visually-hidden">in @section, @region region</span>', ['@section' => $section_label, '@region' => $region_labels['section_header']]),
          '#url' => Url::fromRoute('layout_builder.choose_block',
            [
              'section_storage_type' => $storage_type,
              'section_storage' => $storage_id,
              'delta' => $delta,
              'region' => 'section_header',
            ],
            [
              'attributes' => [
                'class' => [
                  'use-ajax',
                  'layout-builder__link',
                  'layout-builder__link--add',
                ],
                'data-dialog-type' => 'dialog',
                'data-dialog-renderer' => 'off_canvas',
              ],
            ]
          ),
        ];
      }
    }

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  protected function layout(SectionStorageInterface $section_storage) {
    $build = parent::layout($section_storage);
    $build['#attached']['library'][] = 'varbase_layout_builder/enhancements';
    $build['#attached']['library'][] = 'varbase_layout_builder/configure-section.admin';
    return $build;
  }

}
