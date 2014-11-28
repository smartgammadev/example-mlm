Feature: Process Email Cron Command

  @basic @command
  Scenario: Running notify:process_email command
    When  I run "notify:process_email" command
    Then I should see "Processing mail"