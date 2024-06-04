<?php
// Database connection using PDO
$dsn = 'mysql:host=localhost;dbname=calori';
$username = 'root';
$password = '';
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
);

$conn = new PDO($dsn, $username, $password, $options);

function oturumKontrol(){
    if(empty($_SESSION["user_name"])){
        echo "Lütfen Giriş Yapınız. Giriş sayfasına yönlendiriliyorsunuz...";
        header("refresh:0;url=login.php");
        exit();

    }

}

// Veritabanı bağlantısı
function get_meals_from_db() {
    $conn = new mysqli("localhost", "root", "", "calori");
    if ($conn->connect_error) {
        die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
    }

    $sql = "SELECT id, name, category, calories, carbohydrate, sugar, protein, fat, image FROM meals";
    $result = $conn->query($sql);

    $meals = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $meals[] = $row;
        }
    }
    $conn->close();
    return $meals;
}

// Yemekleri kategorilere göre ayırma
function categorize_meals($meals) {
    $categorized_meals = ['Breakfast' => [], 'Lunch/Snacks' => [], 'One Dish Meal' => []];
    foreach ($meals as $meal) {
        if ($meal['category'] == 'Breakfast') {
            $categorized_meals['Breakfast'][] = $meal;
        } elseif ($meal['category'] == 'Lunch/Snacks') {
            $categorized_meals['Lunch/Snacks'][] = $meal;
        } elseif ($meal['category'] == 'One Dish Meal') {
            $categorized_meals['One Dish Meal'][] = $meal;
        }
    }
    return $categorized_meals;
}

// Başlangıç popülasyonu oluşturma
function create_initial_population($size, $categorized_meals) {
    $population = [];
    $breakfast_options = $categorized_meals['Breakfast'];
    $lunch_snacks_options = $categorized_meals['Lunch/Snacks'];
    $one_dish_meal_options = $categorized_meals['One Dish Meal'];

    if (empty($breakfast_options) || empty($lunch_snacks_options) || empty($one_dish_meal_options)) {
        return $population; // Eğer herhangi bir kategori boşsa, boş popülasyon döndür
    }

    for ($i = 0; $i < $size * 5; $i++) { // Popülasyonun başlangıcında çeşitliliği artırmak için boyutu artırdık
        $individual = [
            'Breakfast' => $breakfast_options[array_rand($breakfast_options)],
            'Lunch/Snacks' => $lunch_snacks_options[array_rand($lunch_snacks_options)],
            'One Dish Meal' => $one_dish_meal_options[array_rand($one_dish_meal_options)],
            'Breakfast Portion' => rand(10, 30) / 10.0, // 1 ile 3 arasında rastgele bir porsiyon miktarı
            'Lunch/Snacks Portion' => rand(10, 30) / 10.0, // 1 ile 3 arasında rastgele bir porsiyon miktarı
            'One Dish Meal Portion' => rand(10, 30) / 10.0 // 1 ile 3 arasında rastgele bir porsiyon miktarı
        ];
        $population[] = $individual;
    }

    shuffle($population);
    return array_slice($population, 0, $size); // Popülasyon boyutunu belirtilen boyuta indirgeme
}

// Uygunluk fonksiyonu
function fitness($individual, $user_requirements) {
    // Kullanıcı gereksinimlerinin yüzdeleri
    $breakfast_requirements = array_map(function($value) { return $value * 0.25; }, $user_requirements);
    $lunch_requirements = array_map(function($value) { return $value * 0.40; }, $user_requirements);
    $dinner_requirements = array_map(function($value) { return $value * 0.35; }, $user_requirements);

    // Porsiyon miktarlarını dikkate alarak toplam değerleri hesapla
    $total_calories = (
        $individual['Breakfast']['calories'] * $individual['Breakfast Portion'] +
        $individual['Lunch/Snacks']['calories'] * $individual['Lunch/Snacks Portion'] +
        $individual['One Dish Meal']['calories'] * $individual['One Dish Meal Portion']
    );
    $total_carbs = (
        $individual['Breakfast']['carbohydrate'] * $individual['Breakfast Portion'] +
        $individual['Lunch/Snacks']['carbohydrate'] * $individual['Lunch/Snacks Portion'] +
        $individual['One Dish Meal']['carbohydrate'] * $individual['One Dish Meal Portion']
    );
    $total_protein = (
        $individual['Breakfast']['protein'] * $individual['Breakfast Portion'] +
        $individual['Lunch/Snacks']['protein'] * $individual['Lunch/Snacks Portion'] +
        $individual['One Dish Meal']['protein'] * $individual['One Dish Meal Portion']
    );
    $total_fat = (
        $individual['Breakfast']['fat'] * $individual['Breakfast Portion'] +
        $individual['Lunch/Snacks']['fat'] * $individual['Lunch/Snacks Portion'] +
        $individual['One Dish Meal']['fat'] * $individual['One Dish Meal Portion']
    );

    // Uygunluk puanını hesapla
    $breakfast_fitness = (
        abs($breakfast_requirements['calories'] - $individual['Breakfast']['calories'] * $individual['Breakfast Portion']) +
        abs($breakfast_requirements['carbs'] - $individual['Breakfast']['carbohydrate'] * $individual['Breakfast Portion']) +
        abs($breakfast_requirements['protein'] - $individual['Breakfast']['protein'] * $individual['Breakfast Portion']) +
        abs($breakfast_requirements['fat'] - $individual['Breakfast']['fat'] * $individual['Breakfast Portion'])
    );

    $lunch_fitness = (
        abs($lunch_requirements['calories'] - $individual['Lunch/Snacks']['calories'] * $individual['Lunch/Snacks Portion']) +
        abs($lunch_requirements['carbs'] - $individual['Lunch/Snacks']['carbohydrate'] * $individual['Lunch/Snacks Portion']) +
        abs($lunch_requirements['protein'] - $individual['Lunch/Snacks']['protein'] * $individual['Lunch/Snacks Portion']) +
        abs($lunch_requirements['fat'] - $individual['Lunch/Snacks']['fat'] * $individual['Lunch/Snacks Portion'])
    );

    $dinner_fitness = (
        abs($dinner_requirements['calories'] - $individual['One Dish Meal']['calories'] * $individual['One Dish Meal Portion']) +
        abs($dinner_requirements['carbs'] - $individual['One Dish Meal']['carbohydrate'] * $individual['One Dish Meal Portion']) +
        abs($dinner_requirements['protein'] - $individual['One Dish Meal']['protein'] * $individual['One Dish Meal Portion']) +
        abs($dinner_requirements['fat'] - $individual['One Dish Meal']['fat'] * $individual['One Dish Meal Portion'])
    );

    $total_fitness = $breakfast_fitness + $lunch_fitness + $dinner_fitness;
    return $total_fitness;
}

// Seçilim
function selection($population, $user_requirements) {
    usort($population, function($a, $b) use ($user_requirements) {
        return fitness($a, $user_requirements) - fitness($b, $user_requirements);
    });
    return array_slice($population, 0, count($population) / 2);
}

// Çaprazlama
function crossover($parent1, $parent2) {
    $child = [
        'Breakfast' => (rand(0, 1) == 0) ? $parent1['Breakfast'] : $parent2['Breakfast'],
        'Lunch/Snacks' => (rand(0, 1) == 0) ? $parent1['Lunch/Snacks'] : $parent2['Lunch/Snacks'],
        'One Dish Meal' => (rand(0, 1) == 0) ? $parent1['One Dish Meal'] : $parent2['One Dish Meal'],
        'Breakfast Portion' => ($parent1['Breakfast Portion'] + $parent2['Breakfast Portion']) / 2,
        'Lunch/Snacks Portion' => ($parent1['Lunch/Snacks Portion'] + $parent2['Lunch/Snacks Portion']) / 2,
        'One Dish Meal Portion' => ($parent1['One Dish Meal Portion'] + $parent2['One Dish Meal Portion']) / 2
    ];
    return $child;
}

// Mutasyon
function mutate($individual, $categorized_meals) {
    $meal_types = ['Breakfast', 'Lunch/Snacks', 'One Dish Meal'];
    $meal_type = $meal_types[array_rand($meal_types)];
    if (rand(0, 1) < 0.5) {
        $individual[$meal_type] = $categorized_meals[$meal_type][array_rand($categorized_meals[$meal_type])];
    } else {
        $individual[$meal_type . ' Portion'] += rand(-5, 5) / 10.0; // Porsiyon miktarını küçük bir aralıkta değiştir
        $individual[$meal_type . ' Portion'] = max(1, min($individual[$meal_type . ' Portion'], 3)); // Porsiyon miktarını 1 ile 3 arasında sınırla
    }
    return $individual;
}

// Genetik algoritma
function genetic_algorithm($generations, $population_size, $categorized_meals, $user_requirements) {
    $population = create_initial_population($population_size, $categorized_meals);

    if (empty($population)) {
        return null; // Eğer başlangıç popülasyonu boşsa, null döndür
    }

    for ($generation = 0; $generation < $generations; $generation++) {
        $population = selection($population, $user_requirements);
        $new_population = [];

        while (count($new_population) < $population_size) {
            $parents = array_rand($population, 2);
            $parent1 = $population[$parents[0]];
            $parent2 = $population[$parents[1]];
            $child = crossover($parent1, $parent2);
            $child = mutate($child, $categorized_meals);
            $new_population[] = $child;
        }

        $population = $new_population;
    }

    $best_individual = array_reduce($population, function($best, $current) use ($user_requirements) {
        return (fitness($current, $user_requirements) < fitness($best, $user_requirements)) ? $current : $best;
    }, $population[0]);

    return $best_individual;
}
?>