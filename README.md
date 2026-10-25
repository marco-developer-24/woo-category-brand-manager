# Marcas por Categoría (Logos) — WooCommerce

Plugin de WordPress que muestra las marcas asociadas a productos de la categoría actual, con logos o nombres, contadores y enlaces para activar o quitar un filtro de marca.

## Estado

- **Versión:** 4.2
- **Shortcode:** `[marcas_categoria]`
- **Archivo principal:** `marcas-por-categoria.php`
- **Interfaz:** español

## Requisitos

- WordPress y WooCommerce.
- Una taxonomía de marcas registrada con el slug `product_brand`.
- Marcas asignadas a los productos.
- Para mostrar logos, las marcas deben tener el metadato de imagen `thumbnail_id`.

El plugin no crea ni administra la taxonomía de marcas.

## Instalación

1. Descargá el repositorio como ZIP.
2. Colocá la carpeta del plugin en `wp-content/plugins/`.
3. Activá el plugin desde el administrador de WordPress.
4. Insertá el shortcode `[marcas_categoria]` en el contenido o plantilla correspondiente.
5. Probalo en una categoría de productos que tenga marcas asignadas.

## Uso

Agregá el shortcode:

`[marcas_categoria]`

Para cada marca encontrada, el plugin puede mostrar:

- El logo de la marca o su nombre si no tiene imagen.
- Un contador de productos asociados encontrados en la categoría.
- Un enlace que agrega el parámetro `product_brand` a la URL.
- Un botón para quitar el filtro cuando la marca está activa.

**Importante:** agregar `product_brand` a la URL no garantiza que los productos se filtren automáticamente. El tema o el sistema de filtrado de la tienda debe reconocer ese parámetro.

## Limitaciones conocidas

- La versión actual recupera todos los productos de la categoría en una sola consulta. En catálogos grandes, esto puede afectar el rendimiento.
- Depende de la taxonomía `product_brand` y del metadato `thumbnail_id` para las imágenes.
- No incluye pruebas automatizadas. Se recomienda validar el comportamiento en un entorno de pruebas antes de usarlo en producción.

## Desarrollo

El código inicial se conserva como punto de partida. Las mejoras futuras deberían registrarse en commits separados para facilitar su revisión y reversión.

## Licencia

Este repositorio todavía no especifica una licencia. No asumas que el código puede reutilizarse, modificarse o redistribuirse libremente hasta que se agregue una.
