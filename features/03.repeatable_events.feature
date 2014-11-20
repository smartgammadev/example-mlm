@javascript
Feature: Creation repeatable events in calendar. Repeating by day, week, month, year with given interval
  
  Scenario: Create a repeatable event by day with 1 interval
    Given I am logged in as admin
    Then I want to create new event
    And I fill "startDateTime" with current date plus "15" minutes
    Then I fill "url" with "https://go.myownconference.ru/4SuccessTeam"
    And I fill "name" with "test webinar #1"
    Then I fill "description" with "This is description for test webinar #1"
    And I fill "pattern" with "pattern"
    Then I fill "password" with "password"
    And I select "вводный вебинар" in "eventType"
    Then I select "открытый" in "accessType"
    And I select "webinar_image" in "media"
    Then press "btn_create_and_list"
    And I should see "создан"

    Then I follow "test webinar #1"
    And I press "Repeatable"
    Then I fill "endDateTime" with current date plus "30" days
    And I select "D" in "repeatType"
    Then I fill "interval" with "1"
    And press "btn_create_and_list"
    Then I should see "успешно обновлен"