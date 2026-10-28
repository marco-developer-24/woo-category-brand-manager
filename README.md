# Marcas por Categoría — WooCommerce

> Una capa de navegación visual para explorar las marcas disponibles dentro de una categoría de productos.

Un plugin de WordPress que muestra las marcas asociadas a los productos de la categoría actual. Puede presentar logos o nombres, contadores de productos y enlaces para activar o quitar un filtro de marca.

**Enfoque:** una funcionalidad concreta, alcance explícito y validación antes de producción.

## Funcionalidades

- Detecta la categoría de productos actual.
- Recupera las marcas asociadas a productos de esa categoría.
- Muestra el logo de la marca cuando está disponible; como alternativa, presenta su nombre.
- Indica cuántos productos encontrados están asociados a cada marca.
- Genera enlaces con el parámetro `product_brand` y permite quitar la marca activa.
- Expone la interfaz mediante el shortcode `[marcas_categoria]`.

## Requisitos

- WordPress y WooCommerce.
- Una taxonomía de marcas registrada con el slug `product_brand`.
- Marcas asignadas a los productos.
- Para mostrar logos, cada término debe tener el metadato de imagen `thumbnail_id`.

> **Compatibilidad:** este plugin no registra ni administra la taxonomía de marcas. Si la tienda usa otro slug o una extensión que almacena las imágenes con otra estructura, será necesario adaptar la implementación.

## Instalación

1. Descargá el repositorio como ZIP.
2. Copiá la carpeta del plugin en `wp-content/plugins/`.
3. Activá el plugin desde el administrador de WordPress.
4. Insertá el shortcode en el contenido o plantilla donde quieras mostrar las marcas.
5. Probá el resultado en una categoría que tenga productos y marcas asignadas.

**Antes de instalarlo en producción:** hacé una copia de seguridad y validá el comportamiento en un entorno de pruebas.

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

Validá tanto la navegación por marcas como los productos que efectivamente devuelve la tienda.

## Limitaciones conocidas

- La implementación recupera los IDs de los productos de la categoría en una consulta y recorre sus términos de marca. En categorías grandes, este enfoque puede tener un coste de rendimiento significativo.
- La solución depende de la taxonomía `product_brand` y del metadato `thumbnail_id` para las imágenes.
- El shortcode necesita poder determinar una categoría de productos para mostrar resultados.
- El repositorio no incluye pruebas automatizadas; los cambios deben validarse manualmente en una instalación de prueba de WordPress y WooCommerce.
- La compatibilidad con otros plugins de marcas, temas y sistemas de filtros debe comprobarse en cada integración.

## Checklist de validación

- [ ] Categoría con productos y varias marcas.
- [ ] Marca con logo y marca sin logo.
- [ ] Activación y eliminación del filtro.
- [ ] Contadores contrastados con los productos realmente asociados.
- [ ] Categoría vacía o sin marcas asignadas.
- [ ] Visualización móvil y navegación mediante teclado.
- [ ] Consulta de productos resultante al utilizar `product_brand`.

También podés consultar la [lista de validación](docs/VALIDATION.md).

## Criterio de desarrollo

Priorizá cambios pequeños y verificables. Documentá las limitaciones observadas y separá las modificaciones de comportamiento de los cambios de documentación. No declares mejoras de rendimiento, seguridad o compatibilidad sin evidencia que las respalde.

## Licencia

Este repositorio todavía no especifica una licencia. No asumas que el código puede reutilizarse, modificarse o redistribuirse libremente hasta que se agregue una licencia explícita.
