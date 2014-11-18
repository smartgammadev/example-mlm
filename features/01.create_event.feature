@javascript
Feature: Calendar feature
  
  Scenario: Create, edit, delete events

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

    Then I want to create new event
    And I fill "startDateTime" with current date plus "30" minutes
    Then I fill "url" with "https://go.myownconference.ru/4SuccessTeam"
    And I fill "name" with "test webinar #2"
    Then I fill "description" with "This is description for test webinar #2"
    And I fill "pattern" with "pattern"
    Then I fill "password" with "password"
    And I select "вводный вебинар" in "eventType"
    Then I select "открытый" in "accessType"
    And I select "webinar_image" in "media"
    Then press "btn_create_and_list"
    And I should see "создан"

    Then I go to "/calendarevents/nearest" with "user.email=stas-81@mail.ru&user.first_name=uName&user.last_name=uLastName&sponsor.email=stas-81@mail.ru&sponsor.first_name=sName&sponsor.last_name=sLastName" placeholders
    And I should see "test webinar #1"
    Then I should see "test webinar #2"
    And I should see "Войти в Вебинар"
    And I should see "Записаться"

    Then I go to "/admin/success/event/webinarevent/list"
    And I follow "test webinar #1"
    Then I follow "Удалить"
    And I press "Да, удалить"
    Then I should see "Элемент успешно удален."

    Then I go to "/admin/success/event/webinarevent/list"
    And I follow "test webinar #2"
    Then I follow "Удалить"
    And I press "Да, удалить"
    Then I should see "Элемент успешно удален."

    Then I go to "/calendarevents/nearest" with "user.email=stas-81@mail.ru&user.first_name=uName&user.last_name=uLastName&sponsor.email=stas-81@mail.ru&sponsor.first_name=sName&sponsor.last_name=sLastName" placeholders
    And I should not see "test webinar #1"
    Then I should not see "test webinar #2"