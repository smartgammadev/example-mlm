Feature: User access to closed webinars must be granted or denied by {{user.businessLinkFull}}
        placehoder. If it's have a value then access granted, in other cases access denied

  @basic @javascript
  Scenario: Create a closed event and check access for it in a frontend

    Given I am logged in as admin
     Then I want to create new event
      And I fill "startDateTime" with current date plus "15" minutes
     Then I fill "url" with "https://go.myownconference.ru/4SuccessTeam"
      And I fill "name" with "test webinar #1"
     Then I fill "description" with "This is description for test webinar #1"
      And I fill "pattern" with "pattern"
     Then I fill "password" with "password"
      And I select "вводный вебинар" in "eventType"
     Then I select "для партнеров" in "accessType"
      And I select "webinar_image" in "media"
     Then I press "btn_create_and_list"
      And I should see "создан"

     When I go to "/calendarevents/nearest" with "user.email=user@mail&user.first_name=uName&user.last_name=uLastName&sponsor.email=sponsor@mail&sponsor.first_name=sName&sponsor.last_name=sLastName&user.businessLinkFull=http://example.com" placeholders
      And I follow "Записаться"
     Then I wait for AJAX to finish
      And I press "signup_Записаться"
     Then I wait for AJAX to finish
     Then I should see "Вы успешно зарегистрированы"
      And I press "OK"
     Then I should see "Войти в Вебинар" button enabled

     When I go to "/calendarevents/nearest" with "user.email=user@mail&user.first_name=uName&user.last_name=uLastName&sponsor.email=sponsor@mail&sponsor.first_name=sName&sponsor.last_name=sLastName" placeholders
      And I follow "Записаться"
     Then I wait for AJAX to finish
      And I should see "Извините. Доступ к даному вебинару разрешен только для партнеров с VIP статусом."
     Then I press "OK"
      And I should see "Войти в Вебинар" button disabled

     When I go to "/admin/success/event/eventsignup/list"
     Then I should see "Удалить"
      And I follow "Удалить"
     Then I should see "Да, удалить"
      And I press "Да, удалить"
     Then I should see "Элемент успешно удален."

     When I go to "/admin/success/event/webinarevent/list"
     Then I should see "test webinar #1"
      And I follow "test webinar #1"
     Then I should see "Удалить"
      And I follow "Удалить"
     Then I should see "Да, удалить"
      And I press "Да, удалить"
     Then I should see "Элемент успешно удален."

     Then I go to "/calendarevents/nearest" with "user.email=stas-81@mail.ru&user.first_name=uName&user.last_name=uLastName&sponsor.email=stas-81@mail.ru&sponsor.first_name=sName&sponsor.last_name=sLastName" placeholders
      And I should see "нет ближайших мероприятий"

