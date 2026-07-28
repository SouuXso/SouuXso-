<?php
global $config;
$botToken = $config['telegram_bot_token'] ?? '';
$chatId = $config['telegram_chat_id'] ?? '';

echo "\n\033[38;2;255;121;198m╔══════════════════════════════════════════════════════╗\033[0m\n";
echo "\033[38;2;255;121;198m║  📱  TELEGRAM – Kirim pesan ke bot                   ║\033[0m\n";
echo "\033[38;2;255;121;198m╚══════════════════════════════════════════════════════╝\033[0m\n\n";

echo "\033[38;2;98;114;164m> Bot Token: \033[0m" . substr($botToken, 0, 8) . '******' . "\n";
echo "\033[38;2;98;114;164m> Chat ID  : \033[0m{$chatId}\n\n";

echo "\033[38;2;139;233;253m> Ketik pesan yang mau dikirim (atau 'back'): \033[0m";
$msg = trim(fgets(STDIN));
if ($msg === 'back') {
    header('Location: ?page=telegram');
    exit;
}
if (!empty($msg) && !empty($botToken) && !empty($chatId)) {
    loadingDots("Mengirim ke Telegram", 2);
    $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
    $data = ['chat_id' => $chatId, 'text' => $msg];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $res = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($httpCode == 200) {
        echo "\033[38;2;80;250;123m[✓] Pesan terkirim!\033[0m\n";
    } else {
        echo "\033[38;2;255;121;198m[!] Gagal kirim. Cek token/chat ID.\033[0m\n";
    }
} else {
    echo "\033[38;2;255;121;198m[!] Token atau Chat ID kosong, atau pesan kosong.\033[0m\n";
}
