<?php

// ==================== ШИФР ВИЖЕНЕРА ====================

function vigenereEncrypt($text, $key) {
    $text = strtoupper(preg_replace('/[^A-Za-z]/', '', $text)); // Убираем всё, кроме букв
    $key = strtoupper(preg_replace('/[^A-Za-z]/', '', $key));
    $keyLength = strlen($key);
    $encryptedText = '';
    
    for ($i = 0; $i < strlen($text); $i++) {
        $textChar = ord($text[$i]) - 65;
        $keyChar = ord($key[$i % $keyLength]) - 65;
        $encryptedChar = ($textChar + $keyChar) % 26;
        $encryptedText .= chr($encryptedChar + 65);
    }
    
    return $encryptedText;
}

function vigenereDecrypt($text, $key) {
    $key = strtoupper(preg_replace('/[^A-Za-z]/', '', $key));
    $keyLength = strlen($key);
    $decryptedText = '';
    
    for ($i = 0; $i < strlen($text); $i++) {
        $textChar = ord($text[$i]) - 65;
        $keyChar = ord($key[$i % $keyLength]) - 65;
        $decryptedChar = ($textChar - $keyChar + 26) % 26;
        $decryptedText .= chr($decryptedChar + 65);
    }
    
    return $decryptedText;
}

// Шифрование
$vigenereEncrypted = vigenereEncrypt($message, $vigenereKey);
echo "После шифра Виженера: $vigenereEncrypted\n";

$vigenereDecrypted = vigenereDecrypt($hillDecrypted, $vigenereKey);
echo "После расшифровки Виженера: $vigenereDecrypted\n";

?>