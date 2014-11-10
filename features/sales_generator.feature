@sales_gen                                                    
Feature: Sales Generator(frontend)
    In order to use sales generator
    as a user
    Audiences, questions and answers need to be already in database
    
    Scenario: Check audiences
        Given I am on "/audiences"
         Then I should see "AltAutomatic - Теплый рынок 2 - Тест партнерской ссылки"

    Scenario: Step into audience
        Given I am on "/audiences/1"
         Then I should see "Вопрос"
          And I should see "Ответ"
         When I click ".new-question"