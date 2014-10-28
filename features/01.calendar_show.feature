@javascript
Feature: Calendar display feature
  
  Scenario: Display Calendar
    Given I am on "/calendar/show"
    Then I should see "Sun"
    And I should see "Mon"