@raffle @api @web
Feature:
  In order to give away cool prizes to our members that leave feedback
  As an organizer
  I need to pick and confirm winners

  Background:
    Given we have these meetups in the system
      | id | title     | date       |
      | 1  | Meetup #1 | 2017-01-19 |
    And we have these talks in the system
      | id | title             | eventId |
      | 10 | Talk on meetup #1 | 1       |
    And we have these users in the system
      | id | username | displayName |
      | 1  | User1    | User 1      |
      | 2  | User2    | User 2      |
    And we have each user commenting on each talk
    And organizer picks to raffle meetups: "1"

  Scenario: After marking a winner, number of eligible comments will drop to one
    When I pick a winner
    And I confirm him or her as a winner
    Then we should have 1 eligible comment for next prize

  Scenario: After marking both members as winners, there are no more comments to raffle
    When I pick a winner
    And I confirm him or her as a winner
    And I pick another winner
    And I confirm him or her as a winner
    Then we should have 0 eligible comment for next prize

  Scenario: After marking both members as winners, raffle can not be continued
    When I pick a winner
    And I confirm him or her as a winner
    And I pick another winner
    And I confirm him or her as a winner
    Then we cannot continue raffling
