<?php

use Symfony\Component\Console\Application;
use App\Command\LoadCommand;

/*
 * Create a console application
 */
$application = new Application();

/*
 * Add the commands.
 */
$application->add(new LoadCommand());

/*
 * Run the application.
 */
$application->run();
