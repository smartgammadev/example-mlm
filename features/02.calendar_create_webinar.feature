@javascript
Feature: Calendar create feature
  
  Scenario: Create two different events
    Given I am logged in as admin

    Then I go to "/admin/success/event/webinarevent/create?uniqid=s5444ed96d21e7"
    And I fill in "s5444ed96d21e7_startDateTime" with current date plus "15" minutes
    And I wait for AJAX to finish
    Then I fill in "s5444ed96d21e7_url" with "http://www.google.com.ua"
    And I fill in "s5444ed96d21e7_name" with "for_test_webinar"
    Then I fill in "s5444ed96d21e7_pattern" with "pattern"
    And I fill in "s5444ed96d21e7_password" with "pwd"
    Then I select "вводный вебинар" from "s5444ed96d21e7_eventType"
    And I select "открытый" from "s5444ed96d21e7_accessType"    
    And I select "dog-exper2.jpg" from "s5444ed96d21e7_media"
    And press "Create"
    Then I should see "has been successfully created"

    Then I go to "/admin/success/event/webinarevent/create?uniqid=s5444ed96d21e7"
    And I fill in "s5444ed96d21e7_startDateTime" with current date plus "25" minutes
    And I wait for AJAX to finish
    Then I fill in "s5444ed96d21e7_url" with "http://www.mail.ru"
    And I fill in "s5444ed96d21e7_name" with "for_test_webinar2"
    Then I fill in "s5444ed96d21e7_pattern" with "pattern"
    And I fill in "s5444ed96d21e7_password" with "pwd"
    Then I select "вводный вебинар" from "s5444ed96d21e7_eventType"
    And I select "открытый" from "s5444ed96d21e7_accessType"
    And I select "dog-exper2.jpg" from "s5444ed96d21e7_media"
    And press "Create"
    Then I should see "has been successfully created"