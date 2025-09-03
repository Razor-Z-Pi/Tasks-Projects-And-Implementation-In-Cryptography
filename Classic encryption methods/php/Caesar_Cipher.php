<?php
function CaesarCipher($text, $shift, $mode = 'encrypt', $alphabet = 'eng') {
    $result = '';
    $shift = $mode === 'decrypt' ? -$shift : $shift;
    
    // Определяем алфавит
    if ($alphabet === 'rus') {
        $lowercase = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя';
        $uppercase = 'АБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ';
        $alphabetLength = 33;
    } else {
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $alphabetLength = 26;
    }
    
    for ($i = 0; $i < strlen($text); $i++) {
        $char = $text[$i];
        
        if (strpos($lowercase . $uppercase, $char) !== false) {
            $isUpper = strpos($uppercase, $char) !== false;
            $alphabetToUse = $isUpper ? $uppercase : $lowercase;
            
            $position = strpos($alphabetToUse, $char);
            $newPosition = ($position + $shift) % $alphabetLength;
            
            // Обработка отрицательных позиций
            if ($newPosition < 0) {
                $newPosition += $alphabetLength;
            }
            
            $result .= $alphabetToUse[$newPosition];
        } else {
            // Не-буквенные символы остаются без изменений
            $result .= $char;
        }
    }
    
    return $result;
}

// Пример использования
$text = "Привет, Мир!";
$shift = 5;

$encrypted = CaesarCipher($text, $shift, 'encrypt', 'rus');
echo "Зашифрованный: " . $encrypted . "\n";

$decrypted = CaesarCipher($encrypted, $shift, 'decrypt', 'eng');
echo "Расшифрованный: " . $decrypted . "\n";
?>