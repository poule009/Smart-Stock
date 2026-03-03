# Smart-Stock

Smart-Stock est une application de gestion de stock moderne, conçue pour les restaurants, commerces ou tout établissement nécessitant un suivi précis des produits, lots et mouvements de stock. Elle combine un backend Laravel (API, logique métier, base de données) et un frontend Angular (interface utilisateur).

---

##  Résumé du projet
Smart-Stock permet de :
- Gérer les utilisateurs et leurs rôles (admin, cuisinier, serveur)
- Suivre les catégories, produits, lots et mouvements de stock
- Visualiser l'état du stock en temps réel
- Recevoir des alertes sur les seuils critiques
- Historiser toutes les entrées et sorties de stock

---

##  Structure du projet
- `backend/` : Application Laravel (API, gestion de la base de données, logique métier)
- `frontend/` : Application Angular (interface utilisateur)

---

##  Schéma d'architecture

```
mermaid
graph TD
    A[Visiteur] -->|Accès| B(Frontend Angular)
    B -->|Appels API| C(Backend Laravel)
    C -->|Gestion| D(Base de données)
    C -->|Authentification| E(Utilisateurs)
    C -->|Gestion Stock| F(Produits, Lots, Mouvements)
    B -->|Affichage| G[Interface utilisateur]
    G -->|Actions| F
    E -->|Connexion| B
```

---

##  Fonctionnalités principales
- Gestion des utilisateurs et rôles
- Gestion des catégories et produits
- Gestion des lots (quantité, date d'expiration, prix)
- Suivi des mouvements de stock (entrée, sortie, raison)
- Alertes sur seuils critiques
- Historique complet des opérations

---

##  Prérequis techniques
- PHP >= 8.1
- Composer
- Node.js >= 18
- Angular CLI
- Base de données MySQL ou compatible

---

##  Guide rapide de démarrage

### Backend
1. Installer les dépendances PHP :
   
```
sh
   cd backend
   composer install
   
```
2. Configurer le fichier `.env` (base de données, mail, etc.)
3. Générer la clé d'application :
   
```
sh
   php artisan key:generate
   
```
4. Lancer les migrations :
   
```
sh
   php artisan migrate
   
```
5. (Optionnel) Lancer les seeders :
   
```
sh
   php artisan db:seed
   
```
6. Démarrer le serveur :
   
```
sh
   php artisan serve
   
```

### Frontend
1. Installer les dépendances Node.js :
   
```
sh
   cd frontend
   npm install
   
```
2. Démarrer le serveur Angular :
   
```
sh
   ng serve
   
```
3. Accéder à l'application : [http://localhost:4200](http://localhost:4200)

---

## 🔍 Exemples d'utilisation

### Exemple d'appel API (produits)
```
http
GET /api/produits
```
Réponse :
```
json
[
  {
    "id": 1,
    "nom": "Tomate",
    "categorie_id": 2,
    "unite": "kg",
    "seuil_alerte": 5
  },
  
]
```

### Exemple d'interface utilisateur
*Liste des produits, alertes de stock, historique des mouvements, gestion des lots, etc.*

---

## 📚 Documentation & Ressources
- [Documentation Laravel](https://laravel.com/docs)
- [Documentation Angular](https://angular.io/docs)

---

## 🏆 Points forts
- Architecture claire et évolutive (séparation backend/frontend)
- Respect des conventions Laravel et Angular
- Relations et intégrité référentielle bien gérées dans la base de données
- Présence de tests unitaires et fonctionnels

---

## 🛠️ Axes d'amélioration
- Ajouter une documentation technique plus détaillée (exemples d'utilisation, schémas, API endpoints)
- Compléter les tests (backend et frontend)
- Vérifier la sécurité (validation, protection des routes, gestion des rôles)
- Ajouter des captures d'écran ou démos dans le README

---

## 👤 Auteur
- [EL hadji malick Aidara Badiane]
