<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>TravelWithMahin</title>

<style>
 * {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
  font-family: 'Courier New', Courier, monospace;
}

html, body {
  height: 100%;
  background: black;
  overflow: hidden;
  color: #0f0;
}

#matrixCanvas {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 0;
}

.center-box {
  --box-width: 360px;
  --box-height: 550px;

  position: fixed;
  top: 53%;
  left: 50%;
  transform: translate(-50%, -50%);

  width: var(--box-width);
  height: var(--box-height);

  background: rgba(0, 20, 0, 0.85);
  border: 2px solid #00ff00;
  border-radius: 15px;
  padding: 25px 30px;

  box-shadow: 0 0 10px #00ff00, 0 0 20px #00ff00, 0 0 40px #00ff00;
  z-index: 10;

  box-sizing: border-box;
  overflow-y: auto;
  scrollbar-width: none; /* Firefox */
}

.center-box::-webkit-scrollbar {
  width: 0px;
  background: transparent;
}

label {
  display: block;
  margin-bottom: 6px;
  font-weight: bold;
  text-shadow: 0 0 3px #0f0;
}

textarea,
select,
button {
  width: 100%;
  padding: 10px;
  margin-bottom: 18px;
  background: #002200;
  border: 1px solid #0f0;
  border-radius: 6px;
  color: #0f0;
  font-size: 14px;
  resize: vertical;
}

textarea {
  min-height: 70px;
  max-height: 120px;
}

select {
  cursor: pointer;
}

.toggle-container {
  margin-bottom: 18px;
  text-align: center;
}

.toggle-container input[type="radio"] {
  display: none;
}

.toggle-container label {
  padding: 8px 20px;
  border: 2px solid #0f0;
  border-radius: 25px;
  cursor: pointer;
  margin: 0 10px;
}

.toggle-container input[type="radio"]:checked + label {
  background-color: #0f0;
  color: black;
  box-shadow: 0 0 12px #0f0;
}

button {
  background-color: #0f0;
  color: black;
  font-weight: bold;
  cursor: pointer;
}

button:hover {
  background-color: #00cc00;
}

.navbar {
  position: fixed;
  top: 0;
  width: 100%;
  background: #001100cc;
  padding: 12px 20px;
  color: #0f0;
  font-weight: bold;
  z-index: 20;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.navbar a {
  color: #0f0;
  text-decoration: none;
  font-weight: normal;
}

.navbar a:hover {
  color: #00ff00;
}
.center-box h2 {
  text-align: center;
}
</style>
</head>



<body>


<canvas id="matrixCanvas"></canvas>

<div class="navbar">
  <div>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></div>
  <a href="logout.php">Logout</a>
</div>

<div class="center-box">
<h2 >Lisa Maam's Project</h2>
  <label for="inputText">Input Text</label>
  <textarea id="inputText"></textarea>
  <label for="outputText">Output Text</label>
  <textarea id="outputText" readonly></textarea>
  <label for="cipherSelect">Select Cipher</label>
  <select id="cipherSelect">
    <option value="caesar">Caesar Cipher</option>
    <option value="vernam">Vernam Cipher</option>
    <option value="vigenere">Vigenere Cipher</option>
  </select>
  <div class="toggle-container">
    <input type="radio" id="encryptMode" name="mode" value="encrypt" checked />
    <label for="encryptMode">Encrypt</label>
    <input type="radio" id="decryptMode" name="mode" value="decrypt" />
    <label for="decryptMode">Decrypt</label>
  </div>
  <label for="keyInput">Key</label>
  <textarea id="keyInput"></textarea>
  <button id="executeBtn">Execute</button>
</div>

<script>
const canvas = document.getElementById('matrixCanvas');
const ctx = canvas.getContext('2d');
let width, height;
function resizeCanvas() {
  width = window.innerWidth;
  height = window.innerHeight;
  canvas.width = width;
  canvas.height = height;
}

window.addEventListener('resize', resizeCanvas);
resizeCanvas();
const chars = '1234567890Everyone that I fought is not my enemy and everyone who helps me is not my friend!@#$%&.';
const fontSize = 16;
const columns = Math.floor(width / fontSize);
const rows = Math.floor(height / fontSize);
let verticalDrops = new Array(columns).fill(1);
let horizontalDrops = new Array(rows).fill(1);

function drawVertical() {
  ctx.fillStyle = 'rgba(0, 0, 0, 0.05)';
  ctx.fillRect(0, 0, width, height);
  ctx.fillStyle = '#0f0';
  ctx.font = fontSize + 'px monospace';
  for (let i = 0; i < columns; i++) {
    const text = chars.charAt(Math.floor(Math.random() * chars.length));
    ctx.fillText(text, i * fontSize, verticalDrops[i] * fontSize);
    if (verticalDrops[i] * fontSize > height && Math.random() > 0.975) {
      verticalDrops[i] = 0;
    }
    verticalDrops[i]++;
  }
}

function drawHorizontal() {
  ctx.font = fontSize + 'px monospace';
  ctx.fillStyle = '#0f0';
  for (let j = 0; j < rows; j++) {
    const text = chars.charAt(Math.floor(Math.random() * chars.length));
    ctx.fillText(text, horizontalDrops[j] * fontSize, j * fontSize);
    if (horizontalDrops[j] * fontSize > width && Math.random() > 0.975) {
      horizontalDrops[j] = 0;
    }
    horizontalDrops[j]++;
  }
}

function animate() {
  drawVertical();
  drawHorizontal();
  requestAnimationFrame(animate);
}
animate();

//-----------------------------------------------------------------------------
function normalize(text) {
  return text.toLowerCase().replace(/[^a-z]/g, '');
}

function charCode(c) {
  return c.charCodeAt(0) - 97;
}

function codeToChar(n) {
  return String.fromCharCode((n % 26 + 26) % 26 + 97);
}

//Caesar Cipher
function caesar(text, key, enc = true) {
  let shift = parseInt(key);
  if (!enc) shift = -shift;
  return normalize(text)
    .split('')
    .map(c => codeToChar(charCode(c) + shift))
    .join('');
}

//Corrected Vernam Cipher (with key length check and modular arithmetic)
function vernam(text, key, enc = true) {
  text = normalize(text);
  key = normalize(key);

  if (key.length !== text.length) {
    return 'Error: In Vernam cipher, key length must match the plaintext/ciphertext length.';
  }

  return text.split('').map((c, i) => {
    let p = charCode(c);
    let k = charCode(key[i]);
    let val = enc ? p + k : p - k;
    if (val >= 26) val -= 26;
    if (val < 0) val += 26;
    return codeToChar(val);
  }).join('');
}

// Vigenere Cipher
function vigenere(text, key, enc = true) {
  text = normalize(text);
  key = normalize(key);
  return text.split('').map((c, i) => {
    const shift = charCode(key[i % key.length]);
    return codeToChar(charCode(c) + (enc ? shift : -shift));
  }).join('');
}

//Cipher Execution Handler
document.getElementById('executeBtn').addEventListener('click', () => {
  const input = document.getElementById('inputText').value;
  const key = document.getElementById('keyInput').value;
  const cipher = document.getElementById('cipherSelect').value;
  const mode = document.querySelector('input[name="mode"]:checked').value;
  let output = '';
  try {
    if (cipher === 'caesar') {
      output = caesar(input, key, mode === 'encrypt');
    } else if (cipher === 'vernam') {
      output = vernam(input, key, mode === 'encrypt');
    } else if (cipher === 'vigenere') {
      output = vigenere(input, key, mode === 'encrypt');
    }
  } catch (e) {
    output = 'Error: ' + e.message;
  }
  document.getElementById('outputText').value = output;
});
</script>


</body>
</html>
