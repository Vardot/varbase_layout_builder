<?php

namespace Drupal\lb_ux\Controller;

use Drupal\Core\Ajax\AjaxHelperTrait;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormState;
use Drupal\Core\Layout\LayoutPluginManagerInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\layout_builder\Controller\LayoutRebuildTrait;
use Drupal\layout_builder\Form\ConfigureSectionForm;
use Drupal\layout_builder\LayoutTempstoreRepositoryInterface;
use Drupal\layout_builder\SectionStorageInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Attempts to add a new section, falls back to a form if necessary.
 */
class ConfigureSectionController implements ContainerInjectionInterface {

  use AjaxHelperTrait;
  use LayoutRebuildTrait;
  use StringTranslationTrait;

  /**
   * The layout tempstore repository.
   *
   * @var \Drupal\layout_builder\LayoutTempstoreRepositoryInterface
   */
  protected $layoutTempstoreRepository;

  /**
   * The layout plugin manager.
   *
   * @var \Drupal\Core\Layout\LayoutPluginManagerInterface
   */
  protected $layoutPluginManager;

  /**
   * The form builder.
   *
   * @var \Drupal\Core\Form\FormBuilderInterface
   */
  protected $formBuilder;

  /**
   * The messenger.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * ConfigureSectionController constructor.
   *
   * @param \Drupal\layout_builder\LayoutTempstoreRepositoryInterface $layout_tempstore_repository
   *   The layout tempstore repository.
   * @param \Drupal\Core\Layout\LayoutPluginManagerInterface $layout_plugin_manager
   *   The layout plugin manager.
   * @param \Drupal\Core\Form\FormBuilderInterface $form_builder
   *   The form builder.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger.
   */
  public function __construct(LayoutTempstoreRepositoryInterface $layout_tempstore_repository, LayoutPluginManagerInterface $layout_plugin_manager, FormBuilderInterface $form_builder, MessengerInterface $messenger) {
    $this->layoutTempstoreRepository = $layout_tempstore_repository;
    $this->formBuilder = $form_builder;
    $this->messenger = $messenger;
    $this->layoutPluginManager = $layout_plugin_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('layout_builder.tempstore_repository'),
      $container->get('plugin.manager.core.layout'),
      $container->get('form_builder'),
      $container->get('messenger')
    );
  }

  /**
   * Adds the new section.
   *
   * @param \Drupal\layout_builder\SectionStorageInterface $section_storage
   *   The section storage.
   * @param int $delta
   *   The delta of the section to splice.
   * @param string $plugin_id
   *   The plugin ID of the layout to add.
   *
   * @return \Symfony\Component\HttpFoundation\Response|array
   *   The controller response.
   */
  public function build(SectionStorageInterface $section_storage, $delta, $plugin_id) {
    // Store any existing messages.
    $old_messages_by_type = $this->messenger->all();

    // Attempt to submit the form with only default values.
    $form_state = new FormState();
    $form_state->addBuildInfo('args', func_get_args());
    $this->formBuilder->submitForm(ConfigureSectionForm::class, $form_state);

    // Clear all new messages and restore the original ones.
    $this->messenger->deleteAll();
    foreach ($old_messages_by_type as $type => $old_messages) {
      foreach ($old_messages as $old_message) {
        $this->messenger->addMessage($old_message, $type);
      }
    }

    // If there are errors, the form must be filled out manually.
    if (FormState::hasAnyErrors()) {
      // Clear any existing errors.
      $form_state->clearErrors();

      $form_state = new FormState();
      $form_state->addBuildInfo('args', func_get_args());
      return $this->formBuilder->buildForm(ConfigureSectionForm::class, $form_state);
    }

    if ($this->isAjax()) {
      return $this->rebuildAndClose($section_storage);
    }
    else {
      $url = $section_storage->getLayoutBuilderUrl();
      return new RedirectResponse($url->setAbsolute()->toString());
    }
  }

  /**
   * Returns the title for the configure section route.
   *
   * @param \Drupal\layout_builder\SectionStorageInterface $section_storage
   *   The section storage.
   * @param int $delta
   *   The delta of the section to splice.
   * @param string $plugin_id
   *   The plugin ID of the layout to add.
   *
   * @return string|\Drupal\Core\StringTranslation\TranslatableMarkup
   *   The route title.
   */
  public function title(SectionStorageInterface $section_storage, $delta, $plugin_id) {
    if (is_null($plugin_id)) {
      $layout_definition = $section_storage->getSection($delta)->getLayout()->getPluginDefinition();
    }
    else {
      $layout_definition = $this->layoutPluginManager->getDefinition($plugin_id);
    }
    return $this->t('Configure @label section', ['@label' => $layout_definition->getLabel()]);
  }

}
