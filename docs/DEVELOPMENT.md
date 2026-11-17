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

## Pruebas automatizadas

El repositorio incluye una suite ligera de regresión para la agregación de marcas:

```bash
php tests/test-brand-aggregation.php
```

Las pruebas usan funciones simuladas de WordPress y cubren agrupamiento por slug, conteo de relaciones producto-marca, consulta por lotes, URLs de filtro, categorías sin productos y errores de consulta o URL. No reemplazan las pruebas de integración en una instalación real de WordPress y WooCommerce.

GitHub Actions ejecuta el análisis sintáctico de PHP, la suite unitaria y una prueba de integración en WordPress con WooCommerce.

## Alcance de las garantías

La suite unitaria verifica la lógica bajo funciones simuladas. La prueba de integración ejercita las APIs reales de WordPress para las relaciones de términos y productos. Ninguna certifica compatibilidad universal, rendimiento en catálogos grandes ni comportamiento con todos los plugins de marcas; esos aspectos requieren validación específica con datos representativos.
