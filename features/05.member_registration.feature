Feature: Member registration and loging

  @basic @javascript
  Scenario: Any member must be registerd and logged in by simple signup to event. 
            Data should be taken from placeholders.
    Given there is no events in DB
    Given there is no new members in DB
    Given I am not logged
    Given the following events exist:
        | name         | access_type | description        | type            | date_modifier |
        | Test Event 1 | открытый    | test description 1 | вводный вебинар | +15 minutes   |
        | Test Event 2 | открытый    | test description 2 | вводный вебинар | +20 minutes   |
        | Test Event 3 | открытый    | test description 3 | вводный вебинар | +25 minutes   |
    When I go to "/calendarevents/nearest" with "user.email=new_user@email.email&user.first_name=userFirstName&user.last_name=userLastName&sponsor.email=main.sponsor@mail.com&sponsor.first_name=sName&sponsor.last_name=sLastName" placeholders
    Then I should see 3 ".day-webinar" elements
    Then I should have member with id "new_user@email.email"
     And member "main.sponsor@mail.com" should be sponsor
     And member "new_user@email.email" should be user
     And sponsor of "new_user@email.email" member should be "main.sponsor@mail.com"
    When I go to "/calendarevents/nearest" with "user.email=another_new_user@email.email&user.first_name=userFirstName&user.last_name=userLastName&sponsor.email=main.sponsor@mail.com&sponsor.first_name=sName&sponsor.last_name=sLastName" placeholders
    Then I should have member with id "another_new_user@email.email"
     And member "main.sponsor@mail.com" should be sponsor
     And member "another_new_user@email.email" should be user
     And sponsor of "another_new_user@email.email" member should be "main.sponsor@mail.com"