<?php

namespace Drupal\lb_ux\Routing;

use Drupal\Core\Routing\RouteBuildEvent;
use Drupal\Core\Routing\RoutingEvents;
use Drupal\lb_ux\Controller\ConfigureSectionController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Alters the Layout Builder UI routes.
 */
class LBUXRouteAlter implements EventSubscriberInterface {

  /**
   * Alters existing Layout Builder routes.
   *
   * @param \Drupal\Core\Routing\RouteBuildEvent $event
   *   The route build event.
   */
  public function onAlterRoutes(RouteBuildEvent $event) {
    $collection = $event->getRouteCollection();
    $route = $collection->get('layout_builder.configure_section');

    // Change the title callback for configuring sections.
    $route->setDefault('_title_callback', ConfigureSectionController::class . '::title');

    // Copy the existing route to always display a section configuration form
    // and give it a new path and route name. This will continue to be used when
    // updating existing sections.
    $new_route = clone $route;
    $new_route->setPath('/layout_builder/configure-form/section/{section_storage_type}/{section_storage}/{delta}/{plugin_id}');
    $collection->add('layout_builder.configure_section_form', $new_route);

    // Change the existing route to use the auto-submit controller. This will be
    // used when adding a new section.
    $route->setDefault('_controller', ConfigureSectionController::class . '::build');
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    $events[RoutingEvents::ALTER] = 'onAlterRoutes';
    return $events;
  }

}
