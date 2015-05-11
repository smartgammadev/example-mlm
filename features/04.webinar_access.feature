Feature: User access to closed webinars must be granted or denied by {{user.businessLinkFull}}
        placehoder. If it's have a value then access granted, in other cases access denied

  @basic @javascript @now
  Scenario: Create a closed event and check access for it in a frontend
    Given there is no events in DB
    Given the following events exist:
        | name         | access_type      | description   | type            | date_modifier |
        | test webinar | для партнеров    | description 1 | вводный вебинар | +5 minutes   |
    Given I am not logged
     When I go to "/calendarevents/nearest" with placeholders
        | user.email               | user@mail             |
        | user.first_name          | uName                 |
        | user.last_name           | uLastName             |
        | user.businessLinkFull    | http://example.com    |
        | sponsor.email            | 4success.bz@gmail.com |
        | sponsor.first_name       | sName                 |
        | sponsor.last_name        | sLastName             |
      And I follow "Записаться"
     Then I wait for AJAX to finish
      And I press "signup_Записаться"

     Then I wait for AJAX to finish
     Then I should see "Вы успешно зарегистрированы"
      And I press "OK"
     Then I should see "Войти в Вебинар" button enabled
     When I go to "/calendarevents/nearest" with placeholders
        | user.email               | user@mail             |
        | user.first_name          | uName                 |
        | user.last_name           | uLastName             |
        | sponsor.email            | 4success.bz@gmail.com |
        | sponsor.first_name       | sName                 |
        | sponsor.last_name        | sLastName             |
     And I follow "Записаться"
     Then I wait for AJAX to finish
      And I should see "Извините. Доступ к даному вебинару разрешен только для партнеров с VIP статусом."
     Then I press "OK"
      And I should see "Войти в Вебинар" button disabled