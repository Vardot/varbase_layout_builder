<?php

namespace Drupal\varbase_layout_builder;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Plugin\Discovery\ContainerDerivativeDiscoveryDecorator;
use Drupal\Core\Plugin\Discovery\YamlDiscovery;

/**
 * Provides the default layout_builder.style_options manager.
 */
class LayoutBuilderStyleOptionsPluginManager extends DefaultPluginManager implements LayoutBuilderStyleOptionsPluginManagerInterface {

  /**
   * Provides default values for all layout_builder.style_options plugins.
   *
   * @var array
   */
  protected $defaults = [
    'id' => '',
    'label' => '',
  ];

  /**
   * Constructs a new EntityBrowserEnhancedPluginManager object.
   *
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   */
  public function __construct(ModuleHandlerInterface $module_handler, CacheBackendInterface $cache_backend) {
    // Add more services as required.
    $this->moduleHandler = $module_handler;
    $this->setCacheBackend($cache_backend, 'layout_builder.style_options', ['layout_builder.style_options']);
  }

  /**
   * {@inheritdoc}
   */
  protected function getDiscovery() {
    if (!isset($this->discovery)) {
      $this->discovery = new YamlDiscovery('layout_builder.style.options', $this->moduleHandler->getModuleDirectories());
      $this->discovery->addTranslatableProperty('label', 'label_context');
      $this->discovery = new ContainerDerivativeDiscoveryDecorator($this->discovery);
    }
    return $this->discovery;
  }

  /**
   * {@inheritdoc}
   */
  public function processDefinition(&$definition, $plugin_id) {
    parent::processDefinition($definition, $plugin_id);

    if (empty($definition['id'])) {
      throw new PluginException(sprintf('Layout Builder style option ID property (%s) definition "is" is required.', $plugin_id));
    }

    if (empty($definition['label'])) {
      throw new PluginException(sprintf('Layout Builder style option Lable property (%s) definition "is" is required.', $plugin_id));
    }

  }

  /**
   * Get Layout Builder style option ID.
   *
   * @return string
   *   The ID of the Layout Builder style option.
   */
  public function getId() {
    return $this->pluginDefinition['id'];
  }

  /**
   * Get Layout Builder style option Label.
   *
   * @return string
   *   The Label of the Layout Builder style option.
   */
  public function getLabel() {
    return $this->pluginDefinition['label'];
  }

}
