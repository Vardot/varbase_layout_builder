<?php

namespace Drupal\varbase_layout_builder\Plugin\BootstrapStyles\StylesGroup;

use Drupal\bootstrap_styles\StylesGroup\StylesGroupPluginBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Blocks alignment.
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

    $form['alignment'] = [
      '#type' => 'details',
      '#title' => $this->t('Blocks alignment'),
      '#open' => FALSE,
    ];

    return $form;
  }

}
