<?php

use Symfony\Component\Console\Application;
use App\Command\DefaultCommand;

/*
 * Create a console application
 */
$application = new Application();

/*
 * Add the commands.
 */
$application->add(new DefaultCommand());

/*
 * Run the application.
 */
$application->run();
