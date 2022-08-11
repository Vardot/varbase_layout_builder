<?php

namespace Drupal\varbase_buttons_block\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\varbase_buttons_block\varbaseBootstrapButtonLinkHelper;

/**
 * Defines a form that configures varbase button link settings.
 */
class VarbaseButtonsBlockSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'varbase_buttons_block_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'varbase_buttons_block.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $configs = $this->config('varbase_buttons_block.settings');
 
    $button_colors = $configs->get('colors');
    
    $button_colors_value = varbaseBootstrapButtonLinkHelper::makeValueFromConfigs($button_colors);

    $form['colors'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Colors'),
      '#default_value' => $button_colors_value,
      '#rows' => count($button_colors),
      '#description' => $this->t('Have a list of button link styles options. There should be pairs of bootstrap button classes and their titles in format: bootstrap-class|Style Title, for example: btn-primary|Primary button.'),
    ];

    $button_sizes = $configs->get('sizes');
    $button_sizes_value = varbaseBootstrapButtonLinkHelper::makeValueFromConfigs($button_sizes);

    $form['sizes'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Sizes'),
      '#default_value' => $button_sizes_value,
      '#rows' => count($button_sizes),
      '#description' => $this->t('Have a list of button sizing options. There should be pairs of bootstrap button classes and their titles in format: bootstrap-class|Style Title, for example: btn-large|Large.'),
    ];

    // Allowed Override Options.
    $form['override'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Allowed Override Options'),
      '#description' => $this->t('Specify here which button link options should be overridable in the field widget.'),
    ];

    $form['override']['color'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Color'),
      '#default_value' => $configs->get('override_color'),
    ];

    $form['override']['outline'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Outline'),
      '#default_value' => $configs->get('override_outline'),
    ];

    $form['override']['size'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Size'),
      '#default_value' => $configs->get('override_size'),
    ];

    $form['override']['target'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Target'),
      '#default_value' => $configs->get('override_target'),
    ];

    $form['override']['block_level'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Block level'),
      '#default_value' => $configs->get('override_block_level'),
    ];

    $form['override']['disabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Disabled'),
      '#default_value' => $configs->get('override_disabled'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    $values = $form_state->getValues();

    // Validate the Bootstrap Button color options and save.
    try {
      $button_colors_options = varbaseBootstrapButtonLinkHelper::parseConfigsFromValueWithCleanCssIdentifier($values['colors']);
    }
    catch (\Exception $e) {
      switch ($e->getCode()) {
        case 0x01:
          $form_state->setErrorByName('colors', $this->t('Styles options must be entered in format: bootstrap-class|Title, for example: btn-primary|Primary button. One per line.'));
          break;

        case 0x02:
          $form_state->setErrorByName('colors', $this->t("Some css class doesn\'t corresponds to correct format."));
          break;

        case 0x03:
          $form_state->setErrorByName('colors', $this->t('Some style option name has special characters.'));
          break;
      }

      return;
    }

    $form_state->setValue('colors', $button_colors_options);

    // Validate the Bootstrap Button sizes options and save.
    try {
      $button_sizes_options = varbaseBootstrapButtonLinkHelper::parseConfigsFromValueWithCleanCssIdentifier($values['sizes']);
    }
    catch (\Exception $e) {
      switch ($e->getCode()) {
        case 0x01:
          $form_state->setErrorByName('sizes', $this->t('Styles options must be entered in format: bootstrap-class|Title, for example: btn-primary|Primary button. One per line.'));
          break;

        case 0x02:
          $form_state->setErrorByName('sizes', $this->t("Some css class doesn\'t corresponds to correct format."));
          break;

        case 0x03:
          $form_state->setErrorByName('sizes', $this->t('Some style option name has special characters.'));
          break;
      }

      return;
    }

    $form_state->setValue('sizes', $button_sizes_options);

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config('varbase_buttons_block.settings')
      ->set('colors', $values['colors'])
      ->set('sizes', $values['sizes'])
      ->set('override_color', $values['color'])
      ->set('override_size', $values['size'])
      ->set('override_outline', $values['outline'])
      ->set('override_target', $values['target'])
      ->set('override_block_level', $values['block_level'])
      ->set('override_disabled', $values['disabled'])
      ->save();

    // Clar cache where Varbase Bootstrap Button Link formatter was used.
    Cache::invalidateTags(['varbase_bootstrap_button_link__field_formatter']);

    parent::submitForm($form, $form_state);
  }

}
