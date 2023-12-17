<?php 
require_once "connect.php"; 

$querySchool1 = "SELECT * FROM kierunki WHERE IDSzkoly = 1";
$querySchool2 = "SELECT * FROM kierunki WHERE IDSzkoly = 2";

$resSchool1 = $conn->query($querySchool1);
$resSchool2 = $conn->query($querySchool2);

$school1 = [];
$school2 = [];
foreach ($resSchool1 as $row) {
    $school1[$row['IDKierunku']] = $row['NazwaK'];
}
foreach ($resSchool2 as $row) {
    $school2[$row['IDKierunku']] = $row['NazwaK'];
}
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="./index_style.css" />
        <title>Rejestracja do szkoły</title>
    </head>
    <body>
        <div id="main">
            <div id="content">
                <h1>Wprowadź nowego ucznia:</h1>
                <form action="formSubmit.php" method="post" id="form">
                    <div class="form-group">
                        <label for="imie">Imię:</label>
                        <input
                            type="text"
                            name="imie"
                            placeholder="Wpisz imie"
                            required
                        />
                    </div>

                    <div class="form-group">
                        <label for="nazwisko">Nazwisko:</label>
                        <input
                            type="text"
                            name="nazwisko"
                            placeholder="Wpisz nazwisko"
                            required
                        />
                    </div>

                    <div class="form-group">
                        <label for="pesel">Pesel:</label>
                        <input
                            type="text"
                            name="pesel"
                            placeholder="Wpisz pesel"
                            required
                        />
                    </div>

                    <div class="form-group">
                        <label for="szkolaSelect">Wybór szkoły:</label>
                        <select id="szkolaSelect" name="szkola">
                            <option value="">Wybierz szkołę</option>
                            <option value="zseii">
                                Zespół Szkół Elektronicznych i Informatycznych
                            </option>
                            <option value="grafik">
                                Technikum nr 6 Grafiki i Logistyki Środowiska
                            </option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="kierunek">Wybór kierunku:</label>
                        <select id="selectKierunek" name="kierunek"></select>
                    </div>

                    <div class="form-group">
                        <label for="jp">Język polski:</label>
                        <input
                            type="number"
                            name="jp"
                            placeholder="Wpisz ocenę z polskiego"
                            required
                        />
                    </div>

                    <div class="form-group">
                        <label for="ja">Język angielski:</label>
                        <input
                            type="number"
                            name="ja"
                            placeholder="Wpisz ocenę z angielskiego"
                            required
                        />
                    </div>

                    <div class="form-group">
                        <label for="ma">Matematyka:</label>
                        <input
                            type="number"
                            name="ma"
                            placeholder="Wpisz ocenę z matematyki"
                            required
                        />
                    </div>

                    <div class="form-group">
                        <button type="submit" id="submitBtn">
                            Wyślij i zobacz wynik
                        </button>
                        <button type="reset">Zacznij na nowo</button>
                    </div>
                </form>
                <p><a href="table.php">Wynik rekrutacji</a></p>
            </div>
        </div>
    </body>
    <script>
            const school1Options = <?php echo json_encode($school1); ?>;
            const school2Options = <?php echo json_encode($school2); ?>;

            const updateSelectOptions = (options) => {
                const select = document.getElementById('selectKierunek');
                select.innerHTML = '<option value="">Wybierz kierunek</option>';
                Object.entries(options).forEach(([id, name]) => {
            select.innerHTML += `<option value="${id}">${name}</option>`;
        });

            };

            document.getElementById('szkolaSelect').addEventListener('change', (event) => {
                if (event.target.value === 'zseii') {
                    updateSelectOptions(school1Options);
                } else if (event.target.value === 'grafik') {
                    updateSelectOptions(school2Options);
                }
            });

            document.getElementById('form').addEventListener('submit', function(event) {
                const pesel = document.querySelector('[name="pesel"]').value;
                if (!pesel.match(/^\d{11}$/)) {
                    alert('PESEL musi składać się z 11 cyfr.');
                    event.preventDefault();
                }
            });
    </script>
</html>
