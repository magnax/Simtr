# features/search.feature
Feature: Search
  In order to login to page
  As a website user
  I need to be able to go to login page

  Scenario: Going to login page
    Given I am on "/"
    When I follow "Logowanie"
    Then I should see "LOGOWANIE DO GRY"

  Scenario: Unsuccesfull login
    Given I am on "/login"
    When I press "Zaloguj"
    Then I should see "Login failed, try again"

  Scenario: Succesfull login
    Given I am on "/login"
    When I fill in "email" with "magnax@gmail.com"
    And I fill in "password" with "amanda"
    And I press "Zaloguj"
    Then I should see "POSTACIE"