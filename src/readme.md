# Chatbot with LLaMA3.1 8B, Ollama, LangChain, and Flask

This project is a chatbot built using the LLaMA3.1 8B model, deployed locally using Ollama, and integrated into a Flask application using the LangChain framework. The chatbot responds to user queries based on a prepared dataset stored in a MySQL database.

## Table of Contents
- [Overview](#overview)
- [Features](#features)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)
- [Database Schema](#database-schema)
- [Prompt Engineering](#prompt-engineering)
- [Contributing](#contributing)
- [License](#license)

## Overview
This project leverages the LLaMA3.1 8B model to create a conversational AI chatbot. The model is deployed locally using Ollama, and the LangChain framework is used to integrate it into a Flask application. The chatbot responds to user queries based on a prepared dataset stored in a MySQL database.

## Features
- **Local Deployment**: The LLaMA3.1 8B model is deployed locally using Ollama.
- **LangChain Integration**: The LangChain framework manages the interaction between the Flask app and the LLaMA model.
- **MySQL Database**: A prepared dataset of questions and answers is stored in a MySQL database.
- **Flask Web Application**: The chatbot is accessible via a Flask web app.
- **Prompt Engineering**: Custom system messages and prompt engineering guide the model's responses.

## Prerequisites
Before you begin, ensure you have the following installed:
- Python 3.8+
- MySQL Server
- Ollama (for local deployment of LLaMA3.1 8B)
- Flask
- LangChain
- MySQL Connector/Python

## Installation
1. **Clone the repository:**
   ```bash
   git clone https://github.com/yourusername/your-repo-name.git
   cd your-repo-name