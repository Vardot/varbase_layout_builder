<?php

namespace Drupal\varbase_layout_builder\Plugin\BootstrapStyles\Style;

use Drupal\bootstrap_styles\Style\StylePluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Template\Attribute;

/**
 * Class TextAlignment.
 *
 * @package Drupal\varbase_layout_builder\Plugin\Style
 *
 * @Style(
 *   id = "section_title_alignment",
 *   title = @Translation("Section Title Alignment"),
 *   group_id = "typography",
 *   weight = 1
 * )
 */
class SectionTitleAlignment extends StylePluginBase {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $config = $this->config();

    $form['typography']['text_alignment']['#title'] = $this->t('Body alignment (classes)');
    $form['typography']['text_alignment']['#weight'] = -10;

    $form['typography']['section_title_alignment'] = [
      '#type' => 'textarea',
      '#default_value' => $config->get('section_title_alignment'),
      '#title' => $this->t('Section title alignment (classes)'),
      '#description' => $this->t('<p>Enter one value per line, in the format <b>key|label</b> where <em>key</em> is the CSS class name (without the .), and <em>label</em> is the human readable name of the text.</p>'),
      '#cols' => 60,
      '#rows' => 5,
      '#weight' => -20,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->config()
      ->set('section_title_alignment', $form_state->getValue('section_title_alignment'))
      ->save();
  }

  /**
   * {@inheritdoc}
   */
  public function buildStyleFormElements(array &$form, FormStateInterface $form_state, $storage) {

    $form['text_alignment']['#title'] = $this->t('Body alignment');
    $form['text_alignment']['#weight'] = -10;

    $form['section_title_alignment'] = [
      '#type' => 'radios',
      '#options' => $this->getStyleOptions('section_title_alignment'),
      '#title' => $this->t('Section title alignment'),
      '#default_value' => $storage['section_title_alignment']['class'] ?? NULL,
      '#validated' => TRUE,
      '#attributes' => [
        'class' => ['field-section-title-alignment', 'bs_input-boxes'],
      ],
      "#weight" => -20
    ];



    // Add icons to the container types.
    foreach ($form['section_title_alignment']['#options'] as $key => $value) {
      $form['section_title_alignment']['#options'][$key] = '<span class="input-icon ' . $key . '"></span>' . $value;
    }

    // Attach the Layout Builder form style for this plugin.
    $form['#attached']['library'][] = 'bootstrap_styles/plugin.text_alignment.layout_builder_form';

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitStyleFormElements(array $group_elements) {
    return [
      'section_title_alignment' => [
        'class' => $group_elements['section_title_alignment'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $build, array $storage, $theme_wrapper = NULL) {
    $classes = ['section-title'];
    if (isset($storage['section_title_alignment']['class'])) {
      $classes[] = $storage['section_title_alignment']['class'];
    }

    $section_title_attributes = new Attribute();
    $section_title_attributes->addClass($classes);

    $build['#settings']['section_title_attributes'] = $section_title_attributes;

    return $build;
  }

}
