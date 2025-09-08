<?php

// __________________ ШИФР ВИЖЕНЕРА ________________________

/* 
    1. VigenereEncrypt => функция шифрования;
    2. VigenereDecrypt => функция дешифрования 

    Принцип => Каждая буква сдвигается на значение буквы ключа (A=0, B=1, ..., Z=25).
    Формула шифрования => C_i = (P_i + K_i) mod 26
    Формула декодирования (расшифровки) => P_i = (C_i - K_i + 26) mod 26
*/

function VigenereEncrypt($text, $key) {
    $text = strtoupper(preg_replace('/[^A-Za-z]/', '', $text));
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

function VigenereDecrypt($text, $key) {
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

$message = "HELLO";
$vigenereKey = "KEY";

// Шифрование
$vigenereEncrypted = VigenereEncrypt($message, $vigenereKey);
echo "После шифра Виженера: $vigenereEncrypted\n";

$vigenereDecrypted = VigenereDecrypt($vigenereEncrypted, $vigenereKey);
echo "После декодирования Виженера: $vigenereDecrypted\n";

?>