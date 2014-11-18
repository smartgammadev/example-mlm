@javascript
Feature: Sign up for event in calendar
  
  Scenario: Create and sign up for created event

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

    Then I go to "/calendarevents/nearest" with "user.email=stas-81@mail.ru&user.first_name=uName&user.last_name=uLastName&sponsor.email=stas-81@mail.ru&sponsor.first_name=sName&sponsor.last_name=sLastName" placeholders
    And I follow "Записаться"
    Then I wait for AJAX to finish
    And I press "signup_Записаться"
    Then I wait for AJAX to finish
    Then I should see "Вы успешно зарегистрированы"
    And I press "OK"

    Then I should see 3 new notifications