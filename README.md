# Proyecto Arduino PHP

Este proyecto fue creado con [Laravel](https://laravel.com/).

## Requisitos

- PHP >= 8.0
- [Composer](https://getcomposer.org/)
- [Node.js y npm](https://nodejs.org/)

## Instalación

1. **Clona el repositorio**

```sh
$ git clone https://github.com/ryoka0123/proyecto-arduino-php.git
$ cd proyecto-arduino-php
```

2. **Instala las dependencias de PHP**

```sh
$ composer install
```

3. **Instala las dependencias de python**

### Crear el entorno virtual
```sh
$ python -m venv .venv
```

### Activar el entorno virtual

#### Windows

```sh
$ .venv/Scripts/activate
```

#### Linux
```sh
$ source .venv/bin/activate
```

### Instalar las dependencias

```sh
$ pip install -r requirements.txt
```


4. **Ejecuta las migraciones**

```sh
$ php artisan migrate
```

5. ## Ejecución

Inicia el servidor de desarrollo de Laravel:

```sh
$ php artisan serve
```

```sh
$ uvicorn app.core.main:app --host 0.0.0.0 --port 8001 --watch
```

El proyecto estará disponible en http://localhost:8001.


6. ## Levantar servicio en docker

### Crear contenedor

Nos paramos en el directorio raíz donde esta nuestro dockerfile y ejecutamos el siguiente comando:
```sh
    $ docker build -t proyectopython .
```

### Levantar contenedor
```sh
    $ docker run --name contepython -dp 8001:8001 proyectopython
```


## Endpoint de prueba  

http://localhost:8001/api/compiler/health


## Endpoint Swagger

http://localhost:8001/api/compiler/docs
