@varbase_layout_builder @frontend
Feature: Varbase Layout Builder - front-end with a Varbase theme (vartheme_bs4)
  As a visitor
  I want the site to render with the Varbase theme (vartheme_bs4) and Varbase Layout Builder enabled

  Scenario: The front page renders with the Varbase theme (vartheme_bs4)
    Given I am an anonymous visitor
    When I am on "/"
    Then I should not see "Page not found"
    And I should not see "The website encountered an unexpected error"
    And "body.path-frontpage, body" should be visible
