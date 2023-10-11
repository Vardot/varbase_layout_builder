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
      if (isset($route_name) && strpos($route_name, 'layout_builder') !== FALSE) {
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
    $use_claro = $this->configFactory->get('varbase_layout_builder.settings')->get('use_claro');

    if (isset($use_claro) && $use_claro == 1) {

      $route_name = $route_match->getRouteName();
      if (isset($route_name) && strpos($route_name, 'layout_builder') !== FALSE) {

        if ($this->themeHandler->themeExists('gin') || $this->themeHandler->themeExists('claro')) {
          $current_request = $this->requestStack->getCurrentRequest()->request->all('dialogOptions');
          if (isset($current_request['dialogOptions']) && isset($current_request['dialogOptions']['target'])) {
            $dialog_options = $current_request['dialogOptions']['target'];
          }
          if (isset($dialog_options)) {
            return $this->configFactory->get('system.theme')->get('admin');
          }
          else {
            $request_query_wrapper_format = $this->requestStack->getCurrentRequest()->query->get('_wrapper_format');
            if (isset($request_query_wrapper_format)) {
              if ($request_query_wrapper_format == 'drupal_dialog.off_canvas') {
                return $this->configFactory->get('system.theme')->get('default');
              }
              else {
                return $this->configFactory->get('system.theme')->get('admin');
              }
            }
          }
        }
        else {
          return $this->configFactory->get('system.theme')->get('admin');
        }
      }
    }

    return NULL;
  }

}
