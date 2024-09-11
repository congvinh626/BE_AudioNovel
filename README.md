php artisan make:migration create_type_novels_table --table=type_novels

php artisan migrate:fresh --seed
php artisan passport:install
php artisan queue:table
php artisan queue:listen
