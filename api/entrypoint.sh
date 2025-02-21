#!/bin/bash
set -e

echo "🚀 Vérification des dépendances ---> START"

# Vérifier si les dossiers bin/ et obj/ existent
if [ ! -d "./bin" ] || [ ! -d "./obj" ]; then
    echo "📦 Dossiers bin/ et obj/ manquants. Exécution de 'dotnet restore' ---> START"
    dotnet restore --force
    echo "✅ Les dépendances sont installées ---> OK"
else
    echo "✅ Les dépendances sont déjà en place. Aucun 'restore' nécessaire ---> OK"
fi

echo "💡 Lancement de l'application ---> START"
exec "$@"
