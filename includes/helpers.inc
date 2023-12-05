<?php

/**
 * @file
 * Contains List of all varbase_layout_builder helpers.
 *
 * Add custom needed helper functions.
 */

/**
 * Returns true if the current route is a layout builder route.
 *
 * @return bool
 *   Returns true for layout builder routes.
 */
function varbase_layout_builder__is_layout_builder_route() {
  $layout_builder_route = drupal_static(__FUNCTION__);
  if ($layout_builder_route !== NULL) {
    return $layout_builder_route;
  }

  $route_name = \Drupal::routeMatch()->getRouteName();
  $layout_builder_route = FALSE;

  if ($route_name !== NULL && preg_match('/^(layout_builder\.([^.]+\.)?)/', $route_name)) {
    $layout_builder_route = TRUE;
  }

  $context = NULL;
  \Drupal::moduleHandler()->alter('varbase_layout_builder__is_layout_builder_route', $layout_builder_route, $context);
  unset($context);
  return $layout_builder_route;
}

/**
 * Returns true if the current route is a dashboard route.
 *
 * @return bool
 *   Returns true for dashboard routes.
 */
function varbase_layout_builder__is_dashboard_route() {
  $dashboard_route = drupal_static(__FUNCTION__);
  if ($dashboard_route !== NULL) {
    return $dashboard_route;
  }

  $route_name = \Drupal::routeMatch()->getRouteName();
  $dashboard_route = FALSE;

  if ($route_name !== NULL
    && (str_contains($route_name, 'dashboards') || str_contains(\Drupal::requestStack()->getCurrentRequest()->getPathInfo(), 'dashboards'))) {
    $dashboard_route = TRUE;
  }

  $context = NULL;
  \Drupal::moduleHandler()->alter('varbase_layout_builder__is_dashboard_route', $dashboard_route, $context);
  unset($context);
  return $dashboard_route;
}
