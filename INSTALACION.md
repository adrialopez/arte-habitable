# Arte Habitable — Guía de Instalación WordPress

## 1. Activar SSH en cPanel

1. Entra en **cPanel** > busca **"Terminal"** o **"SSH Access"**
2. Si ves **"Terminal"**: ya tienes acceso directo al terminal. Úsalo.
3. Si ves **"SSH Access"**:
   - Click en **"Manage SSH Keys"**
   - Click **"Generate a New Key"**
   - Deja los campos por defecto (RSA, 2048 bits)
   - Pon una contraseña y pulsa **"Generate Key"**
   - Vuelve atrás y en tu key, pulsa **"Authorize"**
   - Descarga la private key para conectar desde tu Mac:
     ```bash
     ssh -p 22 usuario@tudominio.es
     ```
4. Si NO tienes SSH disponible, contacta a tu hosting para activarlo.

## 2. Instalar WP-CLI

Desde SSH o Terminal en cPanel:

```bash
cd ~
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
mv wp-cli.phar ~/bin/wp
echo 'export PATH="$HOME/bin:$PATH"' >> ~/.bashrc
source ~/.bashrc
wp --info
```

Si `~/bin` no existe: `mkdir ~/bin` antes del `mv`.

## 3. Subir e instalar el tema

### Opción A: Desde cPanel File Manager

1. Comprime la carpeta `arte-habitable-wp` como ZIP
2. cPanel > File Manager > navega a `public_html/wp-content/themes/`
3. Sube el ZIP y descomprímelo
4. Renombra la carpeta a `arte-habitable`

### Opción B: Desde SSH con WP-CLI

```bash
cd ~/public_html
# Si el tema ya está subido via FTP/SFTP:
wp theme activate arte-habitable
```

### Opción C: Desde tu Mac con SCP

```bash
# Comprimir
cd /Users/adrial/Projects
zip -r arte-habitable-wp.zip arte-habitable-wp/ -x "*.DS_Store" "*/Thumbs.db"

# Subir
scp arte-habitable-wp.zip usuario@tudominio.es:~/public_html/wp-content/themes/

# Desde SSH
cd ~/public_html/wp-content/themes/
unzip arte-habitable-wp.zip
mv arte-habitable-wp arte-habitable
wp theme activate arte-habitable
```

## 4. Configuración inicial

### Activar el tema
```bash
wp theme activate arte-habitable
```

### Instalar Contact Form 7
```bash
wp plugin install contact-form-7 --activate
```

### Crear el formulario de contacto
```bash
# Esto se hace desde wp-admin > Contact > Add New
# Campos sugeridos:
# [text* nombre placeholder "Nombre"]
# [email* email placeholder "Email"]
# [tel telefono placeholder "Teléfono"]
# [textarea* mensaje placeholder "Cuéntanos sobre tu proyecto..."]
# [submit "Enviar mensaje"]
```

### Configurar página de inicio
```bash
# Crear la página de inicio
wp post create --post_type=page --post_title='Inicio' --post_status=publish

# Configurar como front page
wp option update show_on_front page
wp option update page_on_front $(wp post list --post_type=page --name=inicio --field=ID)
```

### Configurar permalinks
```bash
wp rewrite structure '/%postname%/'
wp rewrite flush
```

## 5. Crear los proyectos

Desde wp-admin > Proyectos > Añadir Nuevo:

Para cada proyecto:
1. **Título**: nombre del proyecto (ej: "GDLC")
2. **Contenido**: descripción larga del proyecto
3. **Extracto**: descripción corta
4. **Imagen destacada**: imagen principal/thumbnail
5. **Detalles del Proyecto**:
   - Ubicación: "Sant Cugat del Vallès"
   - Tipo: "Residencia familiar"
   - Orden: número para ordenar (1, 2, 3...)
6. **Galería**: añadir imágenes del proyecto
7. **Tipo de Proyecto** (taxonomía): "Diseño y ejecución", "Reforma integral", etc.

### Con WP-CLI (ejemplo):
```bash
wp post create --post_type=proyecto --post_title='GDLC' --post_status=publish --post_content='Este proyecto nació con un objetivo claro: llenar de luz y calma el hogar de una familia de tres...'
wp post meta update <ID> _ah_location 'Sant Cugat del Vallès'
wp post meta update <ID> _ah_project_type 'Residencia familiar'
wp post meta update <ID> _ah_order 1
```

## 6. Configurar el equipo

Crear una página padre llamada **"Equipo"** (slug: `equipo`).
Luego crear páginas hijas para cada miembro:

- Título: nombre del miembro
- Extracto: cargo/rol
- Imagen destacada: foto del miembro
- Página padre: Equipo
- Orden: usar el campo "Orden" de la página

## 7. Personalizar contenido

Ir a **Apariencia > Personalizar**:

- **Hero / Portada**: slides, textos del hero
- **Estudio**: textos e imágenes de la sección sobre nosotros
- **Servicios**: texto introductorio e imágenes
- **Contacto**: email, Instagram, dirección, shortcode de CF7, imagen de fondo

## 8. Subir las imágenes

Las imágenes optimizadas están en `assets/img/` del tema.
Súbelas a la Biblioteca de medios de WordPress y asígnalas desde el Personalizador.

## Estructura de archivos del tema

```
arte-habitable/
├── style.css                    # Metadata del tema
├── functions.php                # Setup, enqueue, includes
├── screenshot.png               # Preview del tema
├── header.php                   # Header + nav
├── footer.php                   # Footer
├── front-page.php               # Template de la home
├── single-proyecto.php          # Template individual de proyecto
├── index.php                    # Fallback
├── inc/
│   ├── cpt-proyectos.php        # CPT + meta boxes + galería
│   ├── customizer.php           # Opciones del personalizador
│   └── helpers.php              # Funciones auxiliares
├── template-parts/
│   ├── section-hero.php
│   ├── section-estudio.php
│   ├── section-servicios.php
│   ├── section-portafolio.php
│   ├── section-equipo.php
│   ├── section-marcas.php
│   └── section-contacto.php
└── assets/
    ├── css/theme.css            # Estilos principales
    ├── js/main.js               # JavaScript principal
    └── img/                     # Imágenes optimizadas
```
