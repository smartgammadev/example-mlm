@javascript
Feature: Check display events feature
  
  Scenario: Display Calendar
    Given I am on "/calendar/show"
    And I wait for AJAX to finish 
    Then I should see "for_test_webinar"

    And I follow "for_test_webinar"
    Then I wait for AJAX to finish 

    And I should see "Event detail"
    Then I should see "for_test_webinar"

    And I should see "Event URL:"
    Then I should see "http://www.google.com.ua"

    Then I should see "Event detail"
    And I should see "Event detail"