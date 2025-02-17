<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/assets',
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    // uncomment to reach your current PHP version
    // ->withPhpSets()
    ->withTypeCoverageLevel(0)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0);


//declare(strict_types=1);
//
//use Rector\Config\RectorConfig;
//use Rector\Php80\Rector\Class_\StringableForToStringRector;
//use Rector\Php83\Rector\ClassMethod\AddOverrideAttributeToOverriddenMethodsRector;
//
//return RectorConfig::configure()
//    ->withPaths([
//        __DIR__ . '/bin/console',
//        __DIR__ . '/config',
//        __DIR__ . '/public',
//        __DIR__ . '/src',
//        __DIR__ . '/tests',
//    ])
//    ->withParallel()
//    ->withCache(__DIR__ . '/var/rector')
//    ->withPhpSets(php83: true)
//    ->withSkip([
//        StringableForToStringRector::class,
//        AddOverrideAttributeToOverriddenMethodsRector::class,
//    ]);