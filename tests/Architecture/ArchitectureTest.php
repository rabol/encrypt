<?php


declare(strict_types=1);

arch()
    ->expect('App')
    ->toUseStrictTypes()
    ->not->toUse(['die', 'dd', 'dump', 'ray']);

arch()
    ->preset()
    ->laravel();
