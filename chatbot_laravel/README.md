# FAQ Management System Documentation

## Table of Contents

1. **Project Setup**
2. **Database Structure**
3. **Endpoints**
4. **JSON Import Format**
5. **Authentication**
6. **Validation Rules**
7. **Deployment Notes**

---

## 1. Project Setup

### Requirements

-   PHP 8.1+
-   Composer
-   Node.js 16+
-   MySQL 8+
-   Laravel 11+

### Installation

```bash
# Clone repository
git clone [your-repo-url]
cd faq-system

# Install dependencies
composer install
npm install

# Create environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Install Breeze authentication
composer require laravel/breeze --dev
php artisan breeze:install blade

# Build assets
npm run build
```

### Database Configuration

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=faq_system
DB_USERNAME=root
DB_PASSWORD=
```

### Migrations

```bash
php artisan migrate
```

---

## 2. Database Structure

### `questions` Table

| Column   | Type         | Description       |
| -------- | ------------ | ----------------- |
| id       | bigint       | Primary key       |
| category | varchar(255) | Question category |
| question | varchar(255) | Question text     |
| answer   | text         | Detailed answer   |

---

## 3. Endpoints

### FAQ Endpoints

| Method | URL               | Description            | Parameters                 |
| ------ | ----------------- | ---------------------- | -------------------------- |
| GET    | /questions/create | Show FAQ creation form | -                          |
| POST   | /questions        | Create new FAQ entry   | category, question, answer |
| GET    | /questions        | List all FAQs          | ?search= (optional)        |
| DELETE | /questions/{id}   | Delete FAQ entry       | -                          |
| POST   | /questions/import | Import FAQs from JSON  | json_file                  |

---

## 4. JSON Import Format

### Required Structure

```json
{
    "dataset": [
        {
            "category": "Valid Category Name",
            "question": "Question Text",
            "answer": "Detailed Answer"
        }
    ]
}
```

### Valid Categories

```
Informations Générales sur l'Établissement
Programmes et Cours
Admission et Inscription
Vie Étudiante
Ressources Académiques
Services de Carrière
Santé et Bien-être
Technologie et Innovation
Politiques et Règlements
Événements et Actualités
Site Web et Plateformes en Ligne
Stages et Expériences Professionnelles
Professeurs et Encadrement
Clubs Étudiants et Associations
Services Administratifs et Carte Étudiante
```

---

## 5. Authentication

-   Uses Laravel Breeze authentication
-   All FAQ routes require authentication
-   User roles supported (admin/user)
-   CSRF protection enabled

---

## 6. Validation Rules

### FAQ Creation

```php
[
    'category' => 'required|string|max:255',
    'question' => 'required|string|max:255',
    'answer' => 'required|string'
]
```

---

## 7. Deployment Notes

### Environment Setup

```bash
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Production Assets

```bash
npm run build
```

### Permissions

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

## Troubleshooting

### Common Issues

1. **Migration Errors**: Run `php artisan migrate:fresh`
2. **NPM Build Errors**: Delete `node_modules` and run `npm install`
3. **File Permission Errors**: Run `composer dump-autoload`
4. **JSON Import Errors**: Validate JSON structure using [JSONLint](https://jsonlint.com/)

---

This documentation covers all aspects of the FAQ management system. Adjust database credentials and server configurations according to your environment requirements.
