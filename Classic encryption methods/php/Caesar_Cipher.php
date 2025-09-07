<?php
function CaesarCipher($text, $shift, $mode = 'encrypt', $lang = 'eng') {
    $result = '';
    $shift = $mode === 'decrypt' ? - $shift : $shift;
    
    // Определяем алфавит
    if ($lang === 'rus') {
        $lowercase = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя';
        $uppercase = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
        $langLength = 33;
    } else {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $langLength = 26;
    }
    
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        
        if (strpos($lowercase . $uppercase, $char) !== false) {
            $isUpper = strpos($uppercase, $char) !== false;
            $langToUse = $isUpper ? $uppercase : $lowercase;
            
            $position = strpos($langToUse, $char);
            $newPosition = ($position + $shift) % $langLength;
            
            // Обработка отрицательных позиций
            if ($newPosition < 0) {
                $newPosition += $langLength;
            }
            
            $result .= $langToUse[$newPosition];
        } else {
            // Не буквенные символы остаются без изменений
            $result .= $char;
        }
    }
    
    return $result;
}

$text = "Привет, Мир!";
$shift = 5;

$encrypted = CaesarCipher($text, $shift, 'encrypt', 'rus');
echo "Зашифрованный: " . $encrypted . "\n";

$decrypted = CaesarCipher($encrypted, $shift, 'decrypt', 'eng');
echo "Расшифрованный: " . $decrypted . "\n";
?>