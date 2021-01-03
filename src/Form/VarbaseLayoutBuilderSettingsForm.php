<?php

namespace Drupal\varbase_layout_builder\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * The settings for the Varbase Layout Builder.
 */
class VarbaseLayoutBuilderSettingsForm extends ConfigFormBase {

  /**
   * Get the from ID.
   */
  public function getFormId() {
    return 'varbase_layout_builder_settings';
  }

  /**
   * Get the editable config names.
   */
  protected function getEditableConfigNames() {
    return ['varbase_layout_builder.settings'];
  }

  /**
   * Build the form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $config = $this->config('varbase_layout_builder.settings');
    $form['settings']['use_claro'] = [
      '#type' => 'checkbox',
      '#default_value' => $config->get('use_claro'),
      '#title' => $this->t('Use claro theme inside layout builder modal'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Submit Form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $config = $this->config('varbase_layout_builder.settings');
    $config->set('use_claro', $form_state->getValue('use_claro'));
    $config->save();

    parent::submitForm($form, $form_state);
  }

}
