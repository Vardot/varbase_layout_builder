<?php

namespace Drupal\varbase_layout_builder\Theme;

use Drupal\section_library\SectionLibraryRender;

/**
 * Add Pre-render trusted callback for Section library.
 */
class VarbaseSectionLibraryRender extends SectionLibraryRender {

  /**
   * Pre-render callback for layout builder.
   */
  public static function preRender($elements) {

    if (\Drupal::service('theme.manager')->getActiveTheme()->getName() === \Drupal::config('system.theme')->get('admin')) {
      return $elements;
    }
    else {
      parent::preRender($elements);
    }

  }

}
