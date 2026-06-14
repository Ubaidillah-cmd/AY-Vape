<?php
// ============================================================
//  config/telegram.php
//  Konfigurasi Bot Telegram AY Vape
// ============================================================
//
//  CARA SETUP:
//  1. Buka Telegram → cari @BotFather
//  2. Ketik /newbot → ikuti instruksi → dapat BOT_TOKEN
//  3. Buka @userinfobot → dapat CHAT_ID kamu
//  4. Isi BOT_TOKEN dan CHAT_ID di bawah
//  5. Simpan file ini
//
// ============================================================

define('TG_BOT_TOKEN', '8480690249:AAEFmAAmSP9bfjQc7gOTWRPtlUt1fuEcnSM');
// Contoh: '7123456789:AAHxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'

define('TG_CHAT_ID', '78861626616');
// Contoh: '123456789'
// Bisa juga pakai username grup: '@nama_grup'

// URL Dashboard Admin (untuk tombol di notif)
define('ADMIN_URL', 'https://ay-vape.gt.tc/panel');

// Nama toko
define('TOKO_NAME', 'AY Vape');

// ============================================================
//  FUNGSI KIRIM NOTIF TELEGRAM
// ============================================================

/**
 * Kirim pesan teks ke Telegram
 *
 * @param string $text     Pesan (mendukung HTML)
 * @param array  $buttons  Tombol inline (opsional)
 *                         Format: [['text' => 'Label', 'url' => 'https://...']]
 */
function sendTelegram(string $text, array $buttons = []): bool
{
    $token = TG_BOT_TOKEN;
    $chatId = TG_CHAT_ID;

    if ($token === 'ISI_BOT_TOKEN_KAMU_DISINI') return false; // belum diisi

    $payload = [
        'chat_id'    => $chatId,
        'text'       => $text,
        'parse_mode' => 'HTML',
    ];

    // Tambah inline keyboard jika ada tombol
    if (!empty($buttons)) {
        $keyboard = [];
        foreach ($buttons as $btn) {
            $keyboard[] = [['text' => $btn['text'], 'url' => $btn['url']]];
        }
        $payload['reply_markup'] = json_encode(['inline_keyboard' => $keyboard]);
    }

    $url = "https://api.telegram.org/bot{$token}/sendMessage";

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $result = curl_exec($ch);
    $err    = curl_errno($ch);
    curl_close($ch);

    return $err === 0;
}

/**
 * Kirim foto ke Telegram (untuk bukti bayar, dsb)
 *
 * @param string $photoPath  Path file foto di server
 * @param string $caption    Caption foto (mendukung HTML)
 */
function sendTelegramPhoto(string $photoPath, string $caption = ''): bool
{
    $token  = TG_BOT_TOKEN;
    $chatId = TG_CHAT_ID;

    if ($token === 'ISI_BOT_TOKEN_KAMU_DISINI') return false;
    if (!file_exists($photoPath)) return false;

    $url = "https://api.telegram.org/bot{$token}/sendPhoto";

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => [
            'chat_id'    => $chatId,
            'photo'      => new CURLFile($photoPath),
            'caption'    => $caption,
            'parse_mode' => 'HTML',
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);

    $result = curl_exec($ch);
    $err    = curl_errno($ch);
    curl_close($ch);

    return $err === 0;
}