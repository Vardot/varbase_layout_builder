<?php

namespace Drupal\varbase_layout_builder\Plugin\BootstrapStyles\StylesGroup;

use Drupal\bootstrap_styles\StylesGroup\StylesGroupPluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\layout_builder\Form\ConfigureSectionForm;

/**
 * Class Alignment.
 *
 * @package Drupal\varbase_layout_builder\Plugin\StylesGroup
 *
 * @StylesGroup(
 *   id = "alignment",
 *   title = @Translation("Blocks alignment"),
 *   weight = 3,
 *   icon = "varbase_layout_builder/images/plugins/alignment-icon.svg"
 * )
 */
class Alignment extends StylesGroupPluginBase {

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {

    $form_object = $form_state->getFormObject();
    if ($form_object instanceof ConfigureSectionForm) {
      $form['alignment'] = [
        '#type' => 'details',
        '#title' => $this->t('Blocks alignment'),
        '#open' => FALSE,
      ];
    }

    return $form;
  }

}
