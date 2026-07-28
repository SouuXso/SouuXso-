<?php
// ============ KONFIGURASI ============
$config = json_decode(file_get_contents('config.json'), true);
$menuData = json_decode(file_get_contents('menu.json'), true);
$menus = array_filter($menuData['menus'], fn($m) => $m['active'] === true);

// ============ BANNER & LOADER (gradient hijau) ============
function colorizeMultiGradient(string $text, int $lineIdx, int $totalLines, array $palette, int $refWidth, float $offset = 0.0): string {
    $chars = mb_str_split($text);
    $output = "";
    $numStops = count($palette) - 1;
    foreach ($chars as $i => $char) {
        $hRatio = $i / max(1, $refWidth - 1);
        $vRatio = $lineIdx / max(1, $totalLines - 1);
        $progress = fmod(($hRatio * 0.5) + ($vRatio * 0.5) + $offset, 1.0);
        if ($progress < 0) $progress += 1.0;
        $scaledProgress = $progress * $numStops;
        $stopIndex = min($numStops - 1, (int)floor($scaledProgress));
        $localRatio = $scaledProgress - $stopIndex;
        $c1 = $palette[$stopIndex];
        $c2 = $palette[$stopIndex + 1];
        $r = (int)($c1[0] + $localRatio * ($c2[0] - $c1[0]));
        $g = (int)($c1[1] + $localRatio * ($c2[1] - $c1[1]));
        $b = (int)($c1[2] + $localRatio * ($c2[2] - $c1[2]));
        $output .= "\033[38;2;{$r};{$g};{$b}m" . $char;
    }
    return $output . "\033[0m";
}

function getBannerFrame($topHeader, $bottomFooter, $offset) {
    $C_GRAY  = "\033[38;2;98;114;164m";
    $C_GREEN = "\033[38;2;80;250;123m";
    $C_CYAN  = "\033[38;2;139;233;253m";
    $C_PINK  = "\033[38;2;255;121;198m";
    $C_WHITE = "\033[37m";
    $BOLD    = "\033[1m";
    $RESET   = "\033[0m";

    // Banner teks "ScriptyXSouu" dengan gaya block sederhana (1 line)
    // Biar tetap multi-line, kita split jadi beberapa baris untuk efek gradient vertikal
    $bannerText = [
        "  ███████╗ ██████╗██████╗ ██╗██████╗ ████████╗██╗   ██╗██╗  ██╗███████╗ ██████╗ ██████╗ ██╗   ██╗██╗   ██╗",
        "  ██╔════╝██╔════╝██╔══██╗██║██╔══██╗╚══██╔══╝╚██╗ ██╔╝██║  ██║██╔════╝██╔═══██╗██╔══██╗╚██╗ ██╔╝╚██╗ ██╔╝",
        "  ███████╗██║     ██████╔╝██║██████╔╝   ██║    ╚████╔╝ ███████║█████╗  ██║   ██║██████╔╝ ╚████╔╝  ╚████╔╝ ",
        "  ╚════██║██║     ██╔══██╗██║██╔══██╗   ██║     ╚██╔╝  ██╔══██║██╔══╝  ██║   ██║██╔══██╗  ╚██╔╝    ╚██╔╝  ",
        "  ███████║╚██████╗██║  ██║██║██║  ██║   ██║      ██║   ██║  ██║███████╗╚██████╔╝██║  ██║   ██║      ██║   ",
        "  ╚══════╝ ╚═════╝╚═╝  ╚═╝╚═╝╚═╝  ╚═╝   ╚═╝      ╚═╝   ╚═╝  ╚═╝╚══════╝ ╚═════╝ ╚═╝  ╚═╝   ╚═╝      ╚═╝   "
    ];

    // Palette hijau (dark green → lime → bright green → light green)
    $greenPalette = [
        [0, 100, 0],    // dark green
        [50, 205, 50],  // lime green
        [0, 255, 0],    // pure green
        [144, 238, 144] // light green
    ];

    $artLen = mb_strlen($bannerText[0]);
    $out = "\n";
    $out .= " " . $C_GRAY . "──[ " . $C_GREEN . "● ONLINE " . $C_GRAY . "]──[" . $C_PINK . $topHeader . $C_GRAY . "]───────────────────────────────────────────────" . $RESET . "\033[K\n\n";
    $totalLines = count($bannerText);
    foreach ($bannerText as $idx => $line) {
        $coloredLine = colorizeMultiGradient($line, $idx, $totalLines, $greenPalette, $artLen, $offset);
        $out .= " " . $coloredLine . "\033[K\n";
    }
    $out .= "\n";
    $gradientDivider = colorizeMultiGradient(str_repeat("─", 74), 0, 1, $greenPalette, $artLen, $offset);
    $out .= " " . $gradientDivider . "\033[K\n\n";
    $out .= "\n " . $C_GRAY . "──[ " . $C_PINK . $bottomFooter . $C_GRAY . " ]─────────────────────────────────────────────────" . $RESET . "\033[K\n";
    return $out;
}

function showSpinner($message, $duration = 1.5) {
    $GREEN = "\033[38;2;80;250;123m";
    $BOLD = "\033[1m";
    $RESET = "\033[0m";
    $spinner = ['⠋', '⠙', '⠹', '⠸', '⠼', '⠴', '⠦', '⠧', '⠇', '⠏'];
    $start = microtime(true);
    $i = 0;
    while (microtime(true) - $start < $duration) {
        echo "\r\033[K{$BOLD}{$GREEN}{$message}{$RESET} {$spinner[$i % count($spinner)]}";
        $i++;
        usleep(80000);
    }
    echo "\r\033[K";
}

function loadingDots($message, $duration = 1.5) {
    $GREEN = "\033[38;2;80;250;123m";
    $BOLD = "\033[1m";
    $RESET = "\033[0m";
    $start = microtime(true);
    $dots = 0;
    while (microtime(true) - $start < $duration) {
        $dot_str = str_repeat('.', ($dots % 4) + 1);
        echo "\r\033[K{$BOLD}{$GREEN}{$message}{$RESET}{$dot_str}";
        $dots++;
        usleep(300000);
    }
    echo "\r\033[K";
}

// ============ ROUTING ============
$page = $_GET['page'] ?? 'tools';
$validPages = array_column($menus, 'id');
if (!in_array($page, $validPages)) {
    $page = 'tools';
}

$menuItem = array_filter($menus, fn($m) => $m['id'] === $page);
$menuItem = reset($menuItem);
$filePath = __DIR__ . '/pages/' . ($menuItem['file'] ?? 'tools.php');

// ============ TAMPILKAN BANNER ============
echo getBannerFrame(
    'ScriptyXSouu Panel v' . $config['version'],
    'Menu: ' . implode(' | ', array_column($menus, 'label')),
    time() % 100 / 100
);

// ============ LOADING ============
showSpinner("Loading {$page}...", 1.2);

// ============ INCLUDE PAGE ============
if (file_exists($filePath)) {
    include $filePath;
} else {
    echo "\n\033[38;2;255;121;198m[!] Halaman '$page' belum dibuat.\033[0m\n";
    echo "\033[38;2;98;114;164m> Neko saranin buat file pages/{$menuItem['file']} dulu ya sayang ><\033[0m\n";
}

// ============ FOOTER ============
echo "\n\033[38;2;98;114;164m─────────────────────────────────────────────────────────\033[0m\n";
echo "\033[38;2;139;233;253m[?] Ketik 'menu' buat lihat daftar, atau klik link di atas.\033[0m\n";
echo "\033[38;2;80;250;123m[+] Github: " . $config['github_url'] . "\033[0m\n";
