@javascript
Feature: Palceholders creation feature

  Scenario: Create and delete placeholder 
    Given I am logged in as admin
    Then I go to "/admin/dashboard"
    And I follow "SPONSOR"
    Then I should see " Placeholders"
    Then I wait for AJAX to finish
    And I go to "/admin/success/placeholder/externalplaceholder/create?uniqid=s5444ed96d21e7"
    
    Then I fill in "s5444ed96d21e7_name" with "test_placeholder"
    And I fill in "s5444ed96d21e7_pattern" with "%test_placeholder_pattern%"
    When I press "Create"
    Then I should see "has been successfully created"

    Then I go to "/admin/success/placeholder/externalplaceholder/list"
    And I should see "test_placeholder"
    Then I follow "test_placeholder"
    
    Then I follow "Delete"
    And I press "Yes, delete"
    Then I should see "deleted successfully."

    Then I go to "/admin/success/placeholder/externalplaceholder/list"
    And I should not see "test_placeholder"