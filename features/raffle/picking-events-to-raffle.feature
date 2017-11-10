@raffle @api @web
Feature:
  In order to give away cool prizes to our members that leave feedback
  As an organizer
  I need to create raffle by first selecting which meetups are eligible

  Scenario: Organizer can pick just one meetup to include in raffle
    Given we have these meetups in the system
      | id | title     | date       |
      | 1  | Meetup #1 | 2017-01-19 |
      | 2  | Meetup #2 | 2017-02-19 |
    When organizer picks to raffle meetups: "2"
    Then there should be 1 events on the raffle

  Scenario: Organizer can pick multiple meetups to include in raffle
    Given we have these meetups in the system
      | id | title     | date       |
      | 1  | Meetup #1 | 2017-01-19 |
      | 2  | Meetup #2 | 2017-02-19 |
      | 3  | Meetup #3 | 2017-03-19 |
    When organizer picks to raffle meetups: "1,3"
    Then there should be 2 events on the raffle
