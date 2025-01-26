from flask import Flask, jsonify, request
import mysql.connector
from main import chatbot
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

# Configuration de la base de données
db_config = {
    'user': 'admin',          # Utilisateur MySQL
    'password': 'ouss3110',          # Mot de passe MySQL (vide par défaut dans XAMPP)
    'host': 'localhost',     # Hôte de la base de données
    'database': 'chatbot',    # Nom de la base de données
    'raise_on_warnings': True
}

def get_db_connection():
    conn = mysql.connector.connect(**db_config)
    return conn

# Route pour insérer une nouvelle question dans la table Questions
@app.route('/add_question', methods=['POST'])
def add_question():
    data = request.json  # Récupérer les données JSON envoyées dans la requête
    question = data.get('question')
    answer = data.get('answer')

    if not question or not answer:
        return jsonify({"error": "Les champs 'question' et 'answer' sont obligatoires"}), 400
    conn = get_db_connection()
    cursor = conn.cursor()

    cursor.execute(
        "INSERT INTO Questions (question, answer) VALUES (%s, %s)",
        (question, answer)
    )
    conn.commit()
    cursor.close()
    conn.close()
    return jsonify({"message": "Question ajoutée avec succès"}), 201

@app.route('/fetch_historique', methods=['GET'])
def fetch_historique():
    
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)

    cursor.execute("SELECT * FROM Historique")
    historique = cursor.fetchall()
    cursor.close()
    conn.close()

    return jsonify(historique)

@app.route('/ask_question', methods=['POST'])
def ask_question():
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)
    cursor.execute("SELECT * FROM questions")
    context = cursor.fetchall()
    if not request.is_json:
        return jsonify({"error": "Invalid input. Please send JSON data."}), 400

    data = request.get_json()

    # Ensure the question is provided
    question = data.get("question")
    if not question:
        return jsonify({"error": "Missing 'question' in request."}), 400
    response=chatbot(question,context)


    return jsonify({"answer": response}),200

if __name__ == '__main__':
    app.run(host='0.0.0.0',
        debug=True)