# 🚀 Laravel Enterprise Boilerplate

Este es un **Boilerplate profesional** desarrollado con Laravel, diseñado para servir como base sólida en proyectos SaaS o sistemas de gestión empresarial. Incluye una arquitectura robusta centrada en la seguridad, trazabilidad y escalabilidad.

## 🛠️ Tecnologías

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)

---

## 💎 Características Principales

- **Seguridad y Autorización:**
    - **Form Requests:** Validación de datos centralizada y tipada antes de entrar al controlador.
    - **Policies & Middleware:** Control de acceso granular en todas las rutas API.
    - **Nomenclatura de Permisos:** Sistema basado en slugs (Ej: `modulo.accion`).
- **Capa de Servicios (Service Pattern):**
    - **`FileUploadService`:** Gestión centralizada para la subida de archivos únicos o múltiples, garantizando nombres únicos y almacenamiento organizado.
- **Arquitectura de Datos & Helpers:**
    - **Trait `HasCode`:** Generación automática de códigos amigables (Ej: `USR-00001`) mediante eventos de modelo (`static::creating`).
    - **Observers:** Automatización de procesos basados en el ciclo de vida de Eloquent.
    - **Resources:** Transformación de respuestas API estandarizadas (JSON ordenado).
- **Robustez y Calidad:**
    - **Logs Centralizados:** Registro de errores y trazabilidad técnica en base de datos mediante un sistema centralizado.
    - **Try-Catch Blocks:** Manejo de excepciones estandarizado en controladores con Rollbacks de DB.

---

## 🔑 Convención de Permisos (Slugs)

Para que el sistema de autorización funcione correctamente, se debe seguir la nomenclatura:

> **`modulo.accion`**

- Ejemplos: `usuario.listar`, `usuario.crear`, `rol.asignar`, `empresa.configurar`.

---

## 🏗️ Instalación y Puesta en Marcha

1.  **Clonar y configurar:**
    ```bash
    git clone https://github.com/Jantonbernal/LaraBase-Starter.git
    cd LaraBase-Starter
    cp .env.example .env
    composer install
    php artisan key:generate
    ```
2.  **Migraciones y Seeders:**
    El proyecto incluye semillas para poblar roles, permisos, empresas y un usuario maestro:
    ```bash
    php artisan migrate --seed
    ```

---

## 👤 Acceso Inicial (Super Usuario)

Al ejecutar los seeders, se crea un usuario con privilegios totales:

- **Usuario:** `admin@admin.com` (o el definido en el seeder)
- **Rol:** `Administrador`
- **Empresa:** Vinculación automática a la empresa base.

---

## 📂 Gestión de Archivos (Service Layer)

El proyecto utiliza un servicio dedicado para el manejo de archivos, permitiendo mantener los controladores limpios y la lógica reutilizable:

```php
// Ejemplo de uso en controlador
public function store(UserRequest $request, FileUploadService $fileService)
{
    if ($request->hasFile('photo')) {
        $file = $fileService->uploadSingleFile($request->file('photo'), 'profiles');
        $user->update(['file_id' => $file->id]);
    }
}
```
