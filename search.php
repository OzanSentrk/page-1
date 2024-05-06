<?php
include 'connect.php';

$searchTerm = '%' . $_POST['search_name'] . '%';

$search_query = $link->prepare("SELECT Name, Calories, CarbohydrateContent, CholesterolContent, ProteinContent FROM foodcontents WHERE Name LIKE ? LIMIT 20");
$search_query->bind_param('s', $searchTerm);
$search_query->execute();
$search_query->store_result();
$search_rows = $search_query->num_rows;
$search_query->bind_result($name, $calories, $carbohydrateContent, $cholesterolContent, $proteinContent);

$results = array();
if ($search_rows > 0) {
    while ($search_query->fetch()) {
        $results[] = array(
            "Name" => $name,
            "Calories" => $calories,
            "CarbohydrateContent" => $carbohydrateContent,
            "CholesterolContent" => $cholesterolContent,
            "ProteinContent" => $proteinContent
        );
    }
}

$search_query->close();
$link->close();

echo json_encode($results);
?>
