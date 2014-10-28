@javascript
Feature: Calendar display feature
  
  Scenario: Display Calendar
    Given I am logged in as admin
    Then I go to "/admin/success/event/webinarevent/create?uniqid=s5444ed96d21e7"

    And I fill in "s5444ed96d21e7_startDateTime" with "2014-10-30T12:00:00+0200"
    Then I fill in "s5444ed96d21e7_url" with "http://www.google.com.ua"
    And I fill in "s5444ed96d21e7_name" with "for_test_webinar"
    Then I fill in "s5444ed96d21e7_pattern" with "pattern"
    And I fill in "s5444ed96d21e7_password" with "pwd"
    Then I select "вводный вебинар" from "s5444ed96d21e7_eventType"
    And I select "открытый" from "s5444ed96d21e7_accessType"    
    And I select "dog-exper2.jpg" from "s5444ed96d21e7_media"
    And press "Create"
    Then I should see "has been successfully created"