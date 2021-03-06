Feature: Calendar feature
  
  @basic @javascript @now
  Scenario: Create, edit, delete events

    Given there is no events in DB
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
     Then I press "btn_create_and_list"
      And I should see "Элемент создан успешно"

    Then I want to create new event
     And I fill "startDateTime" with current date plus "60" minutes
    Then I fill "url" with "https://go.myownconference.ru/4SuccessTeam"
     And I fill "name" with "test webinar #2"
    Then I fill "description" with "This is description for test webinar #2"
     And I fill "pattern" with "pattern"
    Then I fill "password" with "password"
     And I select "вводный вебинар" in "eventType"
    Then I select "открытый" in "accessType"
     And I select "webinar_image" in "media"
    Then I press "btn_create_and_list"
     And I should see "Элемент создан успешно"
    When I go to "/calendarevents/nearest" with placeholders
        | user.email               | stas-81@mail.ru       |
        | user.first_name          | uName                 |
        | user.last_name           | uLastName             |
        | sponsor.email            | 4success.bz@gmail.com |
        | sponsor.first_name       | sName                 |
        | sponsor.last_name        | sLastName             |
    Then I should see "test webinar #1"
     And I should see "test webinar #2"
    Then I should see "Войти в Вебинар"
     And I should see "Записаться"

    When I go to "/admin/success/event/webinarevent/list"
    Then I should see "test webinar #1"
     And I follow "test webinar #1"
    Then I should see "Удалить"
     And I follow "Удалить"
    Then I should see "Да, удалить"
     And I press "Да, удалить"
    Then I should see "Элемент успешно удален."

    When I go to "/admin/success/event/webinarevent/list"
    Then I should see "test webinar #2"
     And I follow "test webinar #2"
    Then I should see "Удалить"
     And I follow "Удалить"
    Then I should see "Да, удалить"
     And I press "Да, удалить"
    Then I should see "Элемент успешно удален."

    When I go to "/calendarevents/nearest" with placeholders
        | user.email               | stas-81@mail.ru       |
        | user.first_name          | uName                 |
        | user.last_name           | uLastName             |
        | sponsor.email            | 4success.bz@gmail.com |
        | sponsor.first_name       | sName                 |
        | sponsor.last_name        | sLastName             |
     And I should not see "test webinar #1"
    Then I should not see "test webinar #2"
     And I should see "нет ближайших мероприятий"
