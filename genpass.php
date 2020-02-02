<?php
declare(strict_types = 1);
const DEFAULT_PASSWORD_LENGTH=18;
/** @var string[] $argv */
$args = $argv ?? [];
unset($args[0]);
$args = array_values($args);
$argc = count($args);
if ($argc === 0) {
    echo generatePassword(DEFAULT_PASSWORD_LENGTH);
    echo PHP_EOL;
    die(0);
} elseif ($argc === 1) {
    $len = $olen = trim($args[0]);
    $len = filter_var($len, FILTER_VALIDATE_INT);
    if ($len === false) {
        echo "error: could not parse argument as int: ";
        var_dump($olen);
        die(1);
    }
    if ($len < 0) {
        echo "error: password length cannot be smaller than 0...";
        echo PHP_EOL;
        die(1);
    }
    echo generatePassword($len);
    echo PHP_EOL;
    die(0);
} else {
    echo "error: only 0-1 arguments supported: password length.";
    echo PHP_EOL;
    die(1);
}

function generatePassword(int $len = 18): string
{
    if ($len < 0) {
        throw new \InvalidArgumentException("len must be >=0");
    }
    $dict = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_';
    $problematic = "1IloO0-_"; // 5S?
    $dict = preg_replace('/[' . preg_quote($problematic, '/') . ']/', "", $dict);
    $randmax = strlen($dict) - 1;
    $ret = '';
    for ($i = 0; $i < $len; ++ $i) {
        $ret .= $dict[random_int(0, $randmax)];
    }
    return $ret;
    // return substr ( strtr ( base64_encode ( random_bytes ( $len ) ), '+/', '-_' ), 0, $len );
}
