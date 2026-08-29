<?php

$startCodeExecute = microtime(true);

for ($i = 1; $i < 500; $i++) {
    echo "looping ke $i" . PHP_EOL;
}

$endCodeExecute = microtime(true);

echo $endCodeExecute - $startCodeExecute . PHP_EOL;
