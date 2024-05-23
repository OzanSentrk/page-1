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
        <div class="progress-container">
          <div class="progress-bar" id="carbohydrateProgress"></div>
        </div>
        <p class="g-o8p" id="dailyProtein"></p>
        <p class="g-JLU" id="dailyFat"></p>
      </div>
    </div>
  </div>
  <div class="auto-group-mkma-jwa clickable-icon" onclick="navigateToFrame3()">
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
  function navigateToDesktop3() {
    window.location.href = "desktop-3.html";
  }

  function navigateToFrame3() {
    window.location.href = "frame-3.html";
  }

  document.addEventListener('DOMContentLoaded', function() {
    // Oturumdaki değerleri JavaScript'e alın
    var dailyNeeds = {
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
  });

  var xhr = new XMLHttpRequest();
  xhr.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var dailyValues = JSON.parse(xhr.responseText);
      console.log("Günlük Değerler: " + JSON.stringify(dailyValues));
      
      // Günlük değerleri HTML içindeki uygun yerlere yerleştirme
      document.getElementById('dailyCalories').innerHTML += "<br>Alınan: " + dailyValues.calories + " kcal";
      document.getElementById('dailyProtein').innerHTML += "<br>Alınan: " + dailyValues.protein + " g";
      document.getElementById('dailyFat').innerHTML += "<br>Alınan: " + dailyValues.fat + " g";
      document.getElementById('dailyCarbohydrate').innerHTML += "<br>Alınan: " + dailyValues.carbohydrate + " g";
    
    
      var carbProgress = (dailyValues.carbohydrate / dailyNeeds.carbohydrate) * 100;
      document.getElementById('carbohydrateProgress').style.width = carbProgress + '%';

    }
  };
  xhr.open("GET", "dailyIntake.php", true);
  xhr.send();
</script>
</body>
</html>
