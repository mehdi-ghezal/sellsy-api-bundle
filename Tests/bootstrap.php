<?php

date_default_timezone_set('Europe/Paris');

require_once __DIR__ . '/../vendor/autoload.php';

/** @noinspection PhpUndefinedConstantInspection */
if (! SELLSY_CONSUMER_TOKEN || ! SELLSY_CONSUMER_SECRET || ! SELLSY_USER_TOKEN || ! SELLSY_USER_SECRET) {
    throw new \RuntimeException('You must fill out the credential on phpunit.xml file');
}