<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Array_\CallableThisArrayToAnonymousFunctionRector;
use Rector\Config\RectorConfig;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
  ->withPhpVersion(PhpVersion::PHP_74)
  ->withPaths([
    __DIR__ . '/app',
  ])
  ->withPreparedSets(
    deadCode: true,
    codeQuality: true,
    typeDeclarations: true,
    strictBooleans: true,
  )
  ->withSkip([
    CallableThisArrayToAnonymousFunctionRector::class
  ]);
