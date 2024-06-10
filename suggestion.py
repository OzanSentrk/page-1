import mysql.connector
import random
import json

# Veritabanı bağlantısı
def get_meals_from_db():
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="calori"
    )
    cursor = conn.cursor(dictionary=True)
    cursor.execute("SELECT id, name, category, calories, carbohydrate, sugar, protein, fat, image FROM meals_updated")
    meals = cursor.fetchall()
    conn.close()
    return meals

# Yemekleri kategorilere göre ayırma
def categorize_meals(meals):
    categorized_meals = {'Breakfast': [], 'Lunch/Snacks': [], 'One Dish Meal': []}
    for meal in meals:
        if meal['category'] == 'Breakfast':
            categorized_meals['Breakfast'].append(meal)
        elif meal['category'] == 'Lunch/Snacks':
            categorized_meals['Lunch/Snacks'].append(meal)
        elif meal['category'] == 'One Dish Meal':
            categorized_meals['One Dish Meal'].append(meal)
    return categorized_meals

# Başlangıç popülasyonu oluşturma
def create_initial_population(size, categorized_meals):
    population = []
    breakfast_options = categorized_meals['Breakfast']
    lunch_snacks_options = categorized_meals['Lunch/Snacks']
    one_dish_meal_options = categorized_meals['One Dish Meal']

    for _ in range(size * 5):  # Popülasyonun başlangıcında çeşitliliği artırmak için boyutu artırdık
        individual = {
            'Breakfast': random.choice(breakfast_options),
            'Lunch/Snacks': random.choice(lunch_snacks_options),
            'One Dish Meal': random.choice(one_dish_meal_options),
            'Breakfast Portion': random.uniform(1, 3),  # 1 ile 3 arasında rastgele bir porsiyon miktarı
            'Lunch/Snacks Portion': random.uniform(1, 3),  # 1 ile 3 arasında rastgele bir porsiyon miktarı
            'One Dish Meal Portion': random.uniform(1, 3)  # 1 ile 3 arasında rastgele bir porsiyon miktarı
        }
        population.append(individual)

    random.shuffle(population)
    return population[:size]  # Popülasyon boyutunu belirtilen boyuta indirgeme

# Uygunluk fonksiyonu
def fitness(individual, user_requirements):
    # Kullanıcı gereksinimlerinin yüzdeleri
    breakfast_requirements = {
        'calories': user_requirements['daily_calories'] * 0.25,
        'carbs': user_requirements['carb_grams'] * 0.25,
        'protein': user_requirements['protein_grams'] * 0.25,
        'fat': user_requirements['fat_grams'] * 0.25
    }
    lunch_requirements = {
        'calories': user_requirements['daily_calories'] * 0.40,
        'carbs': user_requirements['carb_grams'] * 0.40,
        'protein': user_requirements['protein_grams'] * 0.40,
        'fat': user_requirements['fat_grams'] * 0.40
    }
    dinner_requirements = {
        'calories': user_requirements['daily_calories'] * 0.35,
        'carbs': user_requirements['carb_grams'] * 0.35,
        'protein': user_requirements['protein_grams'] * 0.35,
        'fat': user_requirements['fat_grams'] * 0.35
    }

    # Porsiyon miktarlarını dikkate alarak toplam değerleri hesapla
    total_calories = (
        individual['Breakfast']['calories'] * individual['Breakfast Portion'] +
        individual['Lunch/Snacks']['calories'] * individual['Lunch/Snacks Portion'] +
        individual['One Dish Meal']['calories'] * individual['One Dish Meal Portion']
    )
    total_carbs = (
        individual['Breakfast']['carbohydrate'] * individual['Breakfast Portion'] +
        individual['Lunch/Snacks']['carbohydrate'] * individual['Lunch/Snacks Portion'] +
        individual['One Dish Meal']['carbohydrate'] * individual['One Dish Meal Portion']
    )
    total_protein = (
        individual['Breakfast']['protein'] * individual['Breakfast Portion'] +
        individual['Lunch/Snacks']['protein'] * individual['Lunch/Snacks Portion'] +
        individual['One Dish Meal']['protein'] * individual['One Dish Meal Portion']
    )
    total_fat = (
        individual['Breakfast']['fat'] * individual['Breakfast Portion'] +
        individual['Lunch/Snacks']['fat'] * individual['Lunch/Snacks Portion'] +
        individual['One Dish Meal']['fat'] * individual['One Dish Meal Portion']
    )

    # Uygunluk puanını hesapla
    breakfast_fitness = (
        abs(breakfast_requirements['calories'] - total_calories * 0.25) +
        abs(breakfast_requirements['carbs'] - total_carbs * 0.25) +
        abs(breakfast_requirements['protein'] - total_protein * 0.25) +
        abs(breakfast_requirements['fat'] - total_fat * 0.25)
    )

    lunch_fitness = (
        abs(lunch_requirements['calories'] - total_calories * 0.40) +
        abs(lunch_requirements['carbs'] - total_carbs * 0.40) +
        abs(lunch_requirements['protein'] - total_protein * 0.40) +
        abs(lunch_requirements['fat'] - total_fat * 0.40)
    )

    dinner_fitness = (
        abs(dinner_requirements['calories'] - total_calories * 0.35) +
        abs(dinner_requirements['carbs'] - total_carbs * 0.35) +
        abs(dinner_requirements['protein'] - total_protein * 0.35) +
        abs(dinner_requirements['fat'] - total_fat * 0.35)
    )

    total_fitness = breakfast_fitness + lunch_fitness + dinner_fitness
    return total_fitness

# Seçilim
def selection(population, user_requirements):
    population = sorted(population, key=lambda x: fitness(x, user_requirements))
    return population[:len(population)//2]

# Çaprazlama
def crossover(parent1, parent2):
    child = {
        'Breakfast': random.choice([parent1['Breakfast'], parent2['Breakfast']]),
        'Lunch/Snacks': random.choice([parent1['Lunch/Snacks'], parent2['Lunch/Snacks']]),
        'One Dish Meal': random.choice([parent1['One Dish Meal'], parent2['One Dish Meal']]),
        'Breakfast Portion': (parent1['Breakfast Portion'] + parent2['Breakfast Portion']) / 2,
        'Lunch/Snacks Portion': (parent1['Lunch/Snacks Portion'] + parent2['Lunch/Snacks Portion']) / 2,
        'One Dish Meal Portion': (parent1['One Dish Meal Portion'] + parent2['One Dish Meal Portion']) / 2
    }
    return child

# Mutasyon
def mutate(individual, categorized_meals):
    meal_type = random.choice(['Breakfast', 'Lunch/Snacks', 'One Dish Meal'])
    if random.random() < 0.5:
        individual[meal_type] = random.choice(categorized_meals[meal_type])
    else:
        individual[meal_type + ' Portion'] += random.uniform(-0.5, 0.5)  # Porsiyon miktarını küçük bir aralıkta değiştir
        individual[meal_type + ' Portion'] = max(1, min(individual[meal_type + ' Portion'], 3))  # Porsiyon miktarını 1 ile 3 arasında sınırla
    return individual

# Genetik algoritma
def genetic_algorithm(generations, population_size, categorized_meals, user_requirements):
    population = create_initial_population(population_size, categorized_meals)

    for generation in range(generations):
        population = selection(population, user_requirements)
        new_population = []

        while len(new_population) < population_size:
            parent1, parent2 = random.sample(population, 2)
            child = crossover(parent1, parent2)
            child = mutate(child, categorized_meals)
            new_population.append(child)

        population = new_population

    best_individual = min(population, key=lambda x: fitness(x, user_requirements))
    return best_individual

# Ana program
if __name__ == "__main__":
    meals = get_meals_from_db()
    categorized_meals = categorize_meals(meals)

    # Kullanıcının günlük ihtiyacı (JSON dosyasından okuma)
    with open('user_requirements.json', 'r') as f:
        user_requirements = json.load(f)

    # Genetik algoritmayı çalıştırma
    best_meal_plan = genetic_algorithm(generations=100, population_size=50, categorized_meals=categorized_meals, user_requirements=user_requirements)
    
    # En iyi yemek planını ve uygunluk puanını JSON formatında çıktı verme
    result = {
        'best_meal_plan': best_meal_plan,
        'fitness_score': fitness(best_meal_plan, user_requirements)
    }
    print(json.dumps(result, indent=4))
