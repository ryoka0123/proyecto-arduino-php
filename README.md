# Proyecto Arduino PHP

Este proyecto fue creado con [Laravel](https://laravel.com/).

## Requisitos

- [PHP](https://www.php.net/downloads.php) = v8.4.13
- [Composer](https://getcomposer.org/) = v2.8.12
- [Node.js y npm](https://nodejs.org/) = v22.20.0
- [Arduino-cli](https://docs.arduino.cc/arduino-cli/installation/) = v1.3.1
- [Python](https://www.python.org/downloads/) = v3.13

## Instalación

1.  **Clona el repositorio**

```sh
git clone https://github.com/ryoka0123/proyecto-arduino-php.git
cd proyecto-arduino-php
```

2.  **Instala las dependencias de PHP**

```sh
composer install
```

3.  **Instala las dependencias de Python**

### Crear el entorno virtual

```sh
python -m venv .venv
```

### Activar el entorno virtual (Windows)

```sh
.venv/Scripts/activate
```

### Instalar las dependencias

```sh
pip install -r requirements.txt
```

4.  **Instala las dependencias de Arduino-cli**

```sh
arduino-cli config init
arduino-cli core update-index
arduino-cli core install esp32:esp32
```

5.  **Ejecuta las migraciones**

```sh
php artisan migrate
```

## Configuración del archivo `.env`

Antes de ejecutar el proyecto es necesario configurar el archivo de
entorno de Laravel. Este archivo contiene las variables necesarias para
que el sistema funcione correctamente, incluyendo la URL del servicio de
compilación y la ruta del Arduino CLI.

1.  **Duplicar el archivo `.env.example` y renombrarlo a `.env`:**

```sh
cp .env.example .env
```

2.  **Generar la clave de aplicación de Laravel:**

``` sh
php artisan key:generate
```

3.  **Configurar las variables del archivo `.env`:**

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=sqlite

COMPILATION_SERVICE_URL="http://IP_DEL_SERVIDOR"
COMPILATION_SERVICE_PORT=8001

ARDUINO_CLI=ruta/absoluta/a/tu/arduino-cli.exe
# Ejemplo en Windows:
# ARDUINO_CLI=C:\Users\tu_usuario\Documents\arduino-cli.exe
```

### Variables importantes a configurar:

  ------------------------------------------------------------------------
  Variable                       Descripción
  ------------------------------ -----------------------------------------
  **COMPILATION_SERVICE_URL**    Dirección IP o dominio donde se ejecuta
                                 el servicio de compilación (FastAPI).

  **COMPILATION_SERVICE_PORT**   Puerto del servicio FastAPI (por defecto:
                                 8001).

  **ARDUINO_CLI**                Ruta absoluta del ejecutable
                                 `arduino-cli`. En Windows debe incluir el
                                 `.exe`.

  **DB_CONNECTION**              Por defecto es `sqlite`. Si usas otra
                                 base de datos, configura las credenciales
                                 correspondientes.
  ------------------------------------------------------------------------

## Ejecución

Inicia el servidor de desarrollo de Laravel:

``` sh
php artisan serve
```

Inicia el servidor FastAPI:

``` sh
uvicorn app.core.main:app --host 0.0.0.0 --port 8001 --reload
```

El proyecto estará disponible en http://localhost:8001

## Endpoint de prueba

http://localhost:8001/api/compiler/health

## Endpoint Swagger

http://localhost:8001/api/compiler/docs
