# Siroko code challenge

This repository is submitted as a solution to the technical challenge proposed during the selection process for the Senior Backend Developer position @ Techpump.

## Content

This is a **Symfony** project for a **REST API** application, with a development environment configured in **Docker**.

### Side notes

- For the simplicity of the project, it is assumed that prices will always be in Euros and that there is no need to keep a record of product prices.

---

## Installation

- Clone this repo: `git@github.com:jgarciatorralba/siroko-code-challenge.git`
- Navigate to the `/.docker` folder, then run `docker-compose up -d` to download images and set up containers.
  - **Important**: the configuration is set to expose the server container's port on host's port 8000, and the database container's port on host's 6432, so make sure they are available before running the above command.
- Once completed, open with VisualStudio and in the command palette (View > Command Palette) select the option "Dev Containers: Reopen in Container".
- Inside the development container, install packages with `composer install`.
- Even though an empty database named **app_db** should have been created with the installation, you can still run `php bin/console doctrine:database:create` for good measure.
- With the database created and the connection to the application successfully established, execute the existing migrations in folder `/etc/migrations` using the command `php bin/console doctrine:migrations:migrate`.

---

## Scripts

- Run _Pest_ tests: `php ./vendor/bin/pest`
- Run _CodeSniffer_ analysis: `php ./vendor/bin/phpcs <filename|foldername>`
- Correct previously detected coding standard violations: `php ./vendor/bin/phpcbf <filename|foldername>`
- Run _PHPStan_ analysis: `php ./vendor/bin/phpstan analyse <foldernames>`

---

## Author

- **Jorge García Torralba** &#8594; [jorge-garcia](https://github.com/jgarciatorralba)
