<?php

declare(strict_types=1);

namespace Drupal\varbase_layout_builder\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\ckeditor5\LanguageMapper;

/**
 * Sets the CKEditor 5 content language in Layout Builder.
 *
 * Layout Builder block forms are admin routes, so CKEditor 5 took its content
 * language and direction from the admin language. Use the page language.
 */
final class EditorLanguageHooks {

  public function __construct(
    private readonly LanguageManagerInterface $languageManager,
    private readonly RouteMatchInterface $routeMatch,
    private readonly ?LanguageMapper $languageMapper = NULL,
  ) {}

  /**
   * Implements hook_editor_js_settings_alter().
   */
  #[Hook('editor_js_settings_alter')]
  public function editorJsSettingsAlter(array &$settings): void {
    $route_name = (string) $this->routeMatch->getRouteName();
    if ($this->languageMapper === NULL
      || empty($settings['editor']['formats'])
      || !str_starts_with($route_name, 'layout_builder.')) {
      return;
    }

    $langcode = $this->languageManager->getCurrentLanguage(LanguageInterface::TYPE_URL)->getId();
    $content_language = $this->languageMapper->getMapping($langcode);

    foreach ($settings['editor']['formats'] as &$format) {
      if (($format['editor'] ?? NULL) === 'ckeditor5') {
        $format['editorSettings']['language']['content'] = $content_language;
      }
    }
    unset($format);
  }

}
