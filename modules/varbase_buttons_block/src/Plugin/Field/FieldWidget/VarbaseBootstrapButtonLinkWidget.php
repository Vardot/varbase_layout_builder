<?php

namespace Drupal\varbase_buttons_block\Plugin\Field\FieldWidget;

use Drupal\Core\Config\Config;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\link\Plugin\Field\FieldWidget\LinkWidget;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'Varbase Bootstrap Button Link' widget.
 *
 * @FieldWidget(
 *   id = "varbase_bootstrap_button_link_widget",
 *   label = @Translation("Varbase Bootstrap Button"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class VarbaseBootstrapButtonLinkWidget extends LinkWidget {

  /**
   * Contains the varbase_buttons_block.settings configuration object.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $varbaseBootstrapButtonLinkConfigs;

  /**
   * Constructs a varbaseBootstrapButtonLinkWidget object.
   *
   * @param string $plugin_id
   *   The plugin_id for the widget.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the widget is associated.
   * @param array $settings
   *   The widget settings.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param \Drupal\Core\Config\Config $varbase_buttons_block_configs
   *   The varbase_buttons_block.settings configuration factory object.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, Config $varbase_buttons_block_configs) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->varbaseBootstrapButtonLinkConfigs = $varbase_buttons_block_configs;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('config.factory')->get('varbase_buttons_block.settings')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    $_none = ['_none' => ' ' . $this->t('-  None  -') . ' '];

    $button_colors = $_none + $this->varbaseBootstrapButtonLinkConfigs->get('colors');
    $button_sizes = $_none + $this->varbaseBootstrapButtonLinkConfigs->get('sizes');

    $button_default_color = $this->varbaseBootstrapButtonLinkConfigs->get('default.color');
    $button_default_outline = $this->varbaseBootstrapButtonLinkConfigs->get('default.outline');
    $button_default_size = $this->varbaseBootstrapButtonLinkConfigs->get('default.size');
    $button_default_target = $this->varbaseBootstrapButtonLinkConfigs->get('default.target');
    $button_default_block_level = $this->varbaseBootstrapButtonLinkConfigs->get('default.block_level');
    $button_default_disabled = $this->varbaseBootstrapButtonLinkConfigs->get('default.disabled');

    $override_color = (bool) $this->varbaseBootstrapButtonLinkConfigs->get('override_color');
    $override_outline = (bool) $this->varbaseBootstrapButtonLinkConfigs->get('override_outline');
    $override_size = (bool) $this->varbaseBootstrapButtonLinkConfigs->get('override_size');
    $override_target = (bool) $this->varbaseBootstrapButtonLinkConfigs->get('override_target');
    $override_block_level = (bool) $this->varbaseBootstrapButtonLinkConfigs->get('override_block_level');
    $override_disabled = (bool) $this->varbaseBootstrapButtonLinkConfigs->get('override_disabled');

    $element['options'] = [
      '#type' => 'details',
      '#title' => $this->t('Button Options'),
      '#access' => ($override_color || $override_outline || $override_size  || $override_target || $override_block_level || $override_disabled),
    ];

    $element['options']['color'] = [
      '#type' => 'select',
      '#title' => $this->t('Color'),
      '#default_value' => $items[$delta]->options['color'] ?? $button_default_color,
      '#description' => $this->t('Select the color of the button.'),
      '#options' => $button_colors,
      '#access' => $override_color,
    ];

    $element['options']['size'] = [
      '#type' => 'select',
      '#title' => $this->t('Size'),
      '#default_value' => $items[$delta]->options['size'] ?? $button_default_size,
      '#description' => $this->t('Select the size of the button.'),
      '#options' => $button_sizes,
      '#access' => $override_size,
    ];

    $element['options']['outline'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Outline'),
      '#default_value' => $items[$delta]->options['outline'] ?? $button_default_outline,
      '#description' => $this->t('Have an outline border for the button.'),
      '#access' => $override_outline,
    ];

    $element['options']['target'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Open in a new window'),
      '#default_value' => $items[$delta]->options['target'] ?? $button_default_target,
      '#description' => $this->t('Check to open the link in new window.'),
      '#access' => $override_target,
    ];

    $element['options']['block_level'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Block level'),
      '#default_value' => $items[$delta]->options['block_level'] ?? $button_default_block_level,
      '#description' => $this->t('Check to show a wide button to fit content width.'),
      '#access' => $override_block_level,
    ];

    $element['options']['disabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Disabled'),
      '#default_value' => $items[$delta]->options['disabled'] ?? $button_default_disabled,
      '#description' => $this->t('Check to show a disabled not functional button.'),
      '#access' => $override_disabled,
    ];

    return $element;
  }

}
