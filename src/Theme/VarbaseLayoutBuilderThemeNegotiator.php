<?php
/**
 * @file
 * Contains \Drupal\varbase_layout_builder\Theme\ThemeNegotiator
 */
namespace Drupal\varbase_layout_builder\Theme;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Theme\AjaxBasePageNegotiator;
use Drupal\Core\Access\CsrfTokenGenerator;
use Symfony\Component\HttpFoundation\RequestStack;

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
   * Constructs a new AjaxBasePageNegotiator.
   *
   * @param \Drupal\Core\Access\CsrfTokenGenerator $token_generator
   *   The CSRF token generator.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack used to retrieve the current request.
   */
  public function __construct(CsrfTokenGenerator $token_generator, ConfigFactoryInterface $config_factory, RequestStack $request_stack) {
    $this->csrfGenerator = $token_generator;
    $this->configFactory = $config_factory;
    $this->requestStack = $request_stack;
  }
  /**
   * Whether this theme negotiator should be used to set the theme.
   * @param RouteMatchInterface $route_match
   * @return bool
   */
  public function applies(RouteMatchInterface $route_match) {
    $dialog_options = $this->requestStack->getCurrentRequest()->request->get('dialogOptions')['target'];
    $offcanvas = $this->requestStack->getCurrentRequest()->query->get('_wrapper_format');
    if ($dialog_options != NULL) {
      return $this->getTheme($dialog_options) ? true : false;
    }
    else {
      return $this->getTheme($offcanvas) ? true : false;
    }
  }
  
  /**
   * Determine the active theme for the request
   * @param RouteMatchInterface $route_match
   * @return null|string
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    $dialog_options = $this->requestStack->getCurrentRequest()->request->get('dialogOptions')['target'];
    $offcanvas = $this->requestStack->getCurrentRequest()->query->get('_wrapper_format');
    if ($dialog_options != NULL) {
      return $this->getTheme($dialog_options) ?: null;
    }
    else {
      return $this->getTheme($offcanvas) ?: null;
    }

  }

  /**
   * Function that does all of the work in selecting a theme
   * @param RouteMatchInterface $route_match
   * @return bool|string
   */
  private function getTheme($dialog_or_offcanvas) {
    $config = \Drupal::config('varbase_layout_builder.settings');

    if ($dialog_or_offcanvas == "layout-builder-modal") {
      if (isset($config)) {
        if ($config->get('use_claro') == 1) {
          return "claro";
        }
      }
    }
    elseif ($dialog_or_offcanvas == 'drupal_dialog.off_canvas') {
      return \Drupal::config('system.theme')->get('default');
    }
    return NULL;
  }
}
