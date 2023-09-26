<?php

namespace Drupal\varbase_layout_builder\Theme;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Theme\AjaxBasePageNegotiator;
use Drupal\Core\Access\CsrfTokenGenerator;
use Symfony\Component\HttpFoundation\RequestStack;
use Drupal\Core\Extension\ThemeHandlerInterface;

/**
 * Varbase Layout Builder Theme Negotiator.
 */
class VarbaseLayoutBuilderThemeNegotiator extends AjaxBasePageNegotiator {

  /**
   * The CSRF token generator.
   *
   * @var \Drupal\Core\Access\CsrfTokenGenerator
   */
  protected $csrfGenerator;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The request stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * The theme handler.
   *
   * @var \Drupal\Core\Extension\ThemeHandlerInterface
   */
  protected $themeHandler;

  /**
   * Constructs a new AjaxBasePageNegotiator.
   *
   * @param \Drupal\Core\Access\CsrfTokenGenerator $token_generator
   *   The CSRF token generator.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack used to retrieve the current request.
   * @param \Drupal\Core\Extension\ThemeHandlerInterface $theme_handler
   *   The theme handler.
   */
  public function __construct(CsrfTokenGenerator $token_generator, ConfigFactoryInterface $config_factory, RequestStack $request_stack, ThemeHandlerInterface $theme_handler) {
    $this->csrfGenerator = $token_generator;
    $this->configFactory = $config_factory;
    $this->requestStack = $request_stack;
    $this->themeHandler = $theme_handler;
  }

  /**
   * Whether this theme negotiator should be used to set the theme.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   *
   * @return bool
   *   To proceed with changing the theme.
   */
  public function applies(RouteMatchInterface $route_match) {
    $use_claro = $this->configFactory->get('varbase_layout_builder.settings')->get('use_claro');
    if (isset($use_claro) && $use_claro == 1) {

      $route_name = $route_match->getRouteName();
      if (isset($route_name) && (str_contains($route_name, 'layout_builder'))) {
        if ($this->themeHandler->themeExists('gin') || $this->themeHandler->themeExists('claro')) {
          return TRUE;
        }
      }
    }

    return FALSE;
  }

  /**
   * Determine the active theme for the request.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   *
   * @return null|string
   *   The selected active theme.
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    $current_request = $this->requestStack->getCurrentRequest()->request->all();

    // Media Library Theme Negotiator
    $dialog_has_target_media_library = FALSE;
    if (isset($current_request['_triggering_element_name'])
      && str_contains($current_request['_triggering_element_name'], 'media-library')) {

      $dialog_has_target_media_library = TRUE;
    }

    if (isset($current_request['dialogOptions'])
      && isset($current_request['dialogOptions']['dialogClass'])
      && $current_request['dialogOptions']['dialogClass'] == 'media-library-widget-modal') {

      $dialog_has_target_media_library = TRUE;
    }

    if ($dialog_has_target_media_library) {
      $request_query_wrapper_format = $this->requestStack->getCurrentRequest()->query->get('_wrapper_format');
      $request_query_ajax_form = $this->requestStack->getCurrentRequest()->query->get('ajax_form');
      if ($request_query_ajax_form == '1'
        && ($request_query_wrapper_format == 'drupal_dialog'
        || $request_query_wrapper_format == 'drupal_dialog.off_canvas'
        || $request_query_wrapper_format == 'drupal_ajax')) {
        return $this->configFactory->get('system.theme')->get('admin');
      }
    }

    $request_query_media_library_opener = $this->requestStack->getCurrentRequest()->query->get('media_library_opener_id');
    if (!empty($request_query_media_library_opener)) {
      return $this->configFactory->get('system.theme')->get('admin');
    }

    $dialog_has_target_layout_builder_modal = FALSE;
    if (isset($current_request['dialogOptions'])
      && isset($current_request['dialogOptions']['target'])
      && $current_request['dialogOptions']['target'] == 'layout-builder-modal') {

      $dialog_has_target_layout_builder_modal = TRUE;
    }

    $parent_theme_is_front_end_theme = FALSE;
    if (isset($current_request['ajax_page_state'])
    && isset($current_request['ajax_page_state']['theme'])
    && $current_request['ajax_page_state']['theme'] == $this->configFactory->get('system.theme')->get('default')) {
      $parent_theme_is_front_end_theme = TRUE;
    }

    if ($dialog_has_target_layout_builder_modal && $parent_theme_is_front_end_theme) {
      $request_query_wrapper_format = $this->requestStack->getCurrentRequest()->query->get('_wrapper_format');
      if (isset($request_query_wrapper_format)) {
        if ($request_query_wrapper_format == 'drupal_dialog.off_canvas') {
          return $this->configFactory->get('system.theme')->get('admin');
        }
      }
    }

    if ($parent_theme_is_front_end_theme) {
      return $this->configFactory->get('system.theme')->get('default');
    }
  }

}
