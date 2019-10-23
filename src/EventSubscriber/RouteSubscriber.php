<?php

namespace Drupal\varbase_layout_builder\EventSubscriber;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class RouteSubscriber.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    $configureSectionRoute = $collection->get('layout_builder.configure_section');
    if ($configureSectionRoute) {
      $configureSectionRoute->setDefault('_form', '\Drupal\varbase_layout_builder\Form\ConfigureSectionForm');
    }
  }

}
