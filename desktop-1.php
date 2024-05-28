<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <link rel="icon" href="/favicon.ico" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="theme-color" content="#000000" />
  <title>Desktop - 1</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter%3A400"/>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro%3A400"/>
  <link rel="stylesheet" href="./styles/desktop-1.css"/>
</head>
<body>
<div class="desktop-1-c8L">
  <div class="auto-group-imbj-cGk">
    <div class="auto-group-rtel-3Fa">
      <p class="kahvalt-uoa clickable-icon" onclick="navigateToDesktop3()">
        <span class="kahvalt-uoa-sub-0">Kahvaltı  </span>
        <span class="kahvalt-uoa-sub-1">    </span>
      </p>
      <img class="icons8-add-50-2-1-7aC clickable-icon" src="./assets/icons8-add-50-2-1.png" onclick="navigateToDesktop3()"/>
    </div>
    <div class="blue_box">
      <div class="auto-group-ur4c-ukx">
        <p class="item-1150-BiU">
          <span class="item-1150-BiU-sub-1" id="dailyCalories"></span>
          <div class="calories-progress-container">
          <div class="calories-progress-bar" id="caloriesProgress"></div>
        </div>
        </p>
        <p class="kalan-WGt">
          <span class="kalan-WGt-sub-0" id="dailyCaloriesValue"></span>
          <span class="kalan-WGt-sub-1"></span>
        </p>
      </div>
      <div class="auto-group-r6z8-ag4">
        <p class="karbonhidrat-gDJ">Karbonhidrat</p>
        <p class="protein-zjn">Protein</p>
        <p class="ya-YFW">Yağ</p>
      </div>
      <div class="auto-group-8vrg-w6c">
        <p class="g-tGk" id="dailyCarbohydrate"></p>
        <div class="Carb-progress-container">
          <div class="Carb-progress-bar" id="carbohydrateProgress"></div>
        </div>
        <p class="g-o8p" id="dailyProtein"></p>
        <div class="protein-progress-container">
          <div class="protein-progress-bar" id="proteinProgress"></div>
        </div>
        <p class="g-JLU" id="dailyFat"></p>
        <div class="fat-progress-container">
          <div class="fat-progress-bar" id="fatProgress"></div>
        </div>
        
      </div>
    </div>
  </div>
  <div class="auto-group-mkma-jwa clickable-icon" onclick="getUserInfo()" onclick="navigateToFrame3()">
    <div class="auto-group-ts56-rmJ">
      <img class="logout-3-AX6" src="./assets/logout-3.png"/>
      <p class="k-yap-RC8">Çıkış Yap</p>
    </div>
    <div class="auto-group-t1bj-KHW clickable-icon" onclick="navigateToDesktop3()">
      <img class="rectangle-10-Ddn" src="./assets/rectangle-10-oVW.png"/>
      <p class="yemek-nerisi-al-Mjz">
        Yemek Önerisi Al
        <br/>
      </p>
    </div>
    <div class="auto-group-bxms-tE8">
      <img class="rectangle-2-QiG" src="./assets/rectangle-2-LFN.png"/>
      <p class="profil-wiC">Profil</p>
    </div>
    <div class="auto-group-mwnx-p1J">
      <img class="icons8-home-50-1-YT6" src="./assets/icons8-home-50-1.png"/>
      <p class="ana-sayfa-Ube">Ana Sayfa</p>
    </div>
  </div>
  <div class="auto-group-qmps-o88 clickable-icon" onclick="navigateToDesktop3()">
    <p class="le-yemei-Uzx">
      Öğle Yemeği
      <br/>
    </p>
    <img class="icons8-add-50-2-2-BeU clickable-icon" src="./assets/icons8-add-50-2-2.png" onclick="navigateToDesktop3()"/>
  </div>
  <div class="auto-group-mtix-6Fe clickable-icon" onclick="navigateToDesktop3()">
    <p class="attrmalk-phS">Atıştırmalık</p>
    <img class="icons8-add-50-2-3-jpQ clickable-icon" src="./assets/icons8-add-50-2-3.png" onclick="navigateToDesktop3()"/>
  </div>
  <div class="auto-group-tpcc-Umz clickable-icon" onclick="navigateToDesktop3()">
    <p class="akam-yemei-Ptx">
      <span class="akam-yemei-Ptx-sub-0">Akşam Yemeği  </span>
      <span class="akam-yemei-Ptx-sub-1">    </span>
    </p>
    <img class="icons8-add-50-2-4-PR6 clickable-icon" src="./assets/icons8-add-50-2-4.png" onclick="navigateToDesktop3()"/>
  </div>
</div>

<script>
  var dailyNeeds;

  function navigateToDesktop3() {
    window.location.href = "desktop-3.html";
  }

  function navigateToFrame3() {
    window.location.href = "frame-3.html";
  }

  var dailyNeeds;
var dailyValues;

document.addEventListener('DOMContentLoaded', function() {
    // Oturumdaki değerleri JavaScript'e alın
    dailyNeeds = {
      calories: "<?php echo $_SESSION['calorie']; ?>",
      carbohydrate: "<?php echo $_SESSION['carbohydrate']; ?>",
      protein: "<?php echo $_SESSION['protein']; ?>",
      fat: "<?php echo $_SESSION['fat']; ?>"
    };

    // Oturumdaki değerleri konsolda görüntüleyin
    console.log("Günlük İhtiyaçlar: ", dailyNeeds);

    // HTML içindeki uygun alanlara değerleri yerleştirin
    document.getElementById('dailyCalories').innerHTML += "Günlük Kalori İhtiyacı: " + dailyNeeds.calories + " kcal";
    document.getElementById('dailyCarbohydrate').innerHTML += "Günlük Karbonhidrat İhtiyacı: " + dailyNeeds.carbohydrate + " g";
    document.getElementById('dailyProtein').innerHTML += "Günlük Protein İhtiyacı: " + dailyNeeds.protein + " g";
    document.getElementById('dailyFat').innerHTML += "Günlük Yağ İhtiyacı: " + dailyNeeds.fat + " g";

    // dailyValues için XMLHttpRequest ile ayrı bir istek yapın
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        dailyValues = JSON.parse(xhr.responseText);
        console.log("Günlük Değerler: " + JSON.stringify(dailyValues));
        
        // Günlük değerleri HTML içindeki uygun yerlere yerleştirme
        document.getElementById('dailyCalories').innerHTML += "<br>Alınan: " + dailyValues.calories + " kcal";
        document.getElementById('dailyProtein').innerHTML += "<br>Alınan: " + dailyValues.protein + " g";
        document.getElementById('dailyFat').innerHTML += "<br>Alınan: " + dailyValues.fat + " g";
        document.getElementById('dailyCarbohydrate').innerHTML += "<br>Alınan: " + dailyValues.carbohydrate + " g";
      
        // Progress barı güncelleme
        var carbProgress = (dailyValues.carbohydrate / dailyNeeds.carbohydrate) * 100;
        if (carbProgress >= 100) {
          document.getElementById('carbohydrateProgress').style.width = '100%';
          document.getElementById('carbohydrateProgress').style.backgroundColor = '#EA3636'; // Set progress bar color to red
        } else {
          document.getElementById('carbohydrateProgress').style.width = carbProgress + '%';
        }
          
        var proteinProgress = (dailyValues.protein / dailyNeeds.protein) * 100;
        if (proteinProgress >= 100) {
          document.getElementById('proteinProgress').style.width = '100%';
          document.getElementById('proteinProgress').style.backgroundColor = '#EA3636'; // Set progress bar color to red
        } else {
          document.getElementById('proteinProgress').style.width = proteinProgress + '%';
        }

        var fatProgress = (dailyValues.fat / dailyNeeds.fat) * 100;
        if (fatProgress >= 100) {
          document.getElementById('fatProgress').style.width = '100%';
          document.getElementById('fatProgress').style.backgroundColor = '#EA3636'; // Set progress bar color to red
        } else {
          document.getElementById('fatProgress').style.width = fatProgress + '%';
        }
          
        var caloriesProgress = (dailyValues.calories / dailyNeeds.calories) * 100;
        if (caloriesProgress >= 100) {
          document.getElementById('caloriesProgress').style.width = '100%';
          document.getElementById('caloriesProgress').style.backgroundColor = '#EA3636'; // Set progress bar color to red
        } else {
          document.getElementById('caloriesProgress').style.width = caloriesProgress + '%';
        }

        // Hem dailyNeeds hem de dailyValues'ı Python'a gönderme
        sendToPython();
      }
    };
    xhr.open("GET", "dailyIntake.php", true);
    xhr.send();
});

function sendToPython() {
    fetch('http://127.0.0.1:5000/python_endpoint', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ dailyNeeds: dailyNeeds ,dailyValues: dailyValues })
    })
    .then(response => response.json())
    .then(data => {
      console.log('Python tarafından gelen yanıt:', data);
      console.log('Python tarafından gelen mesaj:', data.message);
    })
    .catch(error => {
      console.error('Hata:', error);
    });
}



  
</script>
</body>
</html>
