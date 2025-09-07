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
        $vector = array_map(fn($char) => ord($char) - 65, str_split($block));
        $resultVector = array_fill(0, $n, 0);
        
        for ($row = 0; $row < $n; $row++) { // Умнажаем матрицу на вектор
            for ($col = 0; $col < $n; $col++) {
                $resultVector[$row] += $keyMatrix[$row][$col] * $vector[$col];
            }
            $resultVector[$row] = $resultVector[$row] % 26;
            $encryptedText .= chr($resultVector[$row] + 65);
        }
    }
    
    return $encryptedText;
}

function HillDecrypt($text, $keyMatrix) {
    $n = count($keyMatrix);
    $decryptedText = '';
    
    // Находим обратную матрицу по модулю 26
    $det = determinant($keyMatrix);
    $invDet = ModInverse($det, 26);
    $adjMatrix = Adj($keyMatrix);
    $invMatrix = array_fill(0, $n, array_fill(0, $n, 0));
    
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $n; $j++) {
            $invMatrix[$i][$j] = (($adjMatrix[$i][$j] * $invDet) % 26 + 26) % 26;
        }
    }
    
    for ($i = 0; $i < strlen($text); $i += $n) {
        $block = substr($text, $i, $n);
        $vector = array_map(fn($char) => ord($char) - 65, str_split($block));
        $resultVector = array_fill(0, $n, 0);
        
        for ($row = 0; $row < $n; $row++) {
            for ($col = 0; $col < $n; $col++) {
                $resultVector[$row] += $invMatrix[$row][$col] * $vector[$col];
            }
            $resultVector[$row] = ($resultVector[$row] % 26 + 26) % 26;
            $decryptedText .= chr($resultVector[$row] + 65);
        }
    }
    
    return $decryptedText;
}

// Вспомогательные функции для шифра Хилла
function Determinant($matrix) {
    $n = count($matrix);
    if ($n == 2) {
        return $matrix[0][0] * $matrix[1][1] - $matrix[0][1] * $matrix[1][0];
    }
    $det = 0;
    for ($i = 0; $i < $n; $i++) {
        $minor = array();
        for ($j = 1; $j < $n; $j++) {
            $minorRow = array();
            for ($k = 0; $k < $n; $k++) {
                if ($k != $i) $minorRow[] = $matrix[$j][$k];
            }
            $minor[] = $minorRow;
        }
        $det += $matrix[0][i] * pow(-1, $i) * determinant($minor);
    }
    return $det;
}

function ModInverse($a, $m) {
    for ($x = 1; $x < $m; $x++) {
        if (($a * $x) % $m == 1) {
            return $x;
        }
    }
    return 1;
}

function Adj($matrix) {
    $n = count($matrix);
    $adj = array_fill(0, $n, array_fill(0, $n, 0));
    
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $n; $j++) {
            $minor = array();
            for ($k = 0; $k < $n; $k++) {
                if ($k == $i) continue;
                $minorRow = array();
                for ($l = 0; $l < $n; $l++) {
                    if ($l != $j) $minorRow[] = $matrix[$k][$l];
                }
                $minor[] = $minorRow;
            }
            $adj[$j][$i] = pow(-1, $i + $j) * determinant($minor);
        }
    }
    
    return $adj;
}

// Исходное сообщение и ключи
$message = "HELLO";
$vigenereKey = "KEY";
$hillKeyMatrix = [
    [6, 24, 1],
    [13, 16, 10],
    [20, 17, 15]
];

echo "Исходное сообщение: $message\n\n";


$hillEncrypted = HillEncrypt($vigenereEncrypted, $hillKeyMatrix);
echo "После шифра Хилла: $hillEncrypted\n\n";

// Расшифрование
$hillDecrypted = HillDecrypt($hillEncrypted, $hillKeyMatrix);
echo "После декодирования Хилла: $hillDecrypted\n";


?>