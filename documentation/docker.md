# Documentation du fichier docker-compose.yml

## Présentation
Ce fichier **docker-compose.yml** définit un environnement de développement utilisant plusieurs conteneurs pour une application Symfony. Il comprend :
- Un serveur PHP avec **Mercure** intégré.
- Une base de données **PostgreSQL**.
- Une file de messages **RabbitMQ**.
- Un **worker** Symfony pour exécuter des tâches en arrière-plan.
- La gestion des réseaux et des volumes persistants.

---

## Services

### 1️⃣ **todo-cda-php** (Serveur PHP avec Mercure)

- Conteneur exécutant **PHP avec Mercure**.
- Utilise l'image Docker définie par `IMAGES_PREFIX`.
- Redémarre automatiquement sauf en cas d'arrêt manuel (`restart: unless-stopped`).
- Contient des variables d'environnement pour **Mercure**, **Symfony** et la configuration serveur.
- Monte deux volumes : `caddy_data` et `caddy_config`.
- Connecté aux réseaux **traefik-nseven** et **todo-cda-network**.

### 2️⃣ **todo-cda-database** (Base de données PostgreSQL)

- Utilise **PostgreSQL 16-alpine**.
- Définit un utilisateur (`POSTGRES_USER`), un mot de passe (`POSTGRES_PASSWORD`) et une base de données (`POSTGRES_DB`).
- Vérifie la disponibilité de la base de données avec un **healthcheck** (`pg_isready`).
- Stocke les données dans un volume `database_data` pour la persistance.
- Connecté aux réseaux **traefik-nseven** et **todo-cda-network**.

### 3️⃣ **todo-cda-rabbitmq** (File de messages RabbitMQ)

- Utilise **RabbitMQ 3 avec interface de gestion**.
- Définit un utilisateur et un mot de passe (`RABBITMQ_DEFAULT_USER`, `RABBITMQ_DEFAULT_PASS`).
- Vérifie la disponibilité avec un **healthcheck** (`rabbitmq-diagnostics -q ping`).
- Stocke les données dans un volume `rabbitmq_data`.
- Connecté aux réseaux **traefik-nseven** et **todo-cda-network**.

### 4️⃣ **todo-cda-worker** (Worker Symfony)

- Conteneur dédié à l'exécution des tâches asynchrones avec **Messenger**.
- Construit l’image à partir du Dockerfile (`target: frankenphp_dev`).
- Lance `php bin/console messenger:consume -vvv` pour exécuter les tâches en continu.
- Dépend des services **PHP**, **PostgreSQL** et **RabbitMQ** (`depends_on`).
- Connecté aux réseaux **traefik-nseven** et **todo-cda-network**.

---

## 📌 **Volumes** (Données persistantes)

| Volume         | Description                           |
|---------------|-----------------------------------|
| `caddy_data`  | Stocke les données de Caddy      |
| `caddy_config`| Stocke la configuration de Caddy |
| `rabbitmq_data` | Stocke les messages RabbitMQ  |
| `database_data` | Stocke les données PostgreSQL  |

---

## 🌍 **Réseaux**

| Réseau           | Type       | Description |
|------------------|-----------|-------------|
| `traefik-nseven` | Externe   | Connecte les services à **Traefik** |
| `todo-cda-network` | Bridge  | Réseau privé pour les services |

---

## 🎯 **Résumé**

- **todo-cda-php** : Serveur PHP avec Mercure.
- **todo-cda-database** : Base de données PostgreSQL.
- **todo-cda-rabbitmq** : File de messages RabbitMQ.
- **todo-cda-worker** : Worker Symfony pour Messenger.
- **Volumes persistants** : PostgreSQL, RabbitMQ, Caddy.
- **Réseaux** : Un réseau **externe (traefik)** et un **interne (todo-cda-network)**.

Ce fichier **docker-compose.yml** assure un environnement complet et modulaire pour une application Symfony avec **messagerie asynchrone** et **événements en temps réel** via **Mercure**. 🚀

