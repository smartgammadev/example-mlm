Feature: Audience backend  crud feature

  @basic @javascript @now
    Scenario:  Create, edit, delete  audience

    Given  I am logged in as admin
    Then I want to create new audience
    And I fill "name" with "New Test Audience"
    Then I select "6 В современном..." in "firstQuestion"
    And I press "btn_create_and_list"
    Then I should see "создан"
    Then I want to create new audience
    And I fill "name" with "New Test Audience"
    Then I select "1 Доброго времени..." in "firstQuestion"
    And I press "btn_create_and_list"
    Then I should see "создан"
    Then I should see "New Test Audience"

    When I follow "New Test Audience"
    Then I should see "Редактировать \"New Test Audien...\""
    And  I press "btn_update_and_list"
    Then I should see "Элемент успешно обновлен."

    When I follow "New Test Audience"
    Then I should see "Удалить"
     And I follow "Удалить"
    Then I should see "Да, удалить"
     And I press "Да, удалить"
    Then I should see "Элемент успешно удален."