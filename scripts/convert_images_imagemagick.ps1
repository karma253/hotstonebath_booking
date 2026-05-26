# ImageMagick batch conversion to WebP (PowerShell)
# Usage: Open PowerShell, cd to project root, then: .\scripts\convert_images_imagemagick.ps1
# Requires ImageMagick (magick) available in PATH

$srcDir = "public/image"
$dstDir = "public/image/optimized"

if (-not (Test-Path $dstDir)) { New-Item -ItemType Directory -Path $dstDir | Out-Null }

Get-ChildItem -Path $srcDir -Include *.jpg,*.jpeg,*.png -File -Recurse | ForEach-Object {
    $file = $_
    $base = [System.IO.Path]::GetFileNameWithoutExtension($file.Name)
    $ext = $file.Extension.ToLower()

    # generate three sizes
    $sizes = @(1600,1200,800)
    foreach ($w in $sizes) {
        $out = Join-Path $dstDir ("{0}-{1}.webp" -f $base, $w)
        Write-Host "Converting $($file.Name) -> $([System.IO.Path]::GetFileName($out)) ($w px)"
        magick convert "$($file.FullName)" -strip -resize ${w}x -quality 80 -define webp:method=6 "$out"
    }
}

Write-Host "Done. Optimized WebP files written to $dstDir"