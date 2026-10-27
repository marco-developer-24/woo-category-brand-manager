# Marcas por Categoría — WooCommerce

Plugin de WordPress que muestra las marcas asociadas a los productos de la categoría actual. Permite presentar logos o nombres, mostrar un contador de productos y generar enlaces para activar o quitar un filtro de marca.

El objetivo es ofrecer una navegación visual por marcas sin reemplazar el sistema de filtrado de la tienda.

## Qué hace

- Detecta la categoría de productos actual.
- Recupera las marcas asociadas a productos de esa categoría.
- Muestra el logo de cada marca cuando está disponible; si no, muestra su nombre.
- Indica cuántos productos de la categoría están asociados a cada marca.
- Genera enlaces con el parámetro `product_brand` y permite quitar el filtro de la marca activa.
- Expone la interfaz mediante el shortcode `[marcas_categoria]`.

## Requisitos y supuestos

- WordPress.
- WooCommerce.
- Una taxonomía de marcas registrada con el slug `product_brand`.
- Marcas asignadas a los productos.
- Para mostrar logos, cada término de marca debe tener el metadato de imagen `thumbnail_id`.

**Este plugin no registra ni administra la taxonomía de marcas.** Si tu tienda utiliza otro slug o una extensión que guarda las imágenes de marca con una estructura diferente, será necesario adaptar la implementación.

## Instalación

1. Descargá el repositorio como ZIP.
2. Copiá la carpeta del plugin en `wp-content/plugins/`.
3. Activá el plugin desde el administrador de WordPress.
4. Insertá `[marcas_categoria]` en el contenido o plantilla donde quieras mostrar las marcas.
5. Probá el resultado en una categoría que tenga productos y marcas asignadas.

Realizá la primera validación en un entorno de pruebas y conservá una copia de seguridad antes de instalarlo en una tienda en producción.

## Uso

Insertá el shortcode:

```text
[marcas_categoria]
```

En una categoría compatible, cada elemento puede mostrar:

- El logo de la marca o su nombre como alternativa.
- El contador de productos asociados encontrados.
- Un enlace para seleccionar la marca.
- Un control para quitar la selección cuando esa marca está activa.

## Alcance del filtrado

Los enlaces generados utilizan el parámetro `product_brand` en la URL. **La presencia del parámetro no garantiza por sí sola que el catálogo se filtre.** El tema, la consulta de productos o el sistema de filtrado instalado debe interpretar ese parámetro.

Antes de considerar terminada una integración, verificá tanto la navegación por marcas como los productos que efectivamente devuelve la tienda.

## Limitaciones conocidas

- La implementación actual recupera los IDs de todos los productos de la categoría en una consulta y recorre sus términos de marca. En categorías grandes, este enfoque puede tener un coste de rendimiento significativo.
- La solución depende de la taxonomía `product_brand` y del metadato `thumbnail_id` para las imágenes.
- El shortcode necesita poder determinar una categoría de productos para mostrar resultados.
- El repositorio no incluye pruebas automatizadas. Los cambios deben validarse manualmente en una instalación de prueba de WordPress y WooCommerce.
- La compatibilidad con otros plugins de marcas, temas y sistemas de filtros no debe darse por supuesta sin una prueba específica.

## Validación recomendada

Antes de desplegar una modificación, revisá al menos:

1. Una categoría con productos y varias marcas.
2. Una marca con logo y otra sin logo.
3. La activación y eliminación del filtro.
4. Los contadores frente a los productos realmente asociados.
5. Una categoría vacía o sin marcas asignadas.
6. La visualización en móvil y la navegación mediante teclado.
7. La consulta de productos resultante cuando se utiliza `product_brand`.

Podés usar la [lista de validación](docs/VALIDATION.md) como guía.

## Desarrollo

Priorizá cambios pequeños y verificables. Documentá las limitaciones observadas y separá las modificaciones de comportamiento de los cambios de documentación. No declares una mejora de rendimiento, seguridad o compatibilidad sin evidencia que la respalde.

## Licencia

Este repositorio todavía no especifica una licencia. No asumas que el código puede reutilizarse, modificarse o redistribuirse libremente hasta que se agregue una licencia explícita.
