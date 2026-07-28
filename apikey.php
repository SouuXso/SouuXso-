<?php
global $config;
$apiKey = $config['api_key_default'] ?? '';

echo "\n\033[38;2;255;121;198m╔══════════════════════════════════════════════════════╗\033[0m\n";
echo "\033[38;2;255;121;198m║  🔑  API KEY – Gunakan kunci akses                   ║\033[0m\n";
echo "\033[38;2;255;121;198m╚══════════════════════════════════════════════════════╝\033[0m\n\n";

echo "\033[38;2;98;114;164m> API Key saat ini: \033[0m" . substr($apiKey, 0, 6) . '******' . "\n";
echo "\033[38;2;139;233;253m> Masukkan endpoint (atau 'ganti' untuk ubah key, 'back'): \033[0m";
$input = trim(fgets(STDIN));

if ($input === 'back') {
    header('Location: ?page=apikey');
    exit;
}
if ($input === 'ganti') {
    echo "\033[38;2;80;250;123mMasukkan API Key baru: \033[0m";
    $newKey = trim(fgets(STDIN));
    if (!empty($newKey)) {
        $config['api_key_default'] = $newKey;
        file_put_contents('config.json', json_encode($config, JSON_PRETTY_PRINT));
        echo "\033[38;2;80;250;123m[✓] API Key diperbarui.\033[0m\n";
    }
    exit;
}
if (!empty($input)) {
    loadingDots("Memproses dengan API key", 1.8);
    echo "\033[38;2;80;250;123m[✓] Hasil untuk '{$input}' dengan key {$apiKey}: \033[0m\n";
    echo "  Data dari endpoint (simulasi)\n";
}
