#!/bin/bash
# Script to copy and rename images with project name + number format

SRC="/Users/adrial/Downloads/Arte Habitable"
DEST="/Users/adrial/Projects/arte-habitable-wp/imagenes-para-subir"

copy_folder() {
    local src_dir="$1"
    local dest_dir="$2"
    local prefix="$3"

    mkdir -p "$DEST/$dest_dir"
    local i=1

    # Use find with -print0 and while read to handle spaces
    while IFS= read -r -d '' f; do
        ext="${f##*.}"
        ext=$(echo "$ext" | tr '[:upper:]' '[:lower:]')
        cp "$f" "$DEST/$dest_dir/${prefix}-$(printf '%02d' $i).${ext}"
        i=$((i+1))
    done < <(find "$SRC/$src_dir" -maxdepth 1 -type f \( -iname '*.jpg' -o -iname '*.jpeg' -o -iname '*.png' \) -print0 | sort -z)

    echo "$prefix: $((i-1)) images"
}

# Projects
copy_folder "Casa Lavoisier" "casa-lavoisier" "casa-lavoisier"
copy_folder "Casa SQ" "casa-sq" "casa-sq"
copy_folder "OFICINAS IFF" "oficinas-iff" "oficinas-iff"
copy_folder "ORGAN 1+2" "organ" "organ"
copy_folder "P.G" "pg" "pg"
copy_folder "Vallès Occidental" "valles-occidental" "valles-occidental"

# Other
copy_folder "Equipo" "equipo" "equipo"
copy_folder "Contacto" "contacto" "contacto"
copy_folder "Showroom - Sobre nosotros" "showroom" "showroom"
copy_folder "Frontpage web" "frontpage" "frontpage"

# Root images
mkdir -p "$DEST"
for f in "$SRC"/*.jpg "$SRC"/*.png; do
    [ -f "$f" ] && cp "$f" "$DEST/$(basename "$f" | tr ' ' '-' | tr '[:upper:]' '[:lower:]')"
done

echo ""
echo "Done! All images in: $DEST"
