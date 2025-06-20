<?php
$result = '';
if (isset($_POST['expression'])) {
    $expression = $_POST['expression'];
    $sanitized_expression = preg_replace('/[^0-9\+\-\*\/\.\(\)]/', '', $expression);
    if ($sanitized_expression) {
        // Suppress errors for division by zero etc.
        error_reporting(0);
        $result = eval('return ' . $sanitized_expression . ';');
        error_reporting(E_ALL);
        if ($result === false) {
            $result = 'Błąd';
        }
    } else {
        $result = '0';
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projekt Anna Błaszczyk-Urycz</title>
    <style>
        body {
            background-color: #dcdcdc;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        .calculator-container {
            background-color: #000;
            border-radius: 50px;
            padding: 25px;
            width: 90vw;
            max-width: 420px;
            height: 85vh;
            max-height: 850px;
            display: flex;
            flex-direction: column;
        }

        .calculator {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        
        #display-form {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        #display {
            width: 100%;
            background-color: #000;
            border: none;
            color: #fff;
            text-align: right;
            font-size: clamp(48px, 12vh, 96px);
            padding: 0 10px;
            box-sizing: border-box;
            font-weight: 200;
            flex-grow: 1;
            margin-bottom: 20px;
        }

        .buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
        }

        button {
            aspect-ratio: 1 / 1;
            border-radius: 50%;
            border: none;
            font-size: clamp(24px, 5vw, 42px);
            cursor: pointer;
            transition: background-color 0.2s;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        button:active {
            filter: brightness(1.2);
        }

        .btn-number {
            background-color: #333;
            color: #fff;
        }

        .btn-function {
            background-color: #a5a5a5;
            color: #000;
        }

        .btn-operator {
            background-color: #f1a33c;
            color: #fff;
        }

        .btn-zero {
            grid-column: span 2;
            aspect-ratio: auto;
            border-radius: 50px;
            justify-content: flex-start;
            padding-left: 15%;
        }
    </style>
</head>
<body>
    
    <div class="calculator-container">
        <div class="calculator">
            <form id="display-form" method="post" action="">
                <input type="text" id="display" name="expression" value="<?php echo htmlspecialchars($result ?: '0'); ?>" readonly>
            
                <div class="buttons">
                    <button type="button" class="btn-function" onclick="clearDisplay()"><?= ($result !== '' && $result !== '0') ? 'C' : 'AC' ?></button>
                    <button type="button" class="btn-function" onclick="negate()">+/-</button>
                    <button type="button" class="btn-function" onclick="percentage()">%</button>
                    <button type="button" class="btn-operator" onclick="appendValue('/')">÷</button>
                    
                    <button type="button" class="btn-number" onclick="appendValue('7')">7</button>
                    <button type="button" class="btn-number" onclick="appendValue('8')">8</button>
                    <button type="button" class="btn-number" onclick="appendValue('9')">9</button>
                    <button type="button" class="btn-operator" onclick="appendValue('*')">×</button>
                    
                    <button type="button" class="btn-number" onclick="appendValue('4')">4</button>
                    <button type="button" class="btn-number" onclick="appendValue('5')">5</button>
                    <button type="button" class="btn-number" onclick="appendValue('6')">6</button>
                    <button type="button" class="btn-operator" onclick="appendValue('-')">−</button>

                    <button type="button" class="btn-number" onclick="appendValue('1')">1</button>
                    <button type="button" class="btn-number" onclick="appendValue('2')">2</button>
                    <button type="button" class="btn-number" onclick="appendValue('3')">3</button>
                    <button type="button" class="btn-operator" onclick="appendValue('+')">+</button>

                    <button type="button" class="btn-number btn-zero" onclick="appendValue('0')">0</button>
                    <button type="button" class="btn-number" onclick="appendValue('.')">,</button>

                    <button type="submit" class="btn-operator">=</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const display = document.getElementById('display');

        function appendValue(value) {
            if (display.value === '0' || display.value === 'Błąd') {
                display.value = '';
            }
            display.value += value;
        }

        function clearDisplay() {
            display.value = '0';
        }
        
        function negate() {
             if (display.value !== '0' && display.value !== '') {
                display.value = parseFloat(display.value) * -1;
            }
        }

        function percentage() {
            if (display.value !== '0' && display.value !== '') {
                display.value = parseFloat(display.value) / 100;
            }
        }
    </script>
</body>
</html>
