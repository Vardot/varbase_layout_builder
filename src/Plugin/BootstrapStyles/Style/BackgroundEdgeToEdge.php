<?php

namespace Drupal\varbase_layout_builder\Plugin\BootstrapStyles\Style;

use Drupal\bootstrap_styles\Style\StylePluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\layout_builder\Form\ConfigureSectionForm;

/**
 * Background Edge To Edge.
 *
 * @package Drupal\varbase_layout_builder\Plugin\Style
 *
 * @Style(
 *   id = "background_edgetoedge",
 *   title = @Translation("Edge to Edge Background"),
 *   group_id = "background",
 *   weight = 10
 * )
 */
class BackgroundEdgeToEdge extends StylePluginBase {

  /**
   * {@inheritdoc}
   */
  public function buildStyleFormElements(array &$form, FormStateInterface $form_state, $storage) {

    $form_object = $form_state->getFormObject();
    if ($form_object instanceof ConfigureSectionForm) {
      $form['background_edgetoedge'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Edge to Edge Background'),
        '#default_value' => ((isset($storage['background_edgetoedge'])) ? $storage['background_edgetoedge'] : TRUE),
        '#validated' => TRUE,
        '#attributes' => [
          'class' => ['field-background-edge-to-edge'],
        ],
      ];
    }
    else {
      $form['background_edgetoedge'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Edge to Edge Background'),
        '#validated' => TRUE,
        '#attributes' => [
          'class' => ['field-background-edge-to-edge'],
        ],
        '#access' => FALSE,
        '#default_value' => FALSE,
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitStyleFormElements(array $group_elements) {
    return [
      'background_edgetoedge' => $group_elements['background_edgetoedge'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function build(array $build, array $storage, $theme_wrapper = NULL) {

    if (isset($storage['background_edgetoedge'])
      && $storage['background_edgetoedge'] == 1
      && isset($storage['background']['background_type'])) {

      if ($storage['background']['background_type'] == 'color'
        && !empty($storage['background_color']['class'])
        && $storage['background_color']['class'] !== '_none') {

        $build = $this->addClassesToBuild($build, ['bg-edge2edge'], $theme_wrapper);
      }
      elseif ($storage['background']['background_type'] == 'image'
        && !empty($storage['background_media']['image']['media_id'])) {

        $build = $this->addClassesToBuild($build, ['bg-edge2edge'], $theme_wrapper);
      }
      elseif ($storage['background']['background_type'] == 'video'
        && !empty($storage['background_media']['video']['media_id'])) {

        $build['#theme_wrappers']['bs_video_background']['#attributes']['class'][] = 'bg-edge2edge';
      }
    }
    return $build;
  }

}
