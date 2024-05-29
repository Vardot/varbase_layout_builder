<?php

namespace Drupal\varbase_layout_builder\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\layout_builder\SectionStorageInterface;
use Drupal\layout_builder\Form\LayoutRebuildConfirmFormBase;

/**
 * Provides a form to confirm the Visibility of a section.
 *
 * @internal
 *   Form classes are internal.
 */
class VisibilitySectionForm extends LayoutRebuildConfirmFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'layout_builder_visibility_section';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    $configuration = $this->sectionStorage->getSection($this->delta)->getLayoutSettings();
    // Layouts may choose to use a class that might not have a label
    // configuration.
    if (!empty($configuration['label'])) {
      return $this->t('Are you sure you want to change the Visibility of the @section?', ['@section' => $configuration['label']]);
    }
    return $this->t('Are you sure you want to change the Visibility of section @section?', ['@section' => $this->delta + 1]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Change');
  }

  /**
   * {@inheritdoc}
   */
  public function getDescription() {
    $section_settings = $this->sectionStorage->getSection($this->delta)->getLayoutSettings();
    $visibility = isset($section_settings["visibility"]) ? $section_settings["visibility"] : FALSE;
    $status = $visibility ? 'invisible' : 'visible';

    return $this->t('Current status is ' . $status . '.<br>This action will change the visibility status.');
  }

  /**
   * {@inheritdoc}
   */
  protected function handleSectionStorage(SectionStorageInterface $section_storage, FormStateInterface $form_state) {
    $section_settings = $this->sectionStorage->getSection($this->delta)->getLayoutSettings();
    $visibility = isset($section_settings["visibility"]) ? $section_settings["visibility"] : FALSE;
    if ($form_state->getValue('confirm') == 1) {
      $section_settings["visibility"] = !$visibility;
      $this->sectionStorage->getSection($this->delta)->setLayoutSettings($section_settings);
    }
  }

}
