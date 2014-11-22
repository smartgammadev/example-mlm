@javascript @basic                                                 
Feature: Sales Generator(frontend)
    In order to use sales generator
    as a user
    Audiences, questions and answers need to be already in database
    
    @basic
    Scenario: Check audiences
        Given I am on "/audiences"
         Then I should see 4 ".audience" elements
    @basic
    Scenario: Step into audience
        Given I am on "/audience/1"
         Then I should see "Вопрос"
          And I should see 3 ".answer" elements
         When I click ".new-question:first-child"
         Then I should see 2 ".answer" elements
         When I click ".new-question:first-child"
         Then I should see 2 ".answer" elements
          And I should not see "Ответ №3"
         When I click ".new-question:nth-of-type(1)"
         Then I should see 0 ".answer" elements