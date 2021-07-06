<?php

namespace Drupal\varbase_layout_builder\Plugin\BootstrapStyles\Style;

use Drupal\bootstrap_styles\Style\StylePluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\layout_builder\Form\ConfigureSectionForm;

/**
 * Vertical Alignment.
 *
 * @package Drupal\varbase_layout_builder\Plugin\Style
 *
 * @Style(
 *   id = "vertical_alignment",
 *   title = @Translation("Vertical alignment"),
 *   group_id = "alignment",
 *   weight = 2
 * )
 */
class VerticalAlignment extends StylePluginBase {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $config = $this->config();

    $form['alignment']['vertical_alignment'] = [
      '#type' => 'textarea',
      '#default_value' => $config->get('vertical_alignment'),
      '#title' => $this->t('Vertical alignment (classes)'),
      '#description' => $this->t('<p>Enter one value per line, in the format <b>key|label</b> where <em>key</em> is the CSS class name (without the .), and <em>label</em> is the human readable name of the text.</p>'),
      '#cols' => 60,
      '#rows' => 5,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->config()
      ->set('vertical_alignment', $form_state->getValue('vertical_alignment'))
      ->save();
  }

  /**
   * {@inheritdoc}
   */
  public function buildStyleFormElements(array &$form, FormStateInterface $form_state, $storage) {

    $form_object = $form_state->getFormObject();
    if ($form_object instanceof ConfigureSectionForm) {

      $form['vertical_alignment'] = [
        '#type' => 'radios',
        '#options' => $this->getStyleOptions('vertical_alignment'),
        '#title' => $this->t('Vertical alignment'),
        '#default_value' => $storage['vertical_alignment']['class'] ?? NULL,
        '#validated' => TRUE,
        '#attributes' => [
          'class' => ['field-vertical-alignment', 'bs_input-boxes'],
        ],
      ];

      // Add icons to the container types.
      foreach ($form['vertical_alignment']['#options'] as $key => $value) {
        $form['vertical_alignment']['#options'][$key] = '<span class="input-icon ' . $key . '"></span>' . $value;
      }

      // Attach the Layout Builder form style for this plugin.
      $form['#attached']['library'][] = 'varbase_layout_builder/plugin.vertical_alignment.layout_builder_form';

    }
    else {
      $form['vertical_alignment'] = [
        '#type' => 'radios',
        '#options' => $this->getStyleOptions('vertical_alignment'),
        '#title' => $this->t('Vertical alignment'),
        '#validated' => TRUE,
        '#attributes' => [
          'class' => ['field-vertical-alignment', 'bs_input-boxes'],
        ],
        '#access' => FALSE,
        '#default_value' => NULL,
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitStyleFormElements(array $group_elements) {
    return [
      'vertical_alignment' => [
        'class' => $group_elements['vertical_alignment'],
      ],
    ];
  }

}
