Feature: Process Email Cron Command

  @mink:symfony2 @basic @command 
  Scenario: Running notify:process_email command
    When  I run "notify:process_email" command
    Then I should see console output "/Processing mail/"

    And I should get an email on "testCommand@mail.com" with:
    """
    Информируем Вас о том, что Вы записаны на вебинар.
    """