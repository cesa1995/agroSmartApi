Usuarios:
=========
Ejemplos login:
-----------------
  Entradas:
    {
      "email": "email@example.com",
      "password": "passworduser"
    }
  Respuestas:
  400:Datos incompletos
  404:Usuario no registrado o datos incorrectos
  200:
  {
      "nombre": "nameuser",
      "apellido": "lastnameuser",
      "jwt": "Token"
  }


Ejemplos create:
------------------
  Entradas:
  {
      "nombre": "Nombre del Usuario",
      "apellido": "Apellido del Usuario",
      "email": "email@example.com",
      "password": "Contraseña del usuario",
      "nivel": "{0,1,2}",
      "jwt": "Token que aroja el login"
  }
  Respuestas:
  400:Sesion no iniciada
  401:No autorizado
  400:Data incompleta
  503:Usuario ya creado. Datos no validos
  503:Usuario no creado
  201:
  {
    "massage":"Usuario creado"
  }

Ejemplos delete:
-----------------
Entradas:
{
  "id":"id de usuario",
  "jwt": "Token que aroja el login"
}
Respuestas:
400:sesion no iniciada
401:no autorizado
503:usuario no eliminado
200:
{
  "massage":"Usuario eliminado"
}

Ejemplos read_one:
-----------------
Entradas:
{
  "id":"id de usuario",
  "jwt": "Token que aroja el login"
}
Respuestas:
400:sesion no iniciada
401:no autorizado o datos incompletos
404:usuario no existe
200:
{
  "id": "id de usuario",
  "nombre": "nombre de usuario",
  "apellido": "apellido del usuario",
  "email": "email@example.com",
  "nivel": "{0,1,2}"
}

Ejemplos read:
-----------------
Entradas:
{
"jwt": "Token que aroja el login"
}
Respuestas:
400:sesion no iniciada
401:no autorizado
404:no hay usuarios
200:
{
  "records":
    [{
      "id": "id de usuario",
      "nombre": "nombre de usuario",
      "apellido": "apellido de usuario",
      "email": "email@example.com",
      "nivel": "{0,1,2}"
    },
    {
      "id": "id de usuario",
      "nombre": "nombre de usuario",
      "apellido": "apellido de usuario",
      "email": "email@example.com",
      "nivel": "{0,1,2}"
    }],
}

Ejemplos search:
-----------------
Entradas:
{
  "keywords":"palabra de busqueda",
  "jwt": "Token que aroja el login"
}
Respuestas:
400:sesion no iniciada
401:no autorizado
404:no hay usuarios encontrados
200:
{
  "records":
    [{
      "id": "id de usuario",
      "nombre": "nombre de usuario",
      "apellido": "apellido de usuario",
      "email": "email@example.com",
      "nivel": "{0,1,2}"
    },
    {
      "id": "id de usuario",
      "nombre": "nombre de usuario",
      "apellido": "apellido de usuario",
      "email": "email@example.com",
      "nivel": "{0,1,2}"
    }],
}

Ejemplos update:
-----------------
Entradas:
{
  "id":"id de usuario",
  "nombre":"nombre de usuario a modificar",
  "apellido":"apellido de usuario a modificar",
  "nivel":"nivel de usuario a modificar",
  "jwt": "Token que aroja el login"
}
Respuestas:
400:sesion no iniciada
401:no autorizado
503:usuario no actualizado
200:
{
  "massage":"Usuario actualizado"
}

Ejemplos updatePass:
--------------------
Entradas:
{
  "id":"id de usuario a modificar contraseña",
  "pass1":"nueva contraseña",
  "pass2":"repetir la contraseña",
  "jwt": "Token que aroja el login"
}
Respuestas:
400:sesion no iniciasa
401:no autorizado
503:usuario no actulizado
200:
{
  "massage":Usuario actualizado
}

