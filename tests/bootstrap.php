<?php

require __DIR__ . '/../vendor/autoload.php';

if (!class_exists('Tester\Assert')) {
    echo "Install Nette Tester using `composer update --dev`\n";
    exit(1);
}

Tester\Environment::setup();

if (extension_loaded('xdebug')) {
    xdebug_disable();
    Tester\CodeCoverage\Collector::start(__DIR__ . '/coverage.dat');
}

function id($val)
{
    return $val;
}

@mkdir("../temp");
$configurator = new Nette\Configurator;
$configurator->setDebugMode(FALSE);
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->createRobotLoader()
        ->addDirectory(__DIR__ . '/../EventCalendar')
        ->register();

return $configurator->createContainer();