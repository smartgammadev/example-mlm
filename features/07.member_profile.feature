Feature: Member action in profile

  @basic @javascript
  Scenario: Member must be registerd and logged. 
    When I go to "/calendarevents/nearest" with placeholders
        | user.email               | two_user@email.email  |
        | user.first_name          | user_twoFirstName     |
        | user.last_name           | user_twoLastName      |
        | sponsor.email            | 4success.bz@gmail.com |
        | sponsor.first_name       | sFirstName            |
        | sponsor.last_name        | sLastName             |
    When I go to "/member/profile"
    Then I should see "user_twoFirstName"
    And I should see "user_twoLastName"
    And I should see "баланс: $0"
    And I should see "Ваш балланс не активирован"
    And I should see "пополнить счет"
    And I should see "история счета"
    And I should see "тариф не назначен"
    And I should see "Моя команда"
    And I should see "лидер: 4success.bz@gmail.com"
    When I click "refund-btn"
    Then I wait for AJAX to finish
    When I click "gfr"
    And I should see "История счета"
    And I should see "100"
    And I should see "пополнение счета"
    When I click "pricing-id-3"
    Then I wait for AJAX to finish
    When I click "gfr"
    And I should see "История счета"
    And I should see "-90.00"
    And I should see "приобретение пакета 'V.I.P.'"
    
    
