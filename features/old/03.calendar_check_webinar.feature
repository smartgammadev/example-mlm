@javascript
Feature: Check display events feature
  
  Scenario: Display Calendar with created events
    Given I am on "/calendar/show"
    And I wait for AJAX to finish 
    Then I should see "for_test_webinar"
 
    And I follow "for_test_webinar"
    Then I wait for AJAX to finish 
    And I should see "Event detail"
    Then I should see "for_test_webinar"
    And the "form" element should contain "Войти в вебинар"
    And I should see "Event URL:"
    Then I should see "http://www.google.com.ua"

    Then I should see "for_test_webinar2"
    And I follow "for_test_webinar2"
    Then I wait for AJAX to finish
    And I should see "Event detail"
    Then I should see "for_test_webinar"
    And the "form" element should contain "Записаться"
    And I should see "Event URL:"
    Then I should see "http://www.mail.ru"
