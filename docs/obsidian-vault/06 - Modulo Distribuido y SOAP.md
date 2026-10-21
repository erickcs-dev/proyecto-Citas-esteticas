---
tags:
  - distribuido
  - soap
  - xml
  - rpc
  - interoperabilidad
padre: "[[00 - MOC - Indice Maestro]]"
---

# 🌐 06 - Módulo Distribuido y SOAP

El directorio `distributed/` contiene la implementación de servicios distribuidos y de interoperabilidad de bajo nivel requeridos para la asignatura **Cliente-Servidor**.

---

## 🏗️ Arquitectura del Módulo Distribuido

```mermaid
graph LR
    subgraph Cliente_Distribuido ["Cliente RPC / SOAP"]
        ClientPHP["client.php (Consumidor)"]
    end

    subgraph Registro_Servicios ["Directorio de Servicios"]
        Registry["registry.php (Lookup / Directorio)"]
    end

    subgraph Servidor_Distribuido ["Servidor SOAP"]
        ServerPHP["server.php / soap-service.php"]
        WSDL["Esquemas WSDL / XSD"]
        XSLT["Transformaciones XSLT"]
    end

    ClientPHP -->|1. Consulta servicio| Registry
    Registry -->>|2. URL y Contrato| ClientPHP
    ClientPHP -->|3. Petición SOAP XML| ServerPHP
    ServerPHP -->|4. Valida XSD| WSDL
    ServerPHP -->|5. Transforma| XSLT
    ServerPHP -->>|6. Respuesta XML Envelop| ClientPHP
```

---

## 📂 Componentes y Archivos Clave

| Archivo / Carpeta | Propósito |
| :--- | :--- |
| `distributed/bootstrap.php` | Inicialización del entorno, carga de autoloader y configuración base |
| `distributed/config.php` | Puertos, URLs de hosts y parámetros de red distribuidos |
| `distributed/registry.php` | Directorio de localización de servicios (Service Registry / Broker) |
| `distributed/server.php` | Servidor socket / HTTP para escuchar peticiones de red |
| `distributed/soap-service.php` | Endpoint de despacho del servidor SOAP (SoapServer nativo de PHP) |
| `distributed/client.php` | Cliente de prueba que instancia SoapClient y consume métodos remotos |
| `distributed/schemas/` | Definiciones XSD (XML Schema Definition) que validan los mensajes |
| `distributed/xslt/` | Plantillas XSLT para transformar estructuras XML a HTML u otros formatos |
| `distributed/src/` | Clases de negocio distribuido y serializadores |

---

## 🔄 Interacción con el Sistema Principal Laravel
- **Interoperabilidad**: Mientras que Laravel expone una API REST moderna en `/api/*` con JSON (Guía 7), el módulo en `distributed/` ilustra el modelo de interoperabilidad corporativo clásico con **SOAP / XML**.
- Ambos módulos coexisten en el repositorio y permiten demostrar dominio de múltiples paradigmas de comunicación entre cliente y servidor.

---

## 🔗 Enlaces Relacionados
- MOC Principal: [[00 - MOC - Indice Maestro]]
- Guías Académicas: [[07 - Guias y Actividades Academicas]]
- API REST Laravel: [[02 - Mapa de Rutas y Endpoints]]
