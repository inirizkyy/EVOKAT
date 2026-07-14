<?php
// Ensure directories exist
@mkdir('public/mock', 0777, true);

// Create a valid tiny PNG image
$img = imagecreatetruecolor(100, 100);
$bg = imagecolorallocate($img, 149, 29, 24); // brand color
imagefill($img, 0, 0, $bg);
imagepng($img, 'public/mock/foto.png');
imagedestroy($img);

// Create a valid mock PDF content
$pdf_content = "%PDF-1.5\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] >>\nendobj\nxref\n0 4\n0000000000 65535 f\n0000000009 00000 n\n0000000056 00000 n\n0000000111 00000 n\ntrailer\n<< /Size 4 /Root 1 0 R >>\nstartxref\n180\n%%EOF";
file_put_contents('public/mock/persyaratan.pdf', $pdf_content);

echo "SUCCESS: Created public/mock/foto.png and public/mock/persyaratan.pdf" . PHP_EOL;
