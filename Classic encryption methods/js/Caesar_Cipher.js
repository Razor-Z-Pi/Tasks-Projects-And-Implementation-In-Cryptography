function caesarCipher(text, shift, decr = false) {
    const shiftAmount = decr ? (26 - shift) % 26 : shift;
    
    return text.replace(/[a-z]/gi, char => {
        const base = char === char.toUpperCase() ? "A".charCodeAt(0) : "a".charCodeAt(0);
        const code = char.charCodeAt(0);
        
        let newCode = (code - base + shiftAmount) % 26;
        if (newCode < 0) newCode += 26;
        
        return String.fromCharCode(base + newCode);
    });
}

const text = "Hello World! ABC XYZ";
const shift = 3;

const encrypted = caesarCipher(text, shift);
console.log("Encrypted: ", encrypted);

const decrypted = caesarCipher(encrypted, shift, true);
console.log("Decrypted: ", decrypted);