# teacher_availability_platform

# Project objective
 To develop an intuitive, secure platform for managing teachers' availability and automatically generating optimized schedules. 

 ## Prerequisites


- [Git]
- [PHP (^8.2)]
- [Composer]
- [Mysql / Mariadb]
- [Node](https://nodejs.org/)

## Clone api the backend 


```sh
git https://github.com/josueagboton/plateforme_disponibilite_professeurs.git
cd plateforme_disponibilite_professeurs
cd backend

# dupe the .env.example file and rename it .env

# configure the connection to the database, e.g. mysql

# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=dispo_prof
# DB_USERNAME=root
# DB_PASSWORD=

composer install
php artisan migrate
php artisan serve



