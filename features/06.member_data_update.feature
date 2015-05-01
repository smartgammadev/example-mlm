Feature: Data of members should updating from placeholders

  @basic @javascript
  Scenario: Go to the registration url member data should be updated

    Given there is no new members in DB
    Given I am not logged
    When I go to "/calendarevents/nearest" with placeholders
        | user.email               | john@mail.ss          |
        | user.first_name          | John                  |
        | user.last_name           | Doe                   |
        | user.skype               | testSkype             |
        | user.phone               | 129255792             |
        | user.user_extra_1        | extra1-inf            |
        | user.user_extra_2        | extra2-inf            |
        | user.country             | Moon                  |
        | user.city                | Darkside              |
        | user.email               | john@mail.ss          |
        | user.vkontakte           | testVkontakte         |
        | user.facebook            | testFacebook          |
        | user.twitter             | testTwitter           |
        | user.photoPath           | pathToPhoto           |
        | user.partnerLinkFull     | testPLF               |
        | user.businessLinkFull    | testBLF               |
        | sponsor.email            | 4success.bz@gmail.com |
        | sponsor.first_name       | JohnSponsor           |
        | sponsor.last_name        | DoeSponsor            |
        | sponsor.skype            | testSkypeSponsor      |
        | sponsor.phone            | 129255793             |
        | sponsor.user_extra_1     | extra1-inf-sponsor    |
        | sponsor.user_extra_2     | extra2-inf-sponsor    |
        | sponsor.country          | Moon4Sponsor          |
        | sponsor.city             | Darkside4Sponsor      |
        | sponsor.email            | 4success.bz@gmail.com |
        | sponsor.vkontakte        | testVkontakteS        |
        | sponsor.facebook         | testFacebookS         |
        | sponsor.twitter          | testTwitterS          |
        | sponsor.photoPath        | pathToPhotoS          |
        | sponsor.partnerLinkFull  | testPLFS              |
        | sponsor.businessLinkFull | testBLFS              |
    Then "first_name" of member "john@mail.ss" should be "John"
     And "last_name" of member "john@mail.ss" should be "Doe"
     And "skype" of member "john@mail.ss" should be "testSkype"
     And "phone" of member "john@mail.ss" should be "129255792"
     And "user_extra_1" of member "john@mail.ss" should be "extra1-inf"
     And "user_extra_2" of member "john@mail.ss" should be "extra2-inf"
     And "country" of member "john@mail.ss" should be "Moon"
     And "city" of member "john@mail.ss" should be "Darkside"
     And "email" of member "john@mail.ss" should be "john@mail.ss"
     And "vkontakte" of member "john@mail.ss" should be "testVkontakte"
     And "facebook" of member "john@mail.ss" should be "testFacebook"
     And "twitter" of member "john@mail.ss" should be "testTwitter"
     And "photoPath" of member "john@mail.ss" should be "pathToPhoto"
     And "partnerLinkFull" of member "john@mail.ss" should be "testPLF"
     And "businessLinkFull" of member "john@mail.ss" should be "testBLF"