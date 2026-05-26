# cwebp batch conversion to WebP (PowerShell)
# Usage: Install libwebp and ensure cwebp is in PATH. Run from project root: .\scripts\convert_images_cwebp.ps1

$srcDir = "public/image"
$dstDir = "public/image/optimized"

if (-not (Test-Path $dstDir)) { New-Item -ItemType Directory -Path $dstDir | Out-Null }

Get-ChildItem -Path $srcDir -Include *.jpg,*.jpeg,*.png -File -Recurse | ForEach-Object {
    $file = $_
    $base = [System.IO.Path]::GetFileNameWithoutExtension($file.Name)

    $sizes = @(1600,1200,800)
    foreach ($w in $sizes) {
        $out = Join-Path $dstDir ("{0}-{1}.webp" -f $base, $w)
        Write-Host "Converting $($file.Name) -> $([System.IO.Path]::GetFileName($out)) ($w px)"
        # Resize using ImageMagick 'magick' if available, otherwise rely on -resize via ffmpeg/convert is preferred. Here we use magick if present, else copy original then cwebp will compress.
        if (Get-Command magick -ErrorAction SilentlyContinue) {
            $tmp = [System.IO.Path]::GetTempFileName() + ".jpg"
            magick convert "$($file.FullName)" -resize ${w}x "$tmp"
            cwebp -q 80 "$tmp" -o "$out" | Out-Null
            Remove-Item $tmp -ErrorAction SilentlyContinue
        } else {
            # fallback: try to use cwebp directly (no resize)
            cwebp -resize $w 0 -q 80 "$($file.FullName)" -o "$out" | Out-Null
        }
    }
}

Write-Host "Done. Optimized WebP files written to $dstDir"