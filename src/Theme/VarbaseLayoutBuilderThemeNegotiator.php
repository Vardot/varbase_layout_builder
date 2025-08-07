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
    if (isset($use_claro)
      && $use_claro == 1
      && varbase_layout_builder__is_layout_builder_route()
      && !varbase_layout_builder__is_dashboards_route()
      && !varbase_layout_builder__is_dashboard_route()
      && $route_match->getRouteName() !== 'layout_builder.navigation.view'
      && ($this->themeHandler->themeExists('gin') || $this->themeHandler->themeExists('claro'))) {

      return TRUE;
    }

    if ($this->requestStack->getCurrentRequest()->query->get('media_library_opener_id') === 'media_library.opener.editor') {
      return TRUE;
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

    $current_request = [];
    if ($this->requestStack->getCurrentRequest()->getMethod() === 'GET') {
      $current_request = $this->requestStack->getCurrentRequest()->query->all();
    }
    else {
      $current_request = $this->requestStack->getCurrentRequest()->request->all();
    }

    // Media Library Theme Negotiator.
    if (isset($current_request['_triggering_element_name'])
      && str_contains($current_request['_triggering_element_name'], 'media-library')) {

      return $this->configFactory->get('system.theme')->get('admin');
    }

    if (isset($current_request['dialogOptions'])
      && isset($current_request['dialogOptions']['dialogClass'])
      && $current_request['dialogOptions']['dialogClass'] == 'media-library-widget-modal') {

      if (isset($current_request['ajax_form'])
        && $current_request['ajax_form'] == '1'
        && isset($current_request['_wrapper_format'])
        && ($current_request['_wrapper_format'] == 'drupal_dialog'
        || $current_request['_wrapper_format'] == 'drupal_dialog.off_canvas'
        || $current_request['_wrapper_format'] == 'drupal_ajax')) {

        return $this->configFactory->get('system.theme')->get('admin');
      }
    }

    if (!empty($current_request['media_library_opener_id'])) {
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

      if (isset($current_request['_wrapper_format'])
        && $current_request['_wrapper_format'] == 'drupal_dialog.off_canvas') {
        return $this->configFactory->get('system.theme')->get('admin');
      }
      else {
        return $this->configFactory->get('system.theme')->get('default');
      }
    }

    if ($this->requestStack->getCurrentRequest()->query->get('media_library_opener_id') === 'media_library.opener.editor') {
      return $this->configFactory->get('system.theme')->get('admin');
    }

    // AJAX trigger for any block form field.
    if (isset($current_request['_triggering_element_name'])
      && str_contains($current_request['_triggering_element_name'], 'block_form-field')) {

      return $this->configFactory->get('system.theme')->get('admin');
    }

    // AJAX trigger for section configuration forms (including background settings).
    if (isset($current_request['_triggering_element_name'])) {
      $triggering_element = $current_request['_triggering_element_name'];

      // Handle section configuration AJAX requests
      if (str_contains($triggering_element, 'configure_section')
          || str_contains($triggering_element, 'layout_settings')
          || str_contains($triggering_element, 'section_settings')
          || str_contains($triggering_element, 'background')
          || str_contains($triggering_element, 'bootstrap_styles')
          || str_contains($triggering_element, 'layout_builder_styles')) {

        return $this->configFactory->get('system.theme')->get('admin');
      }

      // Handle block creation and configuration AJAX requests
      if (str_contains($triggering_element, 'add_block')
          || str_contains($triggering_element, 'configure_block')
          || str_contains($triggering_element, 'update_block')
          || str_contains($triggering_element, 'block_form')
          || str_contains($triggering_element, 'inline_block')
          || str_contains($triggering_element, 'custom_block')) {

        return $this->configFactory->get('system.theme')->get('admin');
      }

      // Handle media-related AJAX requests in layout builder context
      if (str_contains($triggering_element, 'media')
          || str_contains($triggering_element, 'field_media')
          || str_contains($triggering_element, 'entity_browser')
          || str_contains($triggering_element, 'upload')) {

        // Check if this is in layout builder context
        $route_name = $this->requestStack->getCurrentRequest()->attributes->get('_route');
        $path = $this->requestStack->getCurrentRequest()->getPathInfo();

        if (($route_name && (str_contains($route_name, 'layout_builder') || str_contains($route_name, 'block')))
            || ($path && (str_contains($path, 'layout_builder') || str_contains($path, 'layout')))) {

          return $this->configFactory->get('system.theme')->get('admin');
        }
      }

      // Handle general layout builder form AJAX requests
      if (str_contains($triggering_element, 'layout_builder')
          || str_contains($triggering_element, 'configure-section')
          || str_contains($triggering_element, 'blb_')) {

        return $this->configFactory->get('system.theme')->get('admin');
      }

      // Handle form element AJAX requests that might be in layout builder context
      if (str_contains($triggering_element, 'field_')
          || str_contains($triggering_element, 'settings')
          || str_contains($triggering_element, 'ajax')) {

        // Check if this is in layout builder context via route or path
        $route_name = $this->requestStack->getCurrentRequest()->attributes->get('_route');
        $path = $this->requestStack->getCurrentRequest()->getPathInfo();

        if (($route_name && (str_contains($route_name, 'layout_builder')
                          || str_contains($route_name, 'add_block')
                          || str_contains($route_name, 'update_block')))
            || ($path && (str_contains($path, 'layout_builder')
                       || str_contains($path, '/layout/')))) {

          return $this->configFactory->get('system.theme')->get('admin');
        }
      }
    }

    // Handle layout builder form IDs and routes
    $route_name = $this->requestStack->getCurrentRequest()->attributes->get('_route');
    if ($route_name && (str_contains($route_name, 'layout_builder.configure_section')
                     || str_contains($route_name, 'layout_builder.add_block')
                     || str_contains($route_name, 'layout_builder.update_block')
                     || str_contains($route_name, 'layout_builder.choose_block')
                     || str_contains($route_name, 'layout_builder.choose_section'))) {
      return $this->configFactory->get('system.theme')->get('admin');
    }

    // Check for form_id indicating layout builder operations
    if (isset($current_request['form_id'])) {
      $form_id = $current_request['form_id'];
      if (str_contains($form_id, 'layout_builder_configure_section')
          || str_contains($form_id, 'configure_section')
          || str_contains($form_id, 'layout_builder_add_block')
          || str_contains($form_id, 'layout_builder_update_block')
          || str_contains($form_id, 'layout_builder_configure_block')
          || str_contains($form_id, 'bootstrap_styles')
          || str_contains($form_id, 'inline_block')
          || str_contains($form_id, 'custom_block')) {

        return $this->configFactory->get('system.theme')->get('admin');
      }
    }

    // Additional check for dialog options that might contain layout builder context
    if (isset($current_request['dialogOptions'])) {
      $dialog_options = $current_request['dialogOptions'];
      if (is_array($dialog_options)) {
        $dialog_str = json_encode($dialog_options);
        if (str_contains($dialog_str, 'layout_builder')
            || str_contains($dialog_str, 'layout-builder')
            || str_contains($dialog_str, 'add_block')
            || str_contains($dialog_str, 'configure_block')) {

          return $this->configFactory->get('system.theme')->get('admin');
        }
      }
    }

    return $this->configFactory->get('system.theme')->get('default');

  }

}
