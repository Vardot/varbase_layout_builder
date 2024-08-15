<?php

namespace Drupal\varbase_layout_builder\Plugin\Block;

use Drupal\Component\Plugin\DerivativeInspectionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\layout_builder\Plugin\Block\InlineBlock;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Inline Block for Varbase Layout Builder.
 *
 * A custom class from the InlineBlockUX class from the lb_ux module.
 *
 */
class VarbaseLayoutBuilderInlineBlock extends InlineBlock {

  /**
   * The keyvalue factory.
   *
   * @var \Drupal\Core\KeyValueStore\KeyValueFactoryInterface
   */
  protected $keyValueFactory;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->keyValueFactory = $container->get('keyvalue');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    // Hide the label field when the label display is unchecked.
    $form['label']['#states']['invisible'][':input[name="settings[label_display]"]']['checked'] = FALSE;

    if ($this->isNew) {
      // Uncheck the label display checkbox for new blocks.
      $form['label_display']['#default_value'] = FALSE;
      // Prefill the label field for new blocks.
      $form['label']['#default_value'] = $this->t('@label @count', ['@label' => $this->label(), '@count' => $this->getNextInlineBlockNumber($form_state)]);
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    if ($form_state->has('varbase_layout_builder.inline_block_count')) {
      /** @var \Drupal\layout_builder\SectionStorageInterface $section_storage */
      $section_storage = $form_state->getFormObject()->getSectionStorage();
      $this->keyValueFactory->get('varbase_layout_builder.inline_block_count.' . $section_storage->getStorageType())->set($section_storage->getStorageId(), $form_state->get('lb_ux.inline_block_count'));
    }
  }

  /**
   * Gets the number of the next inline block.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state.
   *
   * @return int
   *   The next number.
   */
  protected function getNextInlineBlockNumber(FormStateInterface $form_state) {
    if ($form_state->has('varbase_layout_builder.inline_block_count')) {
      return $form_state->get('varbase_layout_builder.inline_block_count');
    }

    /** @var \Drupal\layout_builder\SectionStorageInterface $section_storage */
    $section_storage = $form_state->getFormObject()->getSectionStorage();
    $count = $this->keyValueFactory->get('varbase_layout_builder.inline_block_count.' . $section_storage->getStorageType())->get($section_storage->getStorageId(), 0);
    if (!$count) {
      foreach ($section_storage->getSections() as $section) {
        foreach ($section->getComponents() as $component) {
          $plugin = $component->getPlugin();
          if ($plugin instanceof DerivativeInspectionInterface && $plugin->getBaseId() === 'inline_block') {
            $count++;
          }
        }
      }
      // Exclude the component being added.
      if ($form_state->has('layout_builder__component')) {
        $count--;
      }
    }
    $form_state->set('varbase_layout_builder.inline_block_count', ++$count);
    return $count;
  }

}
