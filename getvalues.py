from flask import Flask, request, jsonify
from flask_cors import CORS

app = Flask(__name__)
CORS(app) # CORS desteğini etkinleştir

@app.route('/python_endpoint', methods=['POST'])
def python_endpoint():
    data = request.json
    print("Gelen veri:", data)  # Gelen veriyi konsola yazdır
    dailyNeeds = data.get('dailyNeeds')
    dailyValues = data.get('dailyValues')

    # Burada gelen veriyi işleyebilirsiniz
    print("Gelen dailyNeeds:", dailyNeeds)
    print("Gelen dailyValues:", dailyValues)
    # İşlenen veriyi bir yanıt olarak gönderebilirsiniz
    response = jsonify({"message": "Veriler başarıyla alındı."})
    response.headers.add('Access-Control-Allow-Origin', '*') # Herkese izin ver

    return response


if __name__ == '__main__':
    app.run(debug=True)
