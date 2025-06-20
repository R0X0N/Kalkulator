<?php
// --- SEKCJA PHP ---
// Ta część kodu wykonuje się na serwerze, tylko gdy formularz zostanie wysłany (kliknięcie "=")

$result = ''; // Domyślnie wynik jest pusty

// Sprawdzamy, czy formularz został wysłany i czy zawiera pole 'expression'
if (isset($_POST['expression'])) {
    $expression = $_POST['expression'];

    // --- UWAGA DOTYCZĄCA BEZPIECZEŃSTWA ---
    // Funkcja eval() jest bardzo niebezpieczna, ponieważ może wykonać dowolny kod PHP.
    // W prawdziwej aplikacji NIGDY nie należy jej używać z danymi od użytkownika bez solidnej walidacji.
    // Tutaj, dla prostoty, usuwamy wszystkie znaki, które nie są cyframi lub podstawowymi operatorami.
    $sanitized_expression = preg_replace('/[^0-9\+\-\*\/\.\(\)]/', '', $expression);

    // Sprawdzamy, czy po czyszczeniu cokolwiek zostało
    if ($sanitized_expression) {
        // Używamy @, aby stłumić błędy (np. dzielenie przez zero) i obsłużyć je poniżej
        $result = @eval('return ' . $sanitized_expression . ';');

        if ($result === false) {
            $result = 'Błąd'; // Jeśli wystąpił błąd w obliczeniach
        }
    } else {
        $result = 'Błędne wyrażenie';
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator PHP</title>
    <style>
        /* --- SEKCJA CSS --- */
        /* Stylizacja, aby kalkulator wyglądał jak ten z iPhone'a */
        
        body {
            background-color: #000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        .calculator {
            width: 320px;
            padding: 20px;
            border-radius: 40px;
            background-color: #000;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        #display-form {
            width: 100%;
        }
        
        #display {
            width: 100%;
            height: 80px;
            background-color: #000;
            border: none;
            color: #fff;
            text-align: right;
            font-size: 64px;
            padding: 0 20px;
            box-sizing: border-box;
            margin-bottom: 20px;
            font-weight: 200;
        }

        .buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }

        button {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            border: none;
            font-size: 28px;
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
            font-size: 36px;
        }

        .btn-zero {
            grid-column: span 2;
            width: 140px;
            border-radius: 35px;
            justify-content: flex-start;
            padding-left: 25px;
        }
    </style>
</head>
<body>
    
    <div class="calculator">
        <!-- Formularz wysyła wyrażenie do PHP do obliczenia -->
        <form id="display-form" method="post" action="">
            <!-- Wyświetlacz, który jest jednocześnie polem tekstowym formularza -->
            <!-- PHP wstawia tutaj wynik obliczeń po odświeżeniu strony -->
            <input type="text" id="display" name="expression" value="<?php echo htmlspecialchars($result); ?>" readonly>
        
            <div class="buttons">
                <!-- Przyciski funkcji (szare) -->
                <button type="button" class="btn-function" onclick="clearDisplay()">AC</button>
                <button type="button" class="btn-function" onclick="appendValue('+/-')">+/-</button> <!-- Funkcjonalność do samodzielnej implementacji w JS -->
                <button type="button" class="btn-function" onclick="appendValue('%')">%</button> <!-- Funkcjonalność do samodzielnej implementacji w JS -->
                
                <!-- Przyciski operatorów (pomarańczowe) -->
                <button type="button" class="btn-operator" onclick="appendValue('/')">÷</button>
                
                <!-- Przyciski cyfr (ciemnoszare) -->
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

                <!-- Przycisk "=" jest jedynym, który wysyła formularz do PHP -->
                <button type="submit" class="btn-operator">=</button>
            </div>
        </form>
    </div>

    <script>
        // --- SEKCJA JAVASCRIPT ---
        // Ta część kodu działa w przeglądarce użytkownika

        const display = document.getElementById('display');

        // Funkcja do dodawania wartości do wyświetlacza
        function appendValue(value) {
            // Jeśli na wyświetlaczu jest wynik "Błąd", czyścimy go przed dodaniem nowej wartości
            if (display.value === 'Błąd' || display.value === 'Błędne wyrażenie') {
                display.value = '';
            }

            // Obsługa znaków specjalnych
            if (value === '×') value = '*';
            if (value === '÷') value = '/';
            if (value === ',') value = '.';
            
            display.value += value;
        }

        // Funkcja do czyszczenia wyświetlacza
        function clearDisplay() {
            display.value = '';
        }

        // Uwaga: Funkcje dla "+/-" i "%" nie są zaimplementowane,
        // aby zachować prostotę kodu. Wymagałyby one bardziej zaawansowanej logiki w JavaScript.

    </script>
</body>
</html>
