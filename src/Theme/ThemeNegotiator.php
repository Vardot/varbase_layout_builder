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


class ThemeNegotiator extends AjaxBasePageNegotiator {


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
    return $this->getTheme($route_match) ? true : false;
  }
  
  /**
   * Determine the active theme for the request
   * @param RouteMatchInterface $route_match
   * @return null|string
   */
  public function determineActiveTheme(RouteMatchInterface $route_match) {
    return $this->getTheme($route_match) ?: null;
  }

  /**
   * Function that does all of the work in selecting a theme
   * @param RouteMatchInterface $route_match
   * @return bool|string
   */
  private function getTheme(RouteMatchInterface $route_match) {
    $config = \Drupal::config('varbase_layout_builder.settings');

    $applied_routes = ['layout_builder.update_block', 'layout_builder.add_block'];
    if (in_array($route_match->getRouteName(), $applied_routes)) {
      if (isset($config)) {
        if ($config->get('use_claro') == 1) {
          return "claro";
        }
      }
    }
    elseif ($route_match->getRouteName() == 'layout_builder.configure_section_form') {
      return \Drupal::config('system.theme')->get('default');
    }
    return NULL;
  }
}
