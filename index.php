<?php
$input = '';
$result = '0';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['expression'] ?? '';
    $input = str_replace(['×', '÷', '−', '%'], ['*', '/', '-', '/100'], $input);
    try {
        // Prosta walidacja – tylko cyfry, operatory, kropki i nawiasy
        if (preg_match('/^[0-9+\-*/.() ]+$/', $input)) {
            eval('$result = ' . $input . ';');
        } else {
            $result = 'Błąd';
        }
    } catch (Throwable $e) {
        $result = 'Błąd';
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Kalkulator Anna Błaszczyk-Urycz 1</title>
  <link rel="icon" href="data:," />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #000;
      color: white;
      font-family: system-ui, sans-serif;
    }
    .calculator {
      max-width: 400px;
      margin: 50px auto;
      border-radius: 30px;
      padding: 20px;
      background-color: #1c1c1c;
      box-shadow: 0 0 20px rgba(255,255,255,0.05);
    }
    .display {
      text-align: right;
      font-size: 48px;
      padding: 20px 10px;
      margin-bottom: 10px;
      background-color: #000;
      border-radius: 10px;
      min-height: 80px;
      overflow-x: auto;
    }
    .btn {
      font-size: 24px;
      height: 70px;
      width: 70px;
      margin: 5px;
      border-radius: 50%;
    }
    .btn-zero {
      width: 150px;
      border-radius: 35px;
      text-align: left;
      padding-left: 28px;
    }
    .btn-light {
      background-color: #a5a5a5;
      color: black;
    }
    .btn-dark {
      background-color: #333;
      color: white;
    }
    .btn-warning {
      background-color: #ff9f0a;
      color: white;
    }
  </style>
</head>
<body>
  <div class="calculator">
    <form method="post">
      <input type="hidden" name="expression" id="expressionInput" value="">
      <div id="result" class="display"><?php echo htmlspecialchars($result); ?></div>
      <div class="d-flex flex-wrap justify-content-between">
        <button type="button" class="btn btn-light" onclick="clearDisplay()">AC</button>
        <button type="button" class="btn btn-light" onclick="append('%')">%</button>
        <button type="button" class="btn btn-light" onclick="append('÷')">÷</button>
        <button type="button" class="btn btn-warning" onclick="append('×')">×</button>

        <button type="button" class="btn btn-dark" onclick="append('7')">7</button>
        <button type="button" class="btn btn-dark" onclick="append('8')">8</button>
        <button type="button" class="btn btn-dark" onclick="append('9')">9</button>
        <button type="button" class="btn btn-warning" onclick="append('−')">−</button>

        <button type="button" class="btn btn-dark" onclick="append('4')">4</button>
        <button type="button" class="btn btn-dark" onclick="append('5')">5</button>
        <button type="button" class="btn btn-dark" onclick="append('6')">6</button>
        <button type="button" class="btn btn-warning" onclick="append('+')">+</button>

        <button type="button" class="btn btn-dark" onclick="append('1')">1</button>
        <button type="button" class="btn btn-dark" onclick="append('2')">2</button>
        <button type="button" class="btn btn-dark" onclick="append('3')">3</button>
        <button type="submit" class="btn btn-warning">=</button>

        <button type="button" class="btn btn-dark btn-zero" onclick="append('0')">0</button>
        <button type="button" class="btn btn-dark" onclick="append('.')">.</button>
      </div>
    </form>
  </div>

  <script>
    const result = document.getElementById("result");
    const input = document.getElementById("expressionInput");

    function append(value) {
      if (result.innerText === "0" && value !== ".") {
        result.innerText = value;
      } else {
        result.innerText += value;
      }
      input.value = result.innerText;
    }

    function clearDisplay() {
      result.innerText = "0";
      input.value = "";
    }
  </script>
</body>
</html>
