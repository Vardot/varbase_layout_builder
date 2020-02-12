<?php

namespace Drupal\varbase_layout_builder\Routing;

use Drupal\Core\Routing\RouteBuildEvent;
use Drupal\Core\Routing\RoutingEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Alters the Layout Builder UI routes.
 */
class VLBRouteAlter implements EventSubscriberInterface {

  /**
   * Alters existing Layout Builder routes.
   *
   * @param \Drupal\Core\Routing\RouteBuildEvent $event
   *   The route build event.
   */
  public function onAlterRoutes(RouteBuildEvent $event) {
    $collection = $event->getRouteCollection();

    $layout_builder_configure_section_route = $collection->get('layout_builder.configure_section');
    if ($layout_builder_configure_section_route) {
      $layout_builder_configure_section_route->setDefault('_form', '\Drupal\varbase_layout_builder\Form\VarbaseLayoutBuilderConfigureSectionForm');
    }

    $layout_builder_configure_section_form_route = $collection->get('layout_builder.configure_section_form');
    if ($layout_builder_configure_section_form_route) {
      $layout_builder_configure_section_form_route->setDefault('_form', '\Drupal\varbase_layout_builder\Form\VarbaseLayoutBuilderConfigureSectionForm');
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[RoutingEvents::ALTER] = 'onAlterRoutes';
    return $events;
  }

}
