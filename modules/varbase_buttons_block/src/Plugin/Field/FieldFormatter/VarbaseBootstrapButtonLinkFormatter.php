<?php

namespace Drupal\varbase_buttons_block\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Path\PathValidatorInterface;
use Drupal\Core\Utility\Token;
use Drupal\Core\Config\Config;
use Drupal\link\Plugin\Field\FieldFormatter\LinkFormatter;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'Varbase Bootstrap Button Link' formatter.
 *
 * @FieldFormatter(
 *   id = "varbase_bootstrap_button_link_formatter",
 *   label = @Translation("Varbase Bootstrap Button Link"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class VarbaseBootstrapButtonLinkFormatter extends LinkFormatter {

  /**
   * The token replacement instance.
   *
   * @var \Drupal\Core\Utility\Token
   */
  protected $token;

  /**
   * Contains the varbase_buttons_block.settings configuration object.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $varbaseBootstrapButtonLinkConfigs;

  /**
   * Constructs a new VarbaseBootstrapButtonLinkFormatter.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Third party settings.
   * @param \Drupal\Core\Path\PathValidatorInterface $path_validator
   *   The path validator service.
   * @param \Drupal\Core\Utility\Token $token
   *   The token replacement instance.
   * @param \Drupal\Core\Config\Config $varbase_buttons_block_configs
   *   The varbase_buttons_block.settings configuration factory object.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, PathValidatorInterface $path_validator, Token $token, Config $varbase_buttons_block_configs) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings, $path_validator);
    $this->token = $token;
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
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('path.validator'),
      $container->get('token'),
      $container->get('config.factory')->get('varbase_buttons_block.settings')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'color' => '_none',
      'size' => '_none',
      'outline' => 0,
      'target' => 0,
      'block_level' => 0,
      'disabled' => 0,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);

    $_none = ['_none' => ' ' . $this->t('-  None  -') . ' '];

    $button_colors_options = $_none + $this->varbaseBootstrapButtonLinkConfigs->get('colors');
    $button_sizes_options = $_none + $this->varbaseBootstrapButtonLinkConfigs->get('sizes');

    // Disable Link Formatter settings.
    $elements['trim_length']['#access'] = FALSE;
    $elements['url_only']['#access'] = FALSE;
    $elements['url_plain']['#access'] = FALSE;
    $elements['rel']['#access'] = FALSE;

    $elements['color'] = [
      '#type' => 'select',
      '#title' => $this->t('Color'),
      '#default_value' => $this->getSetting('color'),
      '#options' => $button_colors_options,
      '#required' => TRUE,
      '#weight' => -60,
    ];

    $elements['size'] = [
      '#type' => 'select',
      '#title' => $this->t('Size'),
      '#default_value' => $this->getSetting('size'),
      '#options' => $button_sizes_options,
      '#required' => TRUE,
      '#weight' => -50,
    ];

    $elements['outline'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Outline'),
      '#default_value' => $this->getSetting('outline'),
      '#description' => $this->t('Have an outline border for the button.'),
      '#weight' => -40,
    ];

    $elements['target'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Open in a new window'),
      '#default_value' => $this->getSetting('target'),
      '#description' => $this->t('Check to open the link in new window.'),
      '#weight' => -30,
    ];

    $elements['block_level'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Block level'),
      '#default_value' => $this->getSetting('block_level'),
      '#description' => $this->t('Check to show a wide button to fit content width.'),
      '#weight' => -20,
    ];

    $elements['disabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Disabled'),
      '#default_value' => $this->getSetting('disabled'),
      '#description' => $this->t('Check to show a disabled not functional button.'),
      '#weight' => -10,
    ];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = $this->t('Color: @text', ['@text' => (($this->getSetting('color') !== '_none') ? $this->getSetting('color') : $this->t('None'))]);
    $summary[] = $this->t('Outline: @text', ['@text' => (($this->getSetting('outline')) ? $this->t('Yes') : $this->t('No'))]);
    $summary[] = $this->t('Size: @text', ['@text' => (($this->getSetting('size') !== '_none') ? $this->getSetting('size') : $this->t('None'))]);
    $summary[] = $this->t('Target: @text', ['@text' => (($this->getSetting('target')) ? $this->t('Open in a new window') : $this->t('Open in the same window'))]);
    $summary[] = $this->t('Block level: @text', ['@text' => (($this->getSetting('block_level')) ? $this->t('Yes') : $this->t('No'))]);
    $summary[] = $this->t('Disabled: @text', ['@text' => (($this->getSetting('disabled')) ? $this->t('Yes') : $this->t('No'))]);

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    $settings = $this->getSettings();
    $entity = $items->getEntity();

    $override_color = (bool) $this->varbaseBootstrapButtonLinkConfigs->get('override_color');
    $override_outline = (bool) $this->varbaseBootstrapButtonLinkConfigs->get('override_outline');
    $override_size = (bool) $this->varbaseBootstrapButtonLinkConfigs->get('override_size');
    $override_target = (bool) $this->varbaseBootstrapButtonLinkConfigs->get('override_target');

    foreach ($items as $delta => $item) {
      $url = $this->buildUrl($item);

      // Load links options.
      $options = $url->getOptions();

      // Load Link Title.
      $link_title = $this->token->replace($item->title, [$entity->getEntityTypeId() => $entity], ['clear' => TRUE]);

      $attributes = [];
      $btn_class = [];

      // Add button color.
      $button_link_color = (!$override_color || empty($options['color']) || $options['color'] == '_none') ? $settings['color'] : $options['color'];

      if (!empty($button_link_color)) {

        // Add button outline.
        $button_link_outline = (empty($options['outline']) || $options['outline'] == 0) ? $settings['outline'] : $options['outline'];

        if ($button_link_outline == 1) {
          $button_link_color = substr_replace($button_link_color, 'btn-outline-', 0, strlen('btn-'));
        }

        $btn_class += ['btn', $button_link_color];
      }

      // Add the Bootstrap Button Block level class if the button was sat to be block level.
      $button_block_level = (empty($options['block_level']) || $options['block_level'] == 0) ? $settings['block_level'] : $options['block_level'];
      if ($button_block_level == 1) {
        $btn_class[] = 'btn-block';
      }

      // Add the bootstrap .disabled class if the button was sat to be disabled.
      $button_disabled = (empty($options['disabled']) || $options['disabled'] == 0) ? $settings['disabled'] : $options['disabled'];
      if ($button_disabled == 1) {
        $btn_class[] = 'disabled';
      }

      // Add button size.
      $button_link_size = (!$override_size || empty($options['size']) || $options['size'] == '_none') ? $settings['size'] : $options['size'];
      $btn_class[] = $button_link_size;

      // Add button target.
      $button_target = (empty($options['target']) || $options['target'] == 0) ? $settings['target'] : $options['target'];
      if ($button_target == 1) {
        $attributes['target'] = '_blank';
      }

      // Add collected classes to attributes.
      if (!empty($btn_class)) {
        $attributes['class'] = implode(' ', $btn_class);
      }

      // Create output of the button link.
      $element[$delta] = [
        '#type' => 'link',
        '#title' => $link_title,
        '#url' => $url,
        '#options' => ['attributes' => $attributes],
      ];
    }

    // Adding caching tag for cleaning.
    $element['#cache']['tags'][] = 'varbase_buttons_block__field_formatter';

    return $element;
  }

}
