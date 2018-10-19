<?php

use Symfony\Component\Console\Application;
use App\Command\LoadCommand;
use App\Command\PickOneCommand;
use App\Command\AddUsersCommand;

/*
 * Create a console application
 */
$application = new Application();

/*
 * Add the commands.
 */
$application->add(new LoadCommand());
$application->add(new PickOneCommand());
$application->add(new AddUsersCommand());

/*
 * Run the application.
 */
$application->run();
