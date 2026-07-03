@varbase_layout_builder @admin
Feature: Varbase Layout Builder - administration pages
  As a site administrator
  I want the core, structure and content-type administration pages to be
  reachable with the Varbase Layout Builder stack (core Layout Builder, Layout
  Builder Restrictions, Bootstrap Layout Builder, Section Library, ...) enabled

  Scenario: The administration pages are reachable for the administrator
    Given I am a logged in user with the "Webmaster" user
    When I open the administration page "/admin/content"
    Then I should not see "Page not found"
    When I open the administration page "/admin/structure"
    Then I should not see "Page not found"
    When I open the administration page "/admin/structure/types"
    Then I should not see "Page not found"
    When I open the administration page "/admin/structure/types/manage/page/display"
    Then I should not see "Page not found"
    When I open the administration page "/admin/content/block"
    Then I should not see "Page not found"
    When I open the administration page "/admin/config"
    Then I should not see "Page not found"
    When I open the administration page "/admin/people"
    Then I should not see "Page not found"
    When I open the administration page "/admin/reports/status"
    Then I should not see "Page not found"
