<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

## LARAVEL BLOG PROJECT

<h2>Server Requirements</h2>
 - PHP >= 7.2.0
 - BCMath PHP Extension
 - Ctype PHP Extension
 - JSON PHP Extension
 - Mbstring PHP Extension
 - OpenSSL PHP Extension
 - PDO PHP Extension
 - Tokenizer PHP Extension
 - XML PHP Extension

<h4>First Step</h4>

 - git clone https://github.com/jadKhoury1/Laravel-Blog.git blog
 - Make sure to clone the project in the www directory of your Apache server
 
 <h4>Second Step</h4>
 
 run <strong>composer install</strong> command
 
 <h4>Third Step</h4>
 
  - Create .env file and copy all the data from .env.example file and paste it in .env file
  - Add the database name and credentials to the env file
  
  <h4>Fourth Step</h4>
  
  - Run <strong>php artisan migrate</strong> command
  - Run <strong>php artisan key:generate</strong> to generate your application key
  - Run <strong>php artisan passport:keys</strong> to create the encryption keys Passport needs in order to generate access token
  - Run <strong>php artisan passport:client --personal</strong>. <br />
  You can give it a name of myApp. This command will create passport personal access client
  
  <h4>Fifth step</h4>
  
  create <strong>uploads</srtong> folder in the public directory
  
  <h4>Postman Collection</h4>
    - https://www.getpostman.com/collections/12ff393642ab0ae58e39
    
  <h4>Admin User</h4>
   - An admin user will be created by default
   - email: admin@blog.com
   - password: ;Y<(p/EX9fa@Tj(p
## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).
