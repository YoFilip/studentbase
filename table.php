<?php
    include "connect.php";
    $kierunek = isset($_GET['kierunek']) ? $_GET['kierunek'] : '';
    $szkola = isset($_GET['szkola']) ? $_GET['szkola'] : '';
    $sort = isset($_GET['sort']) ? $_GET['sort'] : '';

    $sql = "SELECT u.Imie, u.Nazwisko, k.NazwaK, k.Prog, s.Nazwa, u.Srednia 
            FROM uczen u 
            JOIN kierunki k ON u.IDKierunku = k.IDKierunku 
            JOIN szkoly s ON u.IDSzkoly = s.IDSzkoly";

    if (!empty($kierunek)) {
        $sql .= " WHERE k.NazwaK = '".$conn->real_escape_string($kierunek)."'";
    }

    if (!empty($szkola)) {
        if (!empty($kierunek)) {
            $sql .= " AND s.Nazwa = '".$conn->real_escape_string($szkola)."'";
        } else {
            $sql .= " WHERE s.Nazwa = '".$conn->real_escape_string($szkola)."'";
        }
    }

    if ($sort === 'asc') {
        $sql .= " ORDER BY u.Srednia ASC";
    } elseif ($sort === 'desc') {
        $sql .= " ORDER BY u.Srednia DESC";
    }

    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="./table_style.css" />
        <title>Tabela uczniów</title>
    </head>
    <body>
        <a href="./index.php">Formularz Rejestracji Ucznia</a>
        <h1>Zarejestrowani Uczniowie</h1>

        <select id="kierunekSelect">
            <option value="">Wszystkie kierunki</option>
            <option value="Technik Programista">Technik Programista</option>
            <option value="Technik Grafik">Technik Grafik</option></select
        ><br />

        <select id="szkolaSelect">
            <option value="">Wszystkie szkoły</option>
            <option value="Zespół Szkół Elektronicznych i Infomatycznych">
                Zespół Szkół Elektronicznych i Infomatycznych
            </option>
            <option value="Technikum nr 6 Grafiki i Logistyki Środowiska">
                Technikum nr 6 Grafiki i Logistyki Środowiska
            </option></select
        ><br />

        <button id="sortAsc">Sortuj Średnią Rosnąco</button><br />
        <button id="sortDesc">Sortuj Średnią Malejąco</button><br />
        <button id="resetButton">Resetuj filtry</button><br />

        <table cellspacing="0" id="studentsTable">
            <thead>
                <tr>
                    <th>Imie</th>
                    <th>Nazwisko</th>
                    <th>Kierunek</th>
                    <th>Szkoła</th>
                    <th>Średnia Wymagana</th>
                    <th>Średnia Ucznia</th>
                </tr>
            </thead>
            <tbody>
                <?php
             if ($result && $result->num_rows > 0) {
                foreach($result as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['Imie']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Nazwisko']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['NazwaK']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Nazwa']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Prog']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Srednia']) . "</td>";
                    echo "</tr>";
                } } ?>
            </tbody>
        </table>

        <script>
            function updateUrl() {
                const kierunek = document.getElementById('kierunekSelect').value;
                const szkola = document.getElementById('szkolaSelect').value;

                const currentUrl = new URL(window.location.href);
                if (kierunek) {
                    currentUrl.searchParams.set('kierunek', kierunek);
                } else {
                    currentUrl.searchParams.delete('kierunek');
                }

                if (szkola) {
                    currentUrl.searchParams.set('szkola', szkola);
                } else {
                    currentUrl.searchParams.delete('szkola');
                }

                window.location.href = currentUrl.toString();
            }

            function resetFilters() {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('kierunek');
                currentUrl.searchParams.delete('szkola');
                currentUrl.searchParams.delete('sort');
                window.location.href = currentUrl.toString();
            }

            function sortStudents(order) {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('sort', order);
                window.location.href = currentUrl.toString();
            }

            document.getElementById('kierunekSelect').addEventListener('change', updateUrl);
            document.getElementById('szkolaSelect').addEventListener('change', updateUrl);
            document.getElementById('resetButton').addEventListener('click', resetFilters);
            document.getElementById('sortAsc').addEventListener('click', function() { sortStudents('asc'); });
            document.getElementById('sortDesc').addEventListener('click', function() { sortStudents('desc'); });
        </script>
    </body>
</html>
