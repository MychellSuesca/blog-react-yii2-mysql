# Blog React-Yii2-Mysql

Aplicación para un blog:

- Este proyecto se realiza con React.js como Front-End
- PHP con framework Yii2 como Back-End
- MySQL como gestor de base de datos
- Para el sistema de autenticación se usa JWT (Token)
- Se utiliza el framework Bootstrap

## Configuración

#### Clonar el repositorio:

- Se debe de clonar sobre la carpeta su xampp llamada htdocs que es donde se ejecutan los proyectos que requieren apache

```bash
git clone https://github.com/MychellSuesca/blog-react-yii2-mysql.git
```

#### Actualizar las credenciales de base de datos:

- Ingresar dentro del repositorio a la ruta C:\xampp\htdocs\blog-react-yii2-mysql\blog-back\config\config.php y cambiar las credenciales dependiendo de su configuración con Mysql

```bash
$DATABASE='blog';
$HOSTNAME='127.0.0.1';
$PORT=3306;
$USERNAME='root';
$PASSWORD='';
```

#### Crear la Base de Datos en su MySQL:

- Ejecutar en su motor de base de datos de MySQL:

```bash
CREATE DATABASE blog;
```

#### Correr Migraciones:

- Ingresar a la ruta C:\xampp\htdocs\blog-react-yii2-mysql\blog-back y ejecutar

```bash
php yii migrate
```

#### Instalar dependencias de React.js:

- Ingresar a la ruta C:\xampp\htdocs\blog-react-yii2-mysql\blog-react y ejecutar

```bash
npm install
```

_Sino tienes npm instalado, [Las instrucciones están aquí](https://www.npmjs.com/get-npm)._

#### Correr la aplicación:

```bash
npm start
```

#### Ingreso con Usuario de Aministrador

- Al registrarme todos los usuarios siempre tienen por defecto el tipo de usuario: Usuario, para ingresar y modificar los usuarios creados ingresar con el siguiente usuario por defecto

Email: `root@root.com`
Password: `123456`

