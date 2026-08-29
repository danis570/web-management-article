<?php

$startMemoryUsage = memory_get_usage();

$dumpData = [];

for ($i = 1; $i < 1000000; $i++) {
    $dumpData[] = [$i];
}

$endMemoryUsage = memory_get_usage();
echo $endMemoryUsage - $startMemoryUsage;