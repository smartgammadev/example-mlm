Feature: Creation repeatable events in calendar. Repeating by day, week, month, year with given interval
  @basic @javascript
  Scenario: Create a repeatable event by day with 1 day interval
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
    Then I fill "eventRepeat_endDateTime" with current date plus "6" days
     And I fill "eventRepeat_repeatInterval" with "1"    
     And I check "eventRepeat_repeatDays_1" checkbox
     And I check "eventRepeat_repeatDays_2" checkbox
     And I check "eventRepeat_repeatDays_3" checkbox
     And I check "eventRepeat_repeatDays_4" checkbox
     And I check "eventRepeat_repeatDays_5" checkbox
     And I check "eventRepeat_repeatDays_6" checkbox
     And I check "eventRepeat_repeatDays_0" checkbox
    Then I press "btn_update_and_list"
     And I should see "успешно обновлен"

    When I go to "/calendarevents/nearest" with "user.email=user@mail&user.first_name=uName&user.last_name=uLastName&sponsor.email=sponsor@mail&sponsor.first_name=sName&sponsor.last_name=sLastName" placeholders
    Then I should see 1 ".day-event" elements
     And I should see 5 ".week-event" elements

    When I go to "/admin/success/event/webinarevent/list"
    Then I should see "test webinar #1"
     And I follow "test webinar #1"
    Then I should see "Удалить"
     And I follow "Удалить"
    Then I should see "Да, удалить"
     And I press "Да, удалить"
    Then I should see "Элемент успешно удален."

    When I go to "/calendarevents/nearest" with "user.email=user@mail&user.first_name=uName&user.last_name=uLastName&sponsor.email=sponsor@mail&sponsor.first_name=sName&sponsor.last_name=sLastName" placeholders
    Then I should not see "test webinar #1"
     And I should see "нет ближайших мероприятий"

  @basic @javascript
  Scenario: Create a repeatable event by day with 2 days interval
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
    Then I fill "eventRepeat_endDateTime" with current date plus "7" days
     And I fill "eventRepeat_repeatInterval" with "2"
     And I check "eventRepeat_repeatDays_1" checkbox
     And I check "eventRepeat_repeatDays_2" checkbox
     And I check "eventRepeat_repeatDays_3" checkbox
     And I check "eventRepeat_repeatDays_4" checkbox
     And I check "eventRepeat_repeatDays_5" checkbox
     And I check "eventRepeat_repeatDays_6" checkbox
     And I check "eventRepeat_repeatDays_0" checkbox
    Then I press "btn_update_and_list"
     And I should see "успешно обновлен"

    When I go to "/calendarevents/nearest" with "user.email=user@mail&user.first_name=uName&user.last_name=uLastName&sponsor.email=sponsor@mail&sponsor.first_name=sName&sponsor.last_name=sLastName" placeholders
    Then I should see 1 ".day-event" elements
     And I should see 3 ".week-event" elements

    When I go to "/admin/success/event/webinarevent/list"
    Then I should see "test webinar #1"
     And I follow "test webinar #1"
    Then I should see "Удалить"
     And I follow "Удалить"
    Then I should see "Да, удалить"
     And I press "Да, удалить"
    Then I should see "Элемент успешно удален."

    When I go to "/calendarevents/nearest" with "user.email=user@mail&user.first_name=uName&user.last_name=uLastName&sponsor.email=sponsor@mail&sponsor.first_name=sName&sponsor.last_name=sLastName" placeholders
    Then I should not see "test webinar #1"
     And I should see "нет ближайших мероприятий"

  @basic @javascript
  Scenario: Create a repeatable event by week with 1 week interval
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
      And I select "неделя" in "eventRepeat_repeatType"
     Then I fill "eventRepeat_endDateTime" with current date plus "10" days
      And I fill "eventRepeat_repeatInterval" with "1"
      And I check "eventRepeat_repeatDays_1" checkbox
      And I check "eventRepeat_repeatDays_2" checkbox
      And I check "eventRepeat_repeatDays_3" checkbox
      And I check "eventRepeat_repeatDays_4" checkbox
      And I check "eventRepeat_repeatDays_5" checkbox
      And I check "eventRepeat_repeatDays_6" checkbox
      And I check "eventRepeat_repeatDays_0" checkbox
     Then I press "btn_update_and_list"
      And I should see "успешно обновлен"

    When I go to "/calendarevents/nearest" with "user.email=user@mail&user.first_name=uName&user.last_name=uLastName&sponsor.email=sponsor@mail&sponsor.first_name=sName&sponsor.last_name=sLastName" placeholders
    Then I should see 1 ".day-event" elements
     And I should see 1 ".week-event" elements

    When I go to "/admin/success/event/webinarevent/list"
    Then I should see "test webinar #1"
     And I follow "test webinar #1"
    Then I should see "Удалить"
     And I follow "Удалить"
    Then I should see "Да, удалить"
     And I press "Да, удалить"
    Then I should see "Элемент успешно удален."
    When I go to "/calendarevents/nearest" with "user.email=user@mail&user.first_name=uName&user.last_name=uLastName&sponsor.email=sponsor@mail&sponsor.first_name=sName&sponsor.last_name=sLastName" placeholders
    Then I should not see "test webinar #1"
     And I should see "нет ближайших мероприятий"