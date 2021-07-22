# Info

This projects implements a custom form module which creates a simple bank cheque on submission with the input values.

It is a Drupal 8 project and using Bootstrap.

# Installation

 - Drupal installation details can be found at https://github.com/drupal-composer/drupal-project

 - This project is using ddev. https://github.com/drud/ddev

Requirements:

- Composer
- Docker
- Ddev
    ```
    brew install drud/ddev/ddev
    ```
- Make sure Docker is up and running.


- Clone this repository to where you would like to setup your project.
- `cd` into the directory.
- Run `ddev start`
- Run `ddev composer install`
- Now you should be able to see a log similar to this:
    ```
    Project can be reached at http://bankcheque.ddev.site http://127.0.0.1:52627 
    ```
- Login with the details below:
    ```
    username: admin
    password: admin
    ```