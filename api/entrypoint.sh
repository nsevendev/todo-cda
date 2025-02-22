#!/bin/bash
set -e

echo "🚀 Vérification des dépendances de l'api ---> START"

# Vérifier si les dossiers bin/ et obj/ existent
if [ ! -d "./bin" ] || [ ! -d "./obj" ]; then
    echo "📦 Dossiers bin/ et obj/ manquants. Exécution de 'dotnet restore' ---> START"
    dotnet restore --force
    echo "✅ Les dépendances sont installées ---> END OK"
else
    echo "✅ Les dépendances sont déjà en place. Aucun 'restore' nécessaire ---> END OK"
fi

echo "💡 Vérification des dépendances de l'api ---> END OK"
exec "$@"
