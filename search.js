function searchFood() {
    var searchTerm = document.getElementById("searchInput").value;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "search.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var results = JSON.parse(xhr.responseText);
            displayResults(results);
        }
    };
    xhr.send("search_name=" + searchTerm); // Değişiklik yapıldı, search_name olarak gönderiliyor.
  }
  

function displayResults(results) {
    var searchResultsDiv = document.getElementById("searchResults");
    searchResultsDiv.innerHTML = "";
    if (results.length > 0) {
        var ul = document.createElement("ul");
        for (var i = 0; i < results.length && i < 5; i++) {
            var li = document.createElement("li");
            li.textContent = results[i].Name; // Değişiklik yapıldı, results[i].Name olarak alınıyor.
            ul.appendChild(li);
        }
        searchResultsDiv.appendChild(ul);
    } else {
        searchResultsDiv.textContent = "Arama sonucu bulunamadı.";
    }
}
