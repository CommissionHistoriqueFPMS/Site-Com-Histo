# Utiliser l'image officielle PHP Apache
FROM php:7.4-apache

# Activer le module Apache mod_rewrite
RUN a2enmod rewrite

# Copier le contenu du répertoire local dans /var/www/html/
COPY . /var/www/html/

# Définir le répertoire de travail
WORKDIR /var/www/html

# Exposer le port 80
EXPOSE 80

# Démarrer Apache
CMD ["apache2-foreground"]
