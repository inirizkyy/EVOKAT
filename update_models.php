<?php
$dir = __DIR__ . '/app/Models';
$files = scandir($dir);
foreach ($files as $file) {
    if ($file === '.' || $file === '..' || $file === 'User.php') continue;
    $path = $dir . '/' . $file;
    $content = file_get_contents($path);
    if (strpos($content, 'protected $guarded') === false) {
        $content = str_replace('use HasFactory;', "use HasFactory;\n    protected \$guarded = ['id'];", $content);
        file_put_contents($path, $content);
    }
}
echo "Models updated.";
