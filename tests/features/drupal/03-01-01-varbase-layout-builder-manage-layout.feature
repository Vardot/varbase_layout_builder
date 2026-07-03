@varbase_layout_builder @layout
Feature: Varbase Layout Builder - manage layout
  As a site administrator
  I want to open the Layout Builder editing UI for a content type that has
  Layout Builder enabled, so that sections and blocks can be managed

  Scenario: The Manage layout page renders the Layout Builder editing UI
    Given I am a logged in user with the "Webmaster" user
    When I open the administration page "/admin/structure/types/manage/page/display/default/layout"
    Then I should not see "Page not found"
    And I should not see "Access denied"
    And I should see "Add section"

  Scenario: The default node layout is reachable for the administrator
    Given I am a logged in user with the "Webmaster" user
    When I open the administration page "/admin/structure/types/manage/page/display"
    Then I should not see "Page not found"
    And I should see "Manage display"
