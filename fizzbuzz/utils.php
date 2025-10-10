<?php

const FIZZ = 'FIZZ';
const BUZZ = 'BUZZ';

function fizzbuzz(int $num): string|int
{
    return match (true) {
        $num % 15 === 0 => FIZZ . BUZZ,
        $num % 5 === 0 => BUZZ,
        $num % 3 === 0 => FIZZ,
        default => $num,
    };
}
