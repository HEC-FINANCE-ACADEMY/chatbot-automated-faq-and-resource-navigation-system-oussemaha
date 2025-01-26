from langchain_community.chat_models import ChatOllama
from langchain.schema import HumanMessage, SystemMessage
import json 
# Initialize the Ollama model

def chatbot(question):
    chat_model = ChatOllama(model="llama3.1_custom1",temperature=0.1)

    file_path = 'dataset.json'

# Open the JSON file and load its content
    with open(file_path, 'r') as file:
        data = json.load(file)
        print("Chatbot is ready! Type  to end the conversation.")
        # Create a message object with the user's input
    messages = [
            SystemMessage(content=f"""Vous êtes un chatbot intégré au site web d'une université appelée IHEC, Institut des Hautes Études Commerciales, et votre tâche est de répondre aux questions des étudiants et de leur fournir les informations nécessaires 
                          à partir d'un esemble de réponses pré-définis.
Vous trouverez ci-dessous un ensemble de données contenant les questions possibles des étudiants ainsi que les réponses que vous devez fournir :
{data}.
Votre devez comparer la question de l'étudiant avec les questions de l'ensemble de données et retourner la réponse correspondante exactement, sans aucun commentaire.
si vous ne trouvez pas une réponse exacte, vous devez retourner 'Je ne peux pas répondre à cette question'."""),
            HumanMessage(content=question)
        ]
            # Get the chatbot's response
    response = chat_model.invoke(messages)
    return response.content
# Run the chatbot