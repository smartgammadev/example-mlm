
Feature: Sign up for event in calendar
  
  @basic @javascript @signup
  Scenario: Sign up for event
   Given there is no events in DB
   Given there is no new members in DB
   Given there is no notifications in DB
   Given the following events exist:
        | name         | access_type | description   | type            | date_modifier |
        | test webinar | открытый    | description 1 | вводный вебинар | +15 minutes   |
   Given I am not logged
    When I go to "/calendarevents/nearest" with "user.email=user1@mail&user.first_name=uName&user.last_name=uLastName&sponsor.email=4success.bz@gmail.com&sponsor.first_name=sName&sponsor.last_name=sLastName" placeholders
    Then I should see "Записаться"
     And I follow "Записаться"
    Then I wait for AJAX to finish
     And I press "signup_Записаться"
    Then I wait for AJAX to finish
    Then I should see "Вы успешно зарегистрированы"
     And I press "OK"
    Then I should have member with id "user1@mail"
    Then I should have 2 email notification to "user1@mail"
     And I should have 1 email notification to "4success.bz@gmail.com"
     And member "user1@mail" should be user
     And sponsor of "user1@mail" member should be "4success.bz@gmail.com"

  @basic @javascript @signup
  Scenario: Try to sign up for event that user allready signed up
    When I go to "/calendarevents/nearest" with "user.email=user1@mail&user.first_name=uName&user.last_name=uLastName&sponsor.email=4success.bz@gmail.com&sponsor.first_name=sName&sponsor.last_name=sLastName" placeholders
    Then I should see "Записаться"
     And I follow "Записаться"
    Then I wait for AJAX to finish
     And I press "signup_Записаться"
    Then I wait for AJAX to finish
    Then I should see "Вы уже зарегистрированы"
     And I press "OK"

  @basic @javascript @signup
  Scenario: Sign up for event with phone numbers
    When I go to "/calendarevents/nearest" with "user.email=user2@mail&user.first_name=uName&user.last_name=uLastName&sponsor.email=4success.bz@gmail.com&sponsor.first_name=sName&sponsor.last_name=sLastName&user.phone=012345678&sponsor.phone=112345678" placeholders
     And I follow "Записаться"
    Then I wait for AJAX to finish
     And I press "signup_Записаться"
    Then I wait for AJAX to finish
    Then I should see "Вы успешно зарегистрированы"
     And I press "OK"
    Then I should have 2 email notification to "user2@mail"
     And I should have 2 email notification to "4success.bz@gmail.com"
    Then I should have 1 SMS notification to "012345678"
     And I should have 1 SMS notification to "112345678"
    Then I should have member with id "user2@mail"
    And member "user2@mail" should be user
    And sponsor of "user2@mail" member should be "4success.bz@gmail.com"
    And sponsor "4success.bz@gmail.com" should have 2 referals

  @basic @javascript @signup
  Scenario: Sign up from sponsor user1@mail
    When I go to "/calendarevents/nearest" with "user.email=user3@mail&user.first_name=uName&user.last_name=uLastName&sponsor.email=user1@mail&sponsor.first_name=sName&sponsor.last_name=sLastName" placeholders
    Then I should see "Записаться"
     And I follow "Записаться"
    Then I wait for AJAX to finish
     And I press "signup_Записаться"
    Then I wait for AJAX to finish
    Then I should see "Вы успешно зарегистрированы"
     And I press "OK"
    Then I should have member with id "user3@mail"
     And sponsor of "user3@mail" member should be "user1@mail"
    Then I should have 2 email notification to "user3@mail"
     And I should have 3 email notification to "user1@mail"
     And member "user1@mail" should be sponsor
     And sponsor "user1@mail" should have 1 referals
     And sponsor "4success.bz@gmail.com" should have 3 referals