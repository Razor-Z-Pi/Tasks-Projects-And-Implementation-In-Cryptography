<?php
// __________________ ШИФР ХИЛЛА __________________


/*
    Принцип => Текст разбивается на блоки, которые умножаются на матрицу ключа.
    Требования {
        Длина текста должна быть кратна размеру матрицы;
        Матрица ключа должна быть обратима по модулю (mod) 26;
        Формула шифрования => C = K * P mod 26;
        Формула декодирования (расшифровки) => P = K^{-1} * C mod 26.
    }

    $encryptedText =>  Переменная для хранения зашифрованного текста;
    $decryptedText = > Переменная для расшифрованного текста.
*/

function HillEncrypt($text, $keyMatrix) {
    $n = count($keyMatrix);
    $text = strtoupper(preg_replace('/[^A-Za-z]/', '', $text));
    
    // Дополняем текст, если длина не кратна n
    while (strlen($text) % $n != 0) {
        $text .= 'X';
    }
    
    $encryptedText = '';
    
    for ($i = 0; $i < strlen($text); $i += $n) {
        $block = substr($text, $i, $n);
        $vector = array_map(fn($char) => ord($char) - 65, str_split($block)); // Преобразуем символы в числа (A=0, B=1, ..., Z=25)
        $resultVector = array_fill(0, $n, 0); // Создаем вектор для результата умножения
        
        for ($row = 0; $row < $n; $row++) { // Умнажаем матрицу на вектор
            for ($col = 0; $col < $n; $col++) {
                $resultVector[$row] += $keyMatrix[$row][$col] * $vector[$col];
            }
            $resultVector[$row] = $resultVector[$row] % 26; // вычесляем по модулю 26
            $encryptedText .= chr($resultVector[$row] + 65); // Преобразуем число обратно в символ и добавляем к результату
        }
    }
    
    return $encryptedText;
}

function HillDecrypt($text, $keyMatrix) {
    $n = count($keyMatrix);
    $decryptedText = '';
    
    // Находим обратную матрицу по модулю 26
    $det = Determinant($keyMatrix);
    $invDet = ModInverse($det, 26);
    $adjMatrix = Adj($keyMatrix);
    $invMatrix = array_fill(0, $n, array_fill(0, $n, 0));
    
    // Вычисляем обратную матрицу: K^{-1} = (1/det(K)) * adj(K) mod 26
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $n; $j++) {
            $invMatrix[$i][$j] = (($adjMatrix[$i][$j] * $invDet) % 26 + 26) % 26; // Умножаем присоединенную матрицу на обратный определитель и берем mod 26
        }
    }
    
    for ($i = 0; $i < strlen($text); $i += $n) {
        $block = substr($text, $i, $n);
        $vector = array_map(fn($char) => ord($char) - 65, str_split($block));
        $resultVector = array_fill(0, $n, 0); // Вектор для результата
        
        for ($row = 0; $row < $n; $row++) {
            for ($col = 0; $col < $n; $col++) {
                $resultVector[$row] += $invMatrix[$row][$col] * $vector[$col];
            }
            $resultVector[$row] = ($resultVector[$row] % 26 + 26) % 26; // Берем по модулю 26 (двойной mod для отрицательных чисел)
            $decryptedText .= chr($resultVector[$row] + 65); // Преобразуем число в символ и добавляем к результату
        }
    }
    
    return $decryptedText;
}

// Вспомогательные функции для шифра Хилла

// Фун-ия вычисления определителя матрицы
function Determinant($matrix) {
    $n = count($matrix);
    
    if ($n == 1) {
        return $matrix[0][0];
    }
    
    // Для матрицы 2x2: det = a*d - b*c
    if ($n == 2) {
        return $matrix[0][0] * $matrix[1][1] - $matrix[0][1] * $matrix[1][0];
    }
    
    $det = 0; // Переменная для определителя
    
    // Разложение по первой строке (метод Лапласа)
    for ($i = 0; $i < $n; $i++) {
        $minor = array(); // Создаем минор (матрица без i-го столбца и первой строки)

        // Строим минорную матрицу
        for ($j = 1; $j < $n; $j++) {
            $minorRow = array();
            for ($k = 0; $k < $n; $k++) {
                if ($k != $i) { // исключаем i-й столбец
                    $minorRow[] = $matrix[$j][$k];
                }
            }
            if (!empty($minorRow)) {
                $minor[] = $minorRow; // Добавляем строку в минор
            }
        }
        $sign = ($i % 2 == 0) ? 1 : -1; // (-1)^(i+j)
        $det += $sign * $matrix[0][$i] * Determinant($minor); // Рекурсивно вычисляем определитель минора
    }
    
    return $det;
}

// Фун-ия обратного элем. по модулю
function ModInverse($a, $m) {
    for ($x = 1; $x < $m; $x++) {
        if (($a * $x) % $m == 1) {
            return $x;
        }
    }
    return 1;
}

// Фун-ия присоединение матрицы
function Adj($matrix) {
    $n = count($matrix);
    $adj = array_fill(0, $n, array_fill(0, $n, 0));
    
    if ($n == 1) {
        $adj[0][0] = 1;
        return $adj;
    }
    
    // Для каждой ячейки матрицы вычисляем алгебраическое дополнение
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $n; $j++) {
            $minor = array();
            for ($k = 0; $k < $n; $k++) {
                if ($k == $i) continue;
                $minorRow = array();
                for ($l = 0; $l < $n; $l++) {
                    if ($l != $j) {
                        $minorRow[] = $matrix[$k][$l];
                    }
                }
                $minor[] = $minorRow;
            }
            $sign = (($i + $j) % 2 == 0) ? 1 : -1;
            $adj[$j][$i] = $sign * Determinant($minor);
        }
    }
    
    return $adj;
}

// Исходное сообщение и ключи
$message = "HELLO";
$hillKeyMatrix = [
    [6, 24, 1],
    [13, 16, 10],
    [20, 17, 15]
];

echo "Исходное сообщение: $message\n";


$hillEncrypted = HillEncrypt($message, $hillKeyMatrix);
echo "После шифра Хилла: $hillEncrypted\n";

$hillDecrypted = HillDecrypt($hillEncrypted, $hillKeyMatrix);
echo "После декодирования Хилла: $hillDecrypted\n";
?>