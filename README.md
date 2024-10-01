# Projet API création de projets immobiliers.

Ce projet est un test technique pour créer un api sans utiliser le API PLATFORM

## Installation

1. Clonez le dépôt :

```bash
git clone https://github.com/imadamzil/sf-api.git
cd sf-api

```
2. Démarrez les services Docker :
    ```bash
    docker-compose up -d
    ```
3. Accédez au conteneur PHP :
   ```bash
   docker exec -it sf-api-php bash
   ```

4. Installez les dépendances et exécutez le serveur :
    ```bash
    composer install
    php -S 0.0.0.0:8000 -t public
    ```
5. Configurez les variables d’environnement.

     ```bash
      DATABASE_URL="mysql://root:password@mysql:3306/leboncode?serverVersion=8.0&charset=utf8mb4"
    ```

6. Créez la base de données et exécutez les migrations :
    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate
    ```
## Endpoints
### Testing
vous pouvez utilisé par exemple POSTMAN

### Authentification

- **Inscription** : `POST /register`
- **Login** : `POST /login`

### Projets

- **Créer un projet** : `POST /project`
- **Supprimer un projet** : `DELETE /project/{id}`
- **Mettre à jour un projet** : `PATCH /project/{id}`
- **Lister les projets** : `GET /project`
- **Obtenir un projet par ID** : `GET /project/{id}`
- **Rechercher des projets** : `GET /project/search`

## Sécurisation

Toutes les routes sauf `/register` et `/login` nécessitent un JWT valide dans l’en-tête `Authorization`.

## Technologies Utilisées

- Symfony 6.4
- MySQL 8.0
- Docker
- LexikJWTAuthenticationBundle
