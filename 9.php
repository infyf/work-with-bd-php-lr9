<?php
class BD{
    private $bd;
    private $bd_name;

    public function __construct($bd_name) {
        $this->bd = new mysqli("localhost", "root", "", "products");
        $this->bd_name = $bd_name;
    }

    public function ConnectBD(): bool {
        if ($this->bd) {
            return false;
        } else {
            return true;
        }
    }

    public function DisconnectBD(): bool {
        if ($this->bd->close()) {
            return true;
        } else {
            return false;
        }
    }

    public function ReadBD($ID = 0) {
        $prepareBA = $this->bd->query("SELECT * FROM $this->bd_name WHERE ID = $ID");
        if ($prepareBA) {
            echo "<br><br><table border='1'>";
            while ($row = $prepareBA->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["ID"]."</td>";
                echo "<td>".$row["NAME"]."</td>";
                echo "<td>".$row["Country"]."</td>";
                echo "<td>".$row["Producer"]."</td>";
                echo "<td>".$row["Price"]."</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            return false;
        }
    }

    public function ReadAllBD() {
        $prepareBA = $this->bd->query("SELECT * FROM $this->bd_name");
        if ($prepareBA) {
            echo "<table border='1'>";
            echo "<tr><th>ID</th><th>NAME</th><th>Country</th><th>Producer</th><th>Price</th></tr>";
            while ($row = $prepareBA->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row["ID"]."</td>";
                echo "<td>".$row["NAME"]."</td>";
                echo "<td>".$row["Country"]."</td>";
                echo "<td>".$row["Producer"]."</td>";
                echo "<td>".$row["Price"]."</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            return false;
        }
    }

    public function InsertToBD($name, $country, $producer, $price) {
        $name = $this->bd->real_escape_string($name);
        $country = $this->bd->real_escape_string($country);
        $producer = $this->bd->real_escape_string($producer);
        $price = (float)$price;
        $insertSQL = "INSERT INTO $this->bd_name (NAME, Country, Producer, Price) VALUES ('$name', '$country', '$producer', $price)";
        
        if ($this->bd->query($insertSQL) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $this->bd->error;
        }
    }

    public function DeleteFromBD($id) {
        $deleteSQL = "DELETE FROM $this->bd_name WHERE ID = $id";
        if ($this->bd->query($deleteSQL) === TRUE) {
            echo "Record deleted successfully";
        } else {
            echo "Error: " . $this->bd->error;
        }
    }

    public function ChangeValueBD($id, $column, $value) {
        $changeSQL = "UPDATE $this->bd_name SET $column = '$value' WHERE ID = $id";
        if ($this->bd->query($changeSQL) === TRUE) {
            echo "Change value successfully";
        } else {
            echo "Error: " . $this->bd->error;
        }
    }

    public function ChangeAllValueBD($id, $value1, $value2, $value3, $value4) {
        $changeAllSQL = "UPDATE $this->bd_name SET NAME = '$value1', Country = '$value2', Producer = '$value3', Price = $value4 WHERE ID = $id";
        if ($this->bd->query($changeAllSQL) === TRUE) {
            echo "Change value successfully";
        } else {
            echo "Error: " . $this->bd->error;
        }
    }
}

Лістинг коду:  html
<?php
$disabled = false;
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['ID']) && $_GET['ID'] >= 1) {
        $disabled = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PEДАгYBAHHA</title>
</head>
<body>
<h2>Add New Item</h2>
<form action="" method="post">
    <label for="Name">Name:</label><br>
    <input type="text" id="Name" name="Name"><br><br>

    <label for="Country">Country: </label><br>
    <input type="text" id="Country" name="Country"><br><br>

    <label for="Produce">Produce: </label><br>
    <input type="text" id="Produce" name="Produce"><br><br>

    <label for="Price">Price:</label><br>
    <input type="number" id="Price" name="Price"><br><br>

    <input type="submit" value="додавання Позиції" name="SUBMIT1"><br>
    <button><a href="shablon_html_php.php" style="text-decoration: none; color: black;">Назад</a></button><br>
    <input type="text" name="changeOnePole" id="changeOnePole" <?php if($disabled) echo "disabled"; ?>>
    <input type="submit" value="додавання зміни одного поля" name="SUBMIT2" class="submit" <?php if($disabled) echo "disabled"; ?>><br>
    <input type="submit" value="Зміна всіх полів" name="SUBMIT3" class="submit" <?php if($disabled) echo "disabled"; ?>><br>
    <input type="text" name="delete" id="delete" placeholder="id for delete">
    <input type="submit" value="Видалення поля" name="SUBMIT4" class="submit"><br>
</form>
</body>
</html>

<?php
require_once 'index.php';

$bd = new BD ("Products.items1");
$bd->ConnectBD();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST["SUBMIT1"])) {
        switch ($_POST["SUBMIT1"]) {
            case "додавання Позиції":
                if (!empty($_POST['Name']) && !empty($_POST['Country']) && !empty($_POST['Produce']) && !empty($_POST['Price'])) {
                    $bd->InsertToBD($_POST["Name"], $_POST['Country'], $_POST['Produce'], intval($_POST['Price']));
                } else {
                    echo "Please fill in all fields for додавання Позиції";
                }
                break;
         
        }
    }
    if(isset($_POST["SUBMIT2"])) {
        switch ($_POST["SUBMIT2"]) {
            case "додавання зміни одного поля":
                if (isset($_GET['answers']) && $_GET['ID'] >= 1 && !empty($_POST['changeOnePole'])) {
                    $bd->ChangeValueBD($_GET['ID'], $_GET['answers'], $_POST['changeOnePole']);
                } else {
                    $disabled = false;
                }
                break;
           
        }
    }
    if(isset($_POST["SUBMIT3"])) {
        switch ($_POST["SUBMIT3"]) {
            case "Зміна всіх полів":
                if (!empty($_POST['Name']) && !empty($_POST['Country']) && !empty($_POST['Produce']) && !empty($_POST['Price']) && isset($_GET['ID']) && $_GET['ID'] >= 1) {
                    $bd->ChangeAllValueBD(intval($_GET['ID']), $_POST["Name"], $_POST['Country'], $_POST['Produce'], intval($_POST['Price']));
                } else {
                    echo "Please fill in all fields for Зміна всіх полів";
                }
                break;
         
        }
    }
    if(isset($_POST["SUBMIT4"])) {
        switch ($_POST["SUBMIT4"]) {
            case "Видалення поля":
                if (!empty($_POST['delete'])) {
                    $bd->DeleteFromBD($_POST['delete']);
                }
                break;
          
        }
    }
}

$bd->DisconnectBD();
?>

Лістинг коду:  php
<?php require_once('index.php'); ?>

<?php
$bd = new BD('Products.items1');
$bd->ReadAllBD();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WORK WITH BD</title>
    <style>
        table {
            width: 25%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

<form action="form.php" method="get">

    <label for="answers">Choose an option:</label><br>
    <select name="answers" id="answers">
        <option value="NAME">Name</option>
        <option value="Country">Country</option>
        <option value="Producer">Producer</option>
        <option value="price">Price</option>
    </select><br><br>
    <input type="submit" value="Виконання змін">
</form>

</body>
</html>
