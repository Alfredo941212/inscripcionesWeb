
## Sistema de inscripciones deportivas y culturales

Aplicación Laravel que permite a trabajadores universitarios registrarse, subir documentación y elegir disciplinas deportivas y culturales. El comité organizador valida la información, gestiona estados y genera reportes (PDF/XLSX). Un panel de supervisión muestra métricas de cupo.

### Requisitos de entorno

- PHP 8.1+
- MySQL o MariaDB
- Composer
- Node.js (solo si se recompilan assets)

### Instalación rápida

`ash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install && npm run build # opcional
`

### Credenciales iniciales

- Administrador: dmin@example.com / Admin123!
- Supervisor: supervisor@example.com / Supervisor123!

Los participantes crean su cuenta desde el formulario de registro.

### Comandos útiles

- php artisan migrate:fresh --seed – reinicia la base con datos semilla.
- php artisan storage:link – habilita acceso público a documentos subidos.

### Reportes

- Descarga PDF y Excel desde el panel administrativo.
- Los archivos XLSX se generan temporalmente en storage/app/reports y se eliminan tras la descarga.

### Próximos pasos sugeridos

- Definir proveedor SMTP solo cuando se habiliten notificaciones.
- Añadir pruebas automáticas para validaciones y reportes.
- Personalizar estilos o agregar nuevas métricas según necesidades del comité.

