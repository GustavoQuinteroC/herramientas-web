# Herramientas web

Utilidades pequeñas que hice para resolver problemas del día a día en desarrollo, soporte e infraestructura. Todas funcionan en el navegador, excepto el validador de CFDI, que corre en PHP.

| Herramienta | Qué hace | Stack |
|---|---|---|
| [`validador-xml-vs-xsd`](validador-xml-vs-xsd) | Valida un XML de **CFDI 4.0** contra el esquema XSD del SAT y explica cada error con la documentación del propio esquema | PHP 8, libxml, DOM |
| [`generador-qr-wifi`](generador-qr-wifi) | Genera un código QR para conectarse a una red WiFi (SSID, contraseña y tipo de seguridad) | HTML, CSS, JavaScript |
| [`generador-qr-multiple`](generador-qr-multiple) | Genera QR de varios tipos: URL, texto, WiFi y más | HTML, CSS, JavaScript |
| [`generador-de-contraseñas-servicios`](generador-de-contraseñas-servicios) | Contraseñas corporativas reproducibles por perfil: equipos, usuarios y servicios | HTML, JavaScript |
| [`generador-de-contraseñas-año-chino`](generador-de-contraseñas-año-chino) | Variante que deriva las contraseñas del año chino y de factores corporativos | HTML, JavaScript |
| [`generador-de-gradientes`](generador-de-gradientes) | Crea gradientes CSS y copia el código listo para usar | HTML, CSS, JavaScript |

Puedes probarlas en línea en [proyectos.gustavoquintero.dev](https://proyectos.gustavoquintero.dev).

## Uso local

Las herramientas de navegador se abren directamente desde su `index.html`. El validador necesita PHP con la extensión `dom`:

```bash
php -S localhost:8000 -t validador-xml-vs-xsd
```

## Autor

**Gustavo Quintero**, desarrollador full stack en Guadalajara: [gustavoquintero.dev](https://gustavoquintero.dev)

Licencia MIT.
