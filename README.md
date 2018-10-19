# Sample Console Application

This is a skeleton version of the Symfony Console application.

## Running the console

The console can be started with by running the PHP console file.

```
$ php console
```

## PSR-2

The application is PSR-2 compliant and comes with an included Code sniffer

```
php vendor/bin/phpcs
```

## Makefile

The Makefile is meant to be used only on development to facilitate
the job of testing each command and linting.

## Commands

There are 3 commands currently available:

- `php console load <input csv filename> <output json filename>`:
  Load all users from a given CSV file.

- `php console pick-one <input json filename> [ --country-code=## ]`:
  Randomly select a single user; Optionally filter by country

- `php console add-user <input json filename>`:
  Open an interactive session allowing new users to be inserted on the system.

