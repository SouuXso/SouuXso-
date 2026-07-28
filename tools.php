<?php
echo "\n\033[38;2;255;121;198m╔══════════════════════════════════════════════════════╗\033[0m\n";
echo "\033[38;2;255;121;198m║  🔧  TOOLS – Pilih tool yang mau dijalankan          ║\033[0m\n";
echo "\033[38;2;255;121;198m╚══════════════════════════════════════════════════════╝\033[0m\n\n";

$tools = [
    '1' => ['name' => 'Ping Checker', 'desc' => 'Cek koneksi ke host'],
    '2' => ['name' => 'Port Scanner', 'desc' => 'Scan port terbuka'],
    '3' => ['name' => 'DNS Lookup', 'desc' => 'Cari record DNS'],
];

foreach ($tools as $key => $t) {
    echo "  \033[38;2;139;233;253m[{$key}]\033[0m {$t['name']} – {$t['desc']}\n";
}

echo "\n\033[38;2;98;114;164m> Masukkan nomor tool (atau ketik 'back'): \033[0m";
$input = trim(fgets(STDIN));
if ($input === 'back') {
    header('Location: ?page=tools');
    exit;
}
if (isset($tools[$input])) {
    echo "\n\033[38;2;80;250;123m[+] Menjalankan {$tools[$input]['name']}...\033[0m\n";
    // Simulasi
    showSpinner("Proses", 2);
    echo "\033[38;2;80;250;123m[✓] Selesai.\033[0m\n";
} else {
    echo "\033[38;2;255;121;198m[!] Pilihan tidak valid.\033[0m\n";
}
