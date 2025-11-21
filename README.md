# Proyecto Arduino PHP

Este proyecto fue creado con [Laravel](https://laravel.com/).

## Requisitos

- [PHP](https://www.php.net/downloads.php) = v8.4.13
- [Composer](https://getcomposer.org/) = v2.8.12
- [Node.js y npm](https://nodejs.org/) = v22.20.0
- [Arduino-cli](https://docs.arduino.cc/arduino-cli/installation/) = v1.3.1
- [Python](https://www.python.org/downloads/) = v3.13

## Instalación

1. **Clona el repositorio**

```sh
git clone https://github.com/ryoka0123/proyecto-arduino-php.git
cd proyecto-arduino-php
```

2. **Instala las dependencias de PHP**

```sh
composer install
```

3. **Instala las dependencias de python**

### Crear el entorno virtual
```sh
python -m venv .venv
```

4. **Instala las dependencias de Arduino-cli**
```sh
arduino-cli config init
arduino-cli core update-index
arduino-cli core install esp32:esp32
```

### Activar el entorno virtual

#### Windows

```sh
.venv/Scripts/activate
```

### Instalar las dependencias

```sh
pip install -r requirements.txt
```


5. **Ejecuta las migraciones**

```sh
php artisan migrate
```

6. ## Ejecución

Inicia el servidor de desarrollo de Laravel:

```sh
php artisan serve
```

```sh
uvicorn app.core.main:app --host 0.0.0.0 --port 8001 --reload
```

El proyecto estará disponible en http://localhost:8001


## Endpoint de prueba  

http://localhost:8001/api/compiler/health


## Endpoint Swagger

http://localhost:8001/api/compiler/docs
