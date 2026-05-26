<?php
// Simple image converter to WebP with resized variants.
$root = __DIR__ . '/../public/image';
$optimized = $root . '/optimized';
if (!is_dir($optimized)) mkdir($optimized, 0755, true);
$images = [
    'Six Senses Thimphu.jpg',
    'hot stone bath.png',
    'Herbal Steam Bath.png',
    'medicinal water.png'
];
$sizes = [800, 1200, 1600];

function load_image($path) {
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if ($ext === 'jpg' || $ext === 'jpeg') return imagecreatefromjpeg($path);
    if ($ext === 'png') return imagecreatefrompng($path);
    return false;
}

foreach ($images as $img) {
    $src = $root . '/' . $img;
    if (!file_exists($src)) {
        echo "missing: $src\n";
        continue;
    }
    $im = load_image($src);
    if (!$im) { echo "cannot load $src\n"; continue; }
    $w = imagesx($im);
    $h = imagesy($im);
    foreach ($sizes as $size) {
        $newW = $size;
        $newH = (int) round($h * ($newW / $w));
        $dst = imagecreatetruecolor($newW, $newH);
        // preserve PNG transparency
        $ext = strtolower(pathinfo($src, PATHINFO_EXTENSION));
        if ($ext === 'png') {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
            imagefilledrectangle($dst, 0, 0, $newW, $newH, $transparent);
        } else {
            $bg = imagecolorallocate($dst, 255, 255, 255);
            imagefilledrectangle($dst, 0, 0, $newW, $newH, $bg);
        }
        imagecopyresampled($dst, $im, 0,0,0,0, $newW, $newH, $w, $h);
        $safe = str_replace([' ', '%'], ['%20','%25'], $img);
        $name = pathinfo($img, PATHINFO_FILENAME);
        $out = $optimized . '/' . $name . '-' . $newW . '.webp';
        imagewebp($dst, $out, 80);
        imagedestroy($dst);
        echo "created: $out\n";
    }
    imagedestroy($im);
}

echo "Done.\n";
