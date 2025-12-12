# ---

**TPE TERCERA ENTREGA**

Integrantes:  
\[Martiniano Echeverria\] \- \[[echeverriamartiniano@gmail.com](mailto:echeverriamartiniano@gmail.com)\]

📁 Para que funcione bien la api se necesita la base de datos que se encuentra en el archivo db\_turismo.sql. Puede importarse manualmente desde phpMyAdmin.

# **Configuración**

HTTP

  Base URL: http://localhost/turismo-api/api

# **Documentación de la API REST —Turismo**

### **📄Endpoints publicos**

#### **Obtener todos los destinos**

HTTP

  GET ../api/destinos

| Parametro | tipo | Descripción |
| :---- | :---- | :---- |
| ninguno | \- | Retorna un arreglo de objetos destino |

#### **Obtener destinos filtrados por región**

HTTP

  GET ../api/destinos?region=id-region

| QueryParam | tipo | Descripción |
| :---- | :---- | :---- |
| region | int | Required. Id de la región a filtrar |

#### **Obtener destinos ordenados por algún campo**

HTTP

Ejemplos de uso:  
GET ../api/destinos?orderby=nombre\&order=asc  
GET ../api/destinos?orderby=id\_destino\&order=desc

| QueryParam | tipo | Descripción |
| :---- | :---- | :---- |
| orderby | string | Required. campo de destino al cual ordenar |
| order | string | Required. Manera de ordenar el listado (asc/desc) |

#### **Obtener destinos con paginado**

HTTP

  GET ../api/destinos?page=1\&limit=5 // Valores de page y limit son un ejemplo

| QueryParam | tipo | Descripción |
| :---- | :---- | :---- |
| page | int | Required. Página que se quiere ver |
| limit | int | Required. Máximo de destinos por página |

#### ---

**Obtener un destino por su ID**

HTTP

  GET ../api/destinos/${id}

| Parametro | tipo | Descripción |
| :---- | :---- | :---- |
| id | int | *Required*. ID del destino a obtener |

#### ---

### **🔐Endpoints de Administración (ABM)**

HTTP

Permiten modificar el estado de la base de datos (Crear, Editar, Borrar).

#### **Crear Nuevo Destino**

HTTP

  POST ../api/destinos

| Parametro | tipo | Descripción |
| :---- | :---- | :---- |
| nombre | varchar(255) | Requerido. Nombre del destino |
| descripcion | text | Requerido. Descripción del lugar |
| id\_region\_fk | int | Requerido. ID de la región a la que pertenece |
| imagen\_url | varchar(255) | Opcionall. URL de la imagen (puede ser null) |

HTTP

Ejemplo de body JSON  
   
{  
  "nombre": "Cataratas del Iguazú",  
  "descripcion": "Una de las 7 maravillas naturales...",  
  "id\_region\_fk": 2,  
  "imagen\_url": "http://ejemplo.com/iguazu.jpg"  
}

#### **Editar Destino**

HTTP

PUT ../api/destinos/${id}

| Parametro | tipo | Descripción |
| :---- | :---- | :---- |
| nombre | varchar(255) | Required. Nombre del destino |
| descripcion | text | Required. Descripción del lugar |
| id\_region\_fk | int | Required. ID de la región a la que pertenece |
| imagen\_url | varchar(255) | Optional. URL de la imagen |

HTTP

Ejemplo de body JSON

{  
  "nombre": "Bariloche (Editado)",  
  "descripcion": "Centro turístico invernal actualizado...",  
  "id\_region\_fk": 3,  
  "imagen\_url": "http://ejemplo.com/bariloche\_new.jpg"  
}

#### **Eliminar Destino**

HTTP

  DELETE ../api/destinos/${id}

| Parametro | tipo | Descripción |
| :---- | :---- | :---- |
| id | int | *Required*. ID del destino a eliminar |

