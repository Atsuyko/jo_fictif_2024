# jo_fictif_2024

Ce site est une application Web fictive réalisée dans le cadre d'un projet d'étude.

## Deploiement en local

### Pré-requis

  - PHP 8.1 ou plus récent
  - Un SGBD (MySQL)
  - Serveur XAMPP ou similaire (A noter que XAMPP vous fourni le serveur, le SGBD et PHP 8.1)
  - Composer
  - Symfony CLI

### Installation locale

Afin d'utiliser le site en local vous devez suivre les étapes suivantes :
  1. Cloner le repository présent sur GitHub : https://github.com/Atsuyko/jo_fictif_2024
    - git clone https://github.com/Atsuyko/jo_fictif_2024,
  2. Ouvrir le dossier dans un IDE, ouvrir le terminal de commande, se placer dans le dossier du projet "cd jo_fictif_2024" et taper "composer install",
  3. Modifier les paramètres de votre base de donnée le dossier ".env" DATABASE_URL,
  4. Dans le terminal tapez :
    - php bin/console doctrine:database:create,
    - php bin/console doctrine:migration:migrate,
    - php bin/console doctrine:fixtures:load,
  5. Toujours le terminal tapez "symfony server:start".

Votre application est déployé en local, à ouvrir en cliquant sur le lien présent dans le terminal (lien "localhost" ou "127.0.0.1").

### Ajout des API

#### Google oAuth

1. Rendez-vous sur https://console.cloud.google.com/
2. Cliquer sur "API et service"
3. Cliquez sur "Créer un projet" => "Créer"
4. Cliquez sur Ecran de consentement" (Sidebar) => "Configurer l'écran de consentement" => "Externe" => "Créer"
5. Renseigner uniquement les champs obligatoire.
6. Cliquez sur "Identifiant" (Sidebar) => "Créer des identifiants" => "ID client oAuth"
7. "Application web" => Origines JS autorisées => "ajouter un URI" (x2) => "http://127.0.0.1:8000" et "http://localhost:8000"
8. URI de redirection autorisés => "ajouter un URI" (x2) => "http://127.0.0.1:8000/check/google" et "http://localhost:8000/check/google"
9. "Créer"
10. Ajouter au fichier ".env" OAUTH_GOOGLE_ID= clé ID client et OAUTH_GOOGLE_SECRET= Code secret du client

#### Stripe

Créer un compte Stripe et récupérer la clé secrète et l'ajouter au fichier ".env" STRIPE_SECRET="".

### Tips

Dans votre fichier de configuration de PHP php.ini :

1. Décommenter extension=intl pour afficher le formatage des dates de Easyadmin
2. Décommenter extension=gd pour afficher le QR Code des billets

## Compte Admin

Avec les fixtures, un compte admin est disponible avec les identifiants suivants : admin@jo.com / password.
