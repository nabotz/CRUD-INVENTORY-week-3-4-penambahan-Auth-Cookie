<?php

/**
 * Proses upload gambar: resize otomatis (maks 1024x768) dan buat
 * thumbnail 150x150 center-crop di subfolder thumbs/.
 * File selalu disimpan sebagai JPEG untuk konsistensi.
 *
 * @param array  $file         Elemen $_FILES yang sudah divalidasi
 * @param string $folderUpload Path folder tujuan (diakhiri '/')
 * @return string|null         Nama file baru, null jika GD gagal
 */
function proses_gambar(array $file, string $folderUpload): ?string
{
    $folderUpload = rtrim($folderUpload, '/') . '/';
    $folderThumb  = $folderUpload . 'thumbs/';

    if (!file_exists($folderUpload)) mkdir($folderUpload, 0755, true);
    if (!file_exists($folderThumb))  mkdir($folderThumb,  0755, true);

    $tmpFile = $file['tmp_name'];
    $mime    = mime_content_type($tmpFile);

    $source = match ($mime) {
        'image/jpeg' => imagecreatefromjpeg($tmpFile),
        'image/png'  => imagecreatefrompng($tmpFile),
        'image/gif'  => imagecreatefromgif($tmpFile),
        default      => null,
    };
    if (!$source) return null;

    [$origW, $origH] = getimagesize($tmpFile);

    // Ratakan transparansi PNG/GIF ke latar putih agar bisa disimpan JPEG
    if ($mime === 'image/png' || $mime === 'image/gif') {
        $flat  = imagecreatetruecolor($origW, $origH);
        $white = imagecolorallocate($flat, 255, 255, 255);
        imagefill($flat, 0, 0, $white);
        imagecopy($flat, $source, 0, 0, 0, 0, $origW, $origH);
        imagedestroy($source);
        $source = $flat;
    }

    $namaFoto  = uniqid('foto_') . '.jpg';
    $targetPath = $folderUpload . $namaFoto;

    // Resize otomatis jika melebihi batas maksimal
    $maxW = 1024;
    $maxH = 768;
    if ($origW > $maxW || $origH > $maxH) {
        $scale  = min($maxW / $origW, $maxH / $origH);
        $newW   = (int) floor($origW * $scale);
        $newH   = (int) floor($origH * $scale);
        $canvas = imagecreatetruecolor($newW, $newH);
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        imagejpeg($canvas, $targetPath, 90);
        imagedestroy($canvas);
    } else {
        imagejpeg($source, $targetPath, 90);
    }

    // Buat thumbnail 150x150 (center crop persegi)
    $thumbW = $thumbH = 150;
    $thumb  = imagecreatetruecolor($thumbW, $thumbH);

    if ($origW >= $origH) {
        $cropSize = $origH;
        $startX   = (int)(($origW - $cropSize) / 2);
        $startY   = 0;
    } else {
        $cropSize = $origW;
        $startX   = 0;
        $startY   = (int)(($origH - $cropSize) / 2);
    }

    imagecopyresampled($thumb, $source, 0, 0, $startX, $startY, $thumbW, $thumbH, $cropSize, $cropSize);
    imagejpeg($thumb, $folderThumb . $namaFoto, 85);
    imagedestroy($thumb);
    imagedestroy($source);

    return $namaFoto;
}

/**
 * Hapus gambar asli dan thumbnail-nya.
 */
function hapus_gambar(string $namaFoto, string $folderUpload): void
{
    if (empty($namaFoto)) return;
    $folder = rtrim($folderUpload, '/') . '/';
    $files  = [$folder . $namaFoto, $folder . 'thumbs/' . $namaFoto];
    foreach ($files as $f) {
        if (file_exists($f)) unlink($f);
    }
}
