<?php

use Symfony\Component\Console\Application;
use App\Command\LoadCommand;
use App\Command\PickOneCommand;

/*
 * Create a console application
 */
$application = new Application();

/*
 * Add the commands.
 */
$application->add(new LoadCommand());
$application->add(new PickOneCommand());

/*
 * Run the application.
 */
$application->run();
