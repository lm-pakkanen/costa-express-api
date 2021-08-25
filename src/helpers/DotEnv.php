<?php

namespace Src\helpers;

use Error;

class DotEnv {

    public static function load(string $path) {

        if (!is_readable($path)) {
            throw new Error('Could not read environment file', 500);
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach($lines as $line) {


            /**
             * Ignore comments
             */
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value ) = explode('=', $line, 2);

            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
            }

        }

    }

}