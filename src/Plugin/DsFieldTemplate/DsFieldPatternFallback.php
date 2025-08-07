<?php

namespace Drupal\varbase_layout_builder\Plugin\DsFieldTemplate;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\ds\Plugin\DsFieldTemplate\DsFieldTemplateBase;
use Drupal\ds\Attribute\DsFieldTemplate;

/**
 * Fallback DS field template plugin for pattern.
 * 
 * This provides a fallback for the 'pattern' plugin that was referenced
 * but missing, preventing fatal errors in the field template system.
 */
#[DsFieldTemplate(
  id: 'pattern',
  title: new TranslatableMarkup('Pattern (Fallback)'),
  theme: 'ds_field_pattern_fallback',
)]
class DsFieldPatternFallback extends DsFieldTemplateBase {

  /**
   * {@inheritdoc}
   */
  public function alterForm(&$form) {
    // Provide basic form alterations similar to other DS field templates
    $form['lb'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#size' => '10',
      '#description' => $this->t('Configure the label'),
    ];

    $form['lbw'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Label wrapper'),
      '#description' => $this->t('Wrap the label in an HTML element.'),
    ];

    $form['lbw-el'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label wrapper element'),
      '#size' => '10',
      '#description' => $this->t('E.g. h1, h2, p'),
      '#states' => [
        'visible' => [
          ':input[name$="[settings_edit_form][third_party_settings][ds][ft][lbw]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['fi'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Field items'),
      '#description' => $this->t('Wrap field items in an HTML element.'),
    ];

    $form['fi-el'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Field items element'),
      '#size' => '10',
      '#description' => $this->t('E.g. div, span, h2'),
      '#states' => [
        'visible' => [
          ':input[name$="[settings_edit_form][third_party_settings][ds][ft][fi]"]' => ['checked' => TRUE],
        ],
      ],
    ];

    $form['fi-cl'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Field items classes'),
      '#size' => '10',
      '#description' => $this->t('Classes for the field items element'),
      '#states' => [
        'visible' => [
          ':input[name$="[settings_edit_form][third_party_settings][ds][ft][fi]"]' => ['checked' => TRUE],
        ],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'lb' => '',
      'lbw' => FALSE,
      'lbw-el' => 'h3',
      'fi' => FALSE,
      'fi-el' => 'div',
      'fi-cl' => '',
    ];
  }

}