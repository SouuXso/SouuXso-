<?php
echo "\n\033[38;2;255;121;198m╔══════════════════════════════════════════════════════╗\033[0m\n";
echo "\033[38;2;255;121;198m║  🚫  NO API KEY – Bebas tanpa kunci                 ║\033[0m\n";
echo "\033[38;2;255;121;198m╚══════════════════════════════════════════════════════╝\033[0m\n\n";

echo "\033[38;2;139;233;253m> Mode ini tidak butuh API key. Semua request langsung.\033[0m\n";
echo "\033[38;2;98;114;164m> Contoh: cek cuaca, hitung matematika, dll.\033[0m\n\n";

echo "\033[38;2;80;250;123mMasukkan perintah (atau 'back'): \033[0m";
$cmd = trim(fgets(STDIN));
if ($cmd === 'back') {
    header('Location: ?page=noapikey');
    exit;
}
if (!empty($cmd)) {
    loadingDots("Memproses tanpa API key", 1.8);
    echo "\033[38;2;80;250;123m[✓] Hasil untuk '{$cmd}': \033[0m\n";
    echo "  " . strtoupper($cmd) . " -> OK (no key)\n";
}
