# PlaceToPay - Web Checkout!

*Sitio web para la conexión con PlaceToPay para el procesamiento de transacciones de prueba usando Web Checkout* 

## Empezando

Estas instrucciones le proporcionarán una copia del proyecto en funcionamiento en su máquina local.

### Contenido

- [Requisitos previos](#install)
- [Clonar proyecto](#Usage)
- [Instalación](#Response-Properties)
- [Error handling](#Error-handling)
- [Api codes](#Api-codes)
- [Request & Response Examples](#request-response-examples)

### Requisitos previos

- [PHP >= 8.0](https://www.php.net/manual/en/getting-started.php)
- [Laravel 9.x](https://laravel.com/docs/9.x/installation)
- [Composer](https://getcomposer.org/doc/00-intro.md)
- [Node.js](https://nodejs.org/en/docs/guides/getting-started-guide/)
- [MySQL](https://dev.mysql.com/doc/mysql-getting-started/en/)

### Clonar proyecto

Para generar una copia del proyecto ejecute en su máquina local:
`git clone https://github.com/crueda7/PlaceToPay.git`

### Configuración

Cree una base de datos MySQL con el nombre que desee,  posteriormente deberá configurar los parámetros de conexión en el archivo **.env** y los parámetros de conexión con webcheckout.

DB_CONNECTION=mysql  
DB_HOST=127.0.0.1  
DB_PORT=3306  
DB_DATABASE=placetopayshop  
DB_USERNAME=root  
DB_PASSWORD=  
...  
BASE_ENDPOINT=https://checkout-co.placetopay.dev/ 
CREATE_REQUEST=api/session  
LOGIN=  
SECRET_KEY=  
LOCALE=es_CO

> En la raíz del proyecto existe un archivo de configuración de ejemplo con la estructura requerida **example.env** si desea puede utilizarlo renombrándolo a **.env** y dentro del mismo agregar/modificar los parámetros faltantes.

Por último genere la llave identificador de su proyecto con el comando:  
`php artisan key:generate`

### Instalación

Ubíquese en la carpeta de su proyecto y ejecute las siguientes instrucciones.

Instalar de las dependencias:  
`composer install`  
`npm install`

Ejecutar las migraciones:  
`php artisan migrate`

Ejecutar los seeders:  
`php artisan db:seed`

Inicializar el proyecto:  
`php artisan serve`  
`npm run dev`

Ahora puede visualizar el proyecto en su navegador de preferencia con el puerto 8000.

## Funcionalidad

Abra en su navegador de preferencia la url http://127.0.0.1:8000 o  http://localhost:8000 para visualizar el proyecto con el tema de su dispositivo claro/oscuro en el cual podrá:

- Crear un nuevo usuario.
- Iniciar sesión.
- Cerrar sesión.
- Listar productos.
- Agregar productos a un carrito de compras.
- Visualizar y administrar el carrito de compras.
- Generar una orden de compra.
- Realizar proceso de pago de la orden de compra.
- Visualizar las ordenes de compra del usuario en sesión.
- Reintentar el proceso de pago de las ordenes de compras rechazadas o continuar con los pagos pendientes.

### Autores

- Carlos Javier Rueda Mena - [crueda7](https://github.com/crueda7)

### Referencias

- [Bootcamp Laravel](https://bootcamp.laravel.com/)
- [Vue.js Introducción](https://vuejs.org/guide/introduction.html)
- [Tailwindcss Documentación](https://tailwindcss.com/docs/installation)
