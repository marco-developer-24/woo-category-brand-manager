# Lista de validación manual

Usá esta lista al revisar cambios en **Marcas por Categoría — WooCommerce**. Ejecutá las pruebas en un entorno de desarrollo o staging con WordPress y WooCommerce instalados.

Esta lista es una guía manual; no representa una suite automatizada ni certifica compatibilidad con todos los temas o extensiones.

## Preparación

- [ ] Registrar las versiones de WordPress, WooCommerce, PHP y del tema.
- [ ] Confirmar que la taxonomía `product_brand` está registrada.
- [ ] Preparar una categoría con productos de al menos dos marcas.
- [ ] Preparar una marca con imagen `thumbnail_id` y otra sin imagen.
- [ ] Confirmar que existe una copia de seguridad o que el entorno es descartable.

## Renderizado del shortcode

- [ ] `[marcas_categoria]` muestra marcas en una categoría de productos válida.
- [ ] Cada marca aparece una sola vez.
- [ ] Se muestra el logo cuando hay una imagen válida.
- [ ] Se muestra el nombre cuando no hay logo.
- [ ] El contador coincide con la cantidad de productos asociados que contempla la implementación.
- [ ] No se muestra contenido roto cuando la categoría no tiene productos o marcas.

## Navegación y filtros

- [ ] Cada enlace apunta a la categoría esperada e incluye el parámetro `product_brand`.
- [ ] La marca seleccionada se identifica visualmente como activa.
- [ ] Activar una marca distinta genera el enlace esperado.
- [ ] El control para quitar el filtro aparece solo para la marca activa.
- [ ] Quitar el filtro devuelve a la URL de la categoría.
- [ ] Se verifica por separado que la consulta de productos realmente aplique el filtro; la URL por sí sola no lo demuestra.

## Interfaz y accesibilidad básica

- [ ] Los elementos se acomodan correctamente en escritorio y móvil.
- [ ] Los enlaces pueden recorrerse con teclado.
- [ ] El foco del teclado es visible.
- [ ] Las imágenes tienen texto alternativo adecuado.
- [ ] Los enlaces y controles mantienen un propósito comprensible sin depender únicamente del color.

## Regresión y cierre

- [ ] No aparecen avisos o errores nuevos en el log de PHP durante las pruebas.
- [ ] No se observan errores de consola relacionados con el cambio.
- [ ] Se repiten las pruebas relevantes tras modificar la consulta, las URLs o el HTML.
- [ ] Se documentan los casos no probados y las limitaciones conocidas.
- [ ] El commit describe el cambio real y no afirma resultados que no se verificaron.

## Registro de ejecución

Al cerrar una validación, anotá:

- **Fecha:**
- **Commit probado:**
- **Entorno y versiones:**
- **Resultado:** aprobado / aprobado con observaciones / fallido
- **Casos fallidos o no probados:**
- **Observaciones y siguiente acción:**
