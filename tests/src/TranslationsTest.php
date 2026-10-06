<?php

declare(strict_types=1);

use Symfony\Component\Finder\Finder;

it('defines every translation key the package uses', function (): void {
    $keys = collect(Finder::create()->files()->in(__DIR__ . '/../../src')->name('*.php'))
        ->flatMap(function (SplFileInfo $file): array {
            preg_match_all("/__\\('(botly::[^']+)'\\)/", $file->getContents(), $matches);

            return $matches[1];
        })
        ->unique()
        ->values();

    expect($keys)->not->toBeEmpty();

    foreach ($keys as $key) {
        expect(__($key))->not->toBe($key, "Missing translation for [{$key}].");
    }
});
