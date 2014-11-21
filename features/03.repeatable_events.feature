Feature: Creation repeatable events in calendar. Repeating by day, week, month, year with given interval
  @basic @javascript
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
    Then I select "открытый" from "Access Type"
    And I select "webinar_image" in "media"
    Then I press "btn_create_and_edit"
    And I should see "создан"    
    Then I reset sonata unique ID
    And I select "день" in "eventRepeat_repeatType"
    Then I fill "eventRepeat_endDateTime" with current date plus "5" days
    And I fill "eventRepeat_repeatInterval" with "1"    
    And I check "eventRepeat_repeatDays_1" checkbox
    And I check "eventRepeat_repeatDays_2" checkbox
    And I check "eventRepeat_repeatDays_3" checkbox
    And I check "eventRepeat_repeatDays_4" checkbox
    And I check "eventRepeat_repeatDays_5" checkbox
    And I check "eventRepeat_repeatDays_6" checkbox
    And I check "eventRepeat_repeatDays_0" checkbox

    Then press "btn_update_and_list"
    And I should see "успешно обновлен"