<?php

declare(strict_types=1);

use SavinMikhail\CommentsDensity\AnalyzeComments\Comments\DocBlockComment;
use SavinMikhail\CommentsDensity\AnalyzeComments\Comments\FixMeComment;
use SavinMikhail\CommentsDensity\AnalyzeComments\Comments\LicenseComment;
use SavinMikhail\CommentsDensity\AnalyzeComments\Comments\MissingDocBlock;
use SavinMikhail\CommentsDensity\AnalyzeComments\Comments\RegularComment;
use SavinMikhail\CommentsDensity\AnalyzeComments\Comments\TodoComment;
use SavinMikhail\CommentsDensity\AnalyzeComments\Config\DTO\Config;

return new Config(
    directories: [
        'src',
    ],
    thresholds: [
        TodoComment::NAME => 0,
        FixMeComment::NAME => 0,
    ],
    cacheDir: 'var/comments-density',
    disable: [
        DocBlockComment::NAME,
        RegularComment::NAME,
        LicenseComment::NAME,
        MissingDocBlock::NAME,
    ]
);