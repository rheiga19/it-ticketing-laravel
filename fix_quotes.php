<?php
$file = 'resources/views/dashboard.blade.php';
$content = file_get_contents($file);
$content = str_replace('\"', '"', $content);
file_put_contents($file, $content);
echo "Fixed all escaped quotes in dashboard.blade.php\n";
?>
