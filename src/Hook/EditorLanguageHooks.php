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
 *
 * Drupal 11.1 and later call this through the Hook attribute. Drupal 10 calls
 * it from the procedural varbase_layout_builder_editor_js_settings_alter().
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
    if (empty($settings['editor']['formats'])
      || !str_starts_with($route_name, 'layout_builder.')) {
      return;
    }

    $content_language = $this->getContentLanguage();
    if ($content_language === NULL) {
      return;
    }

    foreach ($settings['editor']['formats'] as &$format) {
      if (($format['editor'] ?? NULL) === 'ckeditor5') {
        $format['editorSettings']['language']['content'] = $content_language;
      }
    }
    unset($format);
  }

  /**
   * Maps the page language to a CKEditor 5 language code.
   *
   * @return string|null
   *   The CKEditor 5 language code, or NULL when CKEditor 5 is not available.
   */
  private function getContentLanguage(): ?string {
    $langcode = $this->languageManager->getCurrentLanguage(LanguageInterface::TYPE_URL)->getId();
    // Drupal 11 maps language codes with a service, Drupal 10 with a function.
    if ($this->languageMapper !== NULL) {
      return $this->languageMapper->getMapping($langcode);
    }
    if (function_exists('_ckeditor5_get_langcode_mapping')) {
      // Only reached on Drupal 10, where this function is not deprecated.
      // @phpstan-ignore function.deprecated
      return _ckeditor5_get_langcode_mapping($langcode);
    }
    return NULL;
  }

}
