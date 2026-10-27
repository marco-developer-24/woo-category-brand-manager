# Criterios de desarrollo

Este documento define criterios prácticos para mantener **Marcas por Categoría — WooCommerce**. Son pautas para revisar cambios; no sustituyen pruebas ni garantizan compatibilidad.

## Principios

1. **Cambios acotados:** cada commit debe resolver un objetivo reconocible.
2. **Comportamiento explícito:** documentar los supuestos sobre taxonomías, metadatos y parámetros de URL.
3. **Validación proporcional al riesgo:** los cambios en consultas, filtros y salida HTML requieren pruebas de regresión específicas.
4. **Errores tratados:** comprobar valores de retorno de WordPress y evitar asumir que una función siempre devuelve el tipo esperado.
5. **Salida segura:** escapar los valores al imprimir HTML y validar/sanitizar datos de entrada según el contexto.
6. **Rendimiento medible:** identificar consultas costosas antes de introducir cachés o cambios de arquitectura; comprobar el resultado en datos representativos.
7. **Afirmaciones verificables:** no declarar que una versión está probada, es segura o es compatible sin registrar la evidencia correspondiente.

## Antes de modificar el código

- Identificar el comportamiento actual y el resultado esperado.
- Revisar los efectos sobre tiendas con categorías vacías, marcas sin imagen y filtros activos.
- Determinar si el cambio afecta a consultas, URLs, HTML, estilos o compatibilidad.
- Mantener el cambio enfocado; evitar refactors no relacionados.

## Antes de cerrar un cambio

- Ejecutar las pruebas pertinentes de `docs/VALIDATION.md`.
- Revisar errores de PHP y consola cuando corresponda.
- Documentar limitaciones o escenarios no verificados.
- Usar un mensaje de commit que describa el cambio real.
- No mezclar documentación y cambios funcionales si eso dificulta revisar o revertir el trabajo.

## Alcance de las garantías

El repositorio no incorpora actualmente una suite de pruebas automatizadas. Hasta que exista una, el estado de validación debe describirse como manual y limitado al entorno efectivamente probado. No se deben inferir compatibilidad universal, seguridad completa o mejoras de rendimiento a partir de una revisión superficial.
