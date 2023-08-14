# Siroko code challenge

This repository is submitted as a solution to the technical challenge proposed during the selection process for the Senior Backend Developer position @ Techpump.

---

## Content

This is a **Symfony** project for a **REST API** application, with a development environment configured in **Docker**.

### Side notes

- For the sake of simplicity in the project, it is assumed that:
  - The currency will always be in Euros.
  - There is no need to keep a record of product prices.

---

## Installation

- Clone this repo: `git clone git@github.com:jgarciatorralba/siroko-code-challenge.git`
- Navigate to the `/.docker` folder, then run `docker-compose up -d` to download images and set up containers.
  - **Important**: the configuration is prepared to expose the server container's port on host's port 8000, and the database container's port on host's 6432, so make sure they are available before running the above command.
- Once completed, open with VisualStudio and in the command palette (View > Command Palette) select the option "Dev Containers: Reopen in Container".
- Inside the development container, install packages with `composer install`.
- Even though an empty database named **app_db** should have been created with the installation, you can still run `sf doctrine:database:create` for good measure.
- With the database created and the connection to the application successfully established, execute the existing migrations in folder `/etc/migrations` using the command `sf doctrine:migrations:migrate`.
- Populate the database with demo data using the command `sf doctrine:fixtures:load`.
- You can use [**this**](https://www.postman.com/jgarciatorralba/workspace/siroko-code-challenge/collection/11475793-058dbffb-9972-4f9c-9260-13700c2bf834?action=share&creator=11475793) Postman collection to facilitate the interaction. The two main routes are `"/api/products"` and `"/api/carts"`.

---

## Tests

- Run the complete test suite by executing the command: `php ./vendor/bin/pest`
  - **Important**: make sure to clear Symfony's testing cache by running `sf cache:clear --env=test` before executing the tests. Also note that the feature tests will clear the database after running, so you will have to populate it again with `sf doctrine:fixtures:load`.

---

## Scripts

- Run _Pest_ tests: `php ./vendor/bin/pest`
- Run _CodeSniffer_ analysis: `php ./vendor/bin/phpcs <filename|foldername>`
  - Correct detected coding standard violations: `php ./vendor/bin/phpcbf <filename|foldername>`
- Run _PHPStan_ analysis: `php ./vendor/bin/phpstan analyse <foldernames>`
- Delete existing database: `sf doctrine:database:drop --force`

---

## Author

- **Jorge García Torralba** &#8594; [jorge-garcia](https://github.com/jgarciatorralba)
