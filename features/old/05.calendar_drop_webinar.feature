@javascript
Feature: Check display events feature
  
  Scenario: Display Calendar
    Given I am logged in as admin
    Then I go to "/admin/dashboard"
    And I follow "SPONSOR"
    Then I should see " Webinars"
    Then I wait for AJAX to finish
    And I go to "/admin/success/event/webinarevent/list"
    Then I should see " Webinar event list"
    
    And I follow "for_test_webinar"
    Then I follow "Delete"
    And I press "Yes, delete"
    Then I should see "deleted successfully."

    And I follow "for_test_webinar2"
    Then I follow "Delete"
    And I press "Yes, delete"
    Then I should see "deleted successfully."
    
    And I go to "/calendar/show"
    Then I should see "Calendar"
    And I should not see "for_test_webinar"
    And I should not see "for_test_webinar2"