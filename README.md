# joindin-raffler-frontend
Simple website to run the raffler



## Local development setup

Requirements:

* [Docker](https://docs.docker.com/engine/installation/)

 
 
Set up (using docker):
 - run ```docker-compose up -d``` which will pull the needed docker images and run the container
 - add to your /etc/hosts file:
 ```
 127.0.0.1	dev.raffler.loc
 127.0.0.1	test.raffler.loc
 ```
 
 
Run raffler web:
 - enter `workspace` container (idea is to run all of the CLI from in there) by entering `docker-compose exec workspace bash` and run:
   - run `composer install` to set up composer dependencies
   - run `refresh-dev-db` to set up the dev database
   - run `refresh-test-db` to set up the test database
 - open your favourite browser, go to `http://dev.raffler.loc:8000` to access dev environment and `http://test.raffler.loc:8000` to access test environment
        
To run (from workspace container)
 - phpunit: `phpunit`
 - phpspec: `phpspec run`
 - behat: `behat`
 - fix all the code style issues: `fix-codestandards`
 - tests: `test` (this includes phpunit, phpspec, behat & fix-codestandards tasks)
