@varbase_layout_builder @permissions
Feature: Varbase Layout Builder - permission matrix
  As a site owner
  I want the Layout Builder editing UI to be restricted to privileged users, so
  that anonymous visitors and unprivileged accounts cannot manage layouts

  Scenario: An anonymous visitor cannot reach the Manage layout page
    Given I am an anonymous visitor
    When I visit the page "/admin/structure/types/manage/page/display/default/layout"
    Then the page should be access restricted

  Scenario: A normal authenticated user cannot reach the Manage layout page
    Given I am a logged in user with the "Normal user" user
    When I visit the page "/admin/structure/types/manage/page/display/default/layout"
    Then the page should be access restricted
