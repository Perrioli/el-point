
<?php
echo "🔗 CREANDO ENLACE PARA IMÁGENES\n";
echo "===============================\n\n";

$storagePath = __DIR__ . '/storage/app/public';
$publicStoragePath = __DIR__ . '/public/storage';

// Verificar archivos existentes
$archivos = glob($storagePath . '/productos/*');
echo "📁 Imágenes encontradas: " . count($archivos) . "\n";
foreach ($archivos as $archivo) {
    echo "   - " . basename($archivo) . "\n";
}
echo "\n";

// Eliminar enlace existente si existe
if (is_link($publicStoragePath)) {
    unlink($publicStoragePath);
    echo "🗑️ Enlace anterior eliminado\n";
} elseif (is_dir($publicStoragePath)) {
    // Eliminar directorio recursivamente en Windows
    function eliminarDir($dir) {
        if (is_dir($dir)) {
            $archivos = array_diff(scandir($dir), array('.', '..'));
            foreach ($archivos as $archivo) {
                $ruta = $dir . '/' . $archivo;
                is_dir($ruta) ? eliminarDir($ruta) : unlink($ruta);
            }
            rmdir($dir);
        }
    }
    eliminarDir($publicStoragePath);
    echo "🗑️ Directorio anterior eliminado\n";
}

// Crear enlace simbólico
echo "🔗 Creando enlace simbólico...\n";
if (symlink('../storage/app/public', $publicStoragePath)) {
    echo "✅ ¡Enlace simbólico creado exitosamente!\n";
} else {
    echo "⚠️ Enlace simbólico falló. Copiando archivos...\n";

    // Método alternativo: copiar archivos
    function copiarRecursivo($src, $dst) {
        if (!is_dir($dst)) {
            mkdir($dst, 0755, true);
        }

        $dir = opendir($src);
        while (($archivo = readdir($dir)) !== false) {
            if ($archivo != '.' && $archivo != '..') {
                $srcPath = $src . '/' . $archivo;
                $dstPath = $dst . '/' . $archivo;

                if (is_dir($srcPath)) {
                    copiarRecursivo($srcPath, $dstPath);
                } else {
                    copy($srcPath, $dstPath);
                    echo "   📋 Copiado: " . basename($archivo) . "\n";
                }
            }
        }
        closedir($dir);
    }

    copiarRecursivo($storagePath, $publicStoragePath);
    echo "✅ Archivos copiados exitosamente\n";
}

// Verificar resultado
echo "\n🧪 VERIFICACIÓN:\n";
if (is_dir($publicStoragePath)) {
    echo "✅ public/storage creado\n";

    $archivosPublic = glob($publicStoragePath . '/productos/*');
    echo "📁 Archivos accesibles: " . count($archivosPublic) . "\n";

    if (count($archivosPublic) > 0) {
        echo "\n🌐 URLs para probar:\n";
        foreach ($archivosPublic as $archivo) {
            $nombre = basename($archivo);
            echo "   http://localhost/storage/productos/$nombre\n";
        }
        echo "\n✅ ¡Ya puedes ver las imágenes en /tragos!\n";
    }
} else {
    echo "❌ Error: No se pudo crear public/storage\n";
}
?>
