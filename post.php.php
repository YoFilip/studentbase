<?php
require_once "connect.php";

function createTable($conn, $query)
{
    $conn->query($query);
}

function insertDataIfNotExists($conn, $query, $checkQuery)
{
    $result = $conn->query($checkQuery);
    if ($result->num_rows <= 0) {
        $conn->query($query);
    }
}

createTable(
    $conn,
    "CREATE TABLE IF NOT EXISTS kierunki(IDKierunku int AUTO_INCREMENT, IDSzkoly int, NazwaK VARCHAR(255), Prog FLOAT NOT NULL, PRIMARY KEY(IDKierunku));",
);
createTable(
    $conn,
    "CREATE TABLE IF NOT EXISTS szkoly(IDSzkoly int AUTO_INCREMENT, Nazwa VARCHAR(255), PRIMARY KEY(IDSzkoly));",
);
createTable(
    $conn,
    "CREATE TABLE IF NOT EXISTS uczen(IDUcznia int AUTO_INCREMENT, Imie VARCHAR(255), Nazwisko VARCHAR(255), Pesel VARCHAR(255), Srednia int, IDSzkoly int, IDKierunku VARCHAR(255), PRIMARY KEY(IDUcznia));",
);

insertDataIfNotExists(
    $conn,
    "INSERT INTO kierunki(IDSzkoly, NazwaK, Prog) VALUES(1, 'Technik Programista', 5);",
    "SELECT * FROM kierunki WHERE NazwaK = 'Technik Programista'",
);
insertDataIfNotExists(
    $conn,
    "INSERT INTO kierunki(IDSzkoly, NazwaK, Prog) VALUES(2, 'Technik Grafik', 4);",
    "SELECT * FROM kierunki WHERE NazwaK = 'Technik Grafik'",
);

insertDataIfNotExists(
    $conn,
    "INSERT INTO szkoly(Nazwa) VALUES('Zespół Szkół Elektronicznych i Infomatycznych');",
    "SELECT * FROM szkoly WHERE Nazwa = 'Zespół Szkół Elektronicznych i Infomatycznych'",
);
insertDataIfNotExists(
    $conn,
    "INSERT INTO szkoly(Nazwa) VALUES('Technikum nr 6 Grafiki i Logistyki Środowiska');",
    "SELECT * FROM szkoly WHERE Nazwa = 'Technikum nr 6 Grafiki i Logistyki Środowiska'",
);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $imie = $_POST["imie"];
    $nazwisko = $_POST["nazwisko"];
    $pesel = $_POST["pesel"];
    $kierunek = $_POST["kierunek"];
    $jp = $_POST["jp"];
    $ja = $_POST["ja"];
    $ma = $_POST["ma"];
    $szkola =
        $_POST["szkola"] == "zseii"
            ? "Zespół Szkół Elektronicznych i Infomatycznych"
            : "Technikum nr 6 Grafiki i Logistyki Środowiska";
    $srednia = ($jp + $ja + $ma) / 3;
    $srednia = number_format($srednia, 2, ".", "");

    $schoolIdQuery = $conn->prepare(
        "SELECT IDSzkoly FROM szkoly WHERE Nazwa = ?",
    );
    $schoolIdQuery->bind_param("s", $szkola);
    $schoolIdQuery->execute();
    $result = $schoolIdQuery->get_result();
    $schoolId = $result->num_rows > 0 ? $result->fetch_assoc()["IDSzkoly"] : 0;

    $studentQuery = $conn->prepare(
        "INSERT INTO uczen (Imie, Nazwisko, Pesel, Srednia, IDSzkoly, IDKierunku) VALUES (?, ?, ?, ?, ?, ?)",
    );
    $studentQuery->bind_param(
        "sssiii",
        $imie,
        $nazwisko,
        $pesel,
        $srednia,
        $schoolId,
        $kierunek,
    );
    $studentQuery->execute();

    header("Location: table.php");
    exit();
}
?>
