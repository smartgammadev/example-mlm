Feature: Static pages feature

  @basic @javascript
    Scenario:  Create, edit, delete  pages

    Given  I am logged in as admin
    Then I want to create new static page
    And  I fill "slug" with "page_test"
    And  I fill in ckeeditor with "Project via Test 1"
    And  I select "1" in "isActive"
    Then I press "btn_create_and_edit"
    And  I should see "Элемент создан успешно"

    Then I click add paket and select "стандарт"
    And  I click add paket and select "V.I.P."
    Then I press "btn_update_and_edit"
    And  I should see "Элемент успешно обновлен."

    Then I follow "Удалить"
    And  I should see "Да, удалить"
    And  I press "Да, удалить"
    And  I should see "Элемент успешно удален."

 