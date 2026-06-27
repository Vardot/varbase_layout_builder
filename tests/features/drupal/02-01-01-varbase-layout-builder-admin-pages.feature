@varbase_layout_builder @admin
Feature: Varbase Layout Builder - administration pages
  Scenario: The administration pages are reachable with Varbase Layout Builder enabled
    Given I am a logged in user with the "Webmaster" user
    When I open the administration page "/admin/structure"
    Then I should not see "Page not found"
    When I open the administration page "/admin/content"
    Then I should not see "Page not found"
    When I open the administration page "/admin/structure/block"
    Then I should not see "Page not found"
    When I open the administration page "/admin/structure/types"
    Then I should not see "Page not found"
    When I open the administration page "/admin/reports/status"
    Then I should not see "Page not found"
