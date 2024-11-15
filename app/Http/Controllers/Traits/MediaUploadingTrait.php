<?php
namespace App\Http\Controllers\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait MediaUploadingTrait
{
    public function storeMedia(Request $request)
    {
        // Validar el tamaño del archivo (si está especificado)
        if ($request->has('size')) {
            $this->validate($request, [
                'file' => 'max:' . ($request->input('size') * 1024),
            ]);
        }

        // Validación de imagen (si tiene dimensiones)
        if ($request->has('width') || $request->has('height')) {
            $this->validate($request, [
                'file' => sprintf(
                    'image|dimensions:max_width=%s,max_height=%s',
                    $request->input('width', 100000),
                    $request->input('height', 100000)
                ),
            ]);
        }

        // Validar el tipo de archivo permitido (por ejemplo: jpg, png, pdf)
        $this->validate($request, [
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:10240', // Tamaño máximo de 10MB
        ]);

        // Obtener el archivo
        $file = $request->file('file');

        // Generar un nombre único para el archivo
        $name = Str::random(40) . '_' . time() . '.' . $file->getClientOriginalExtension();

        // Determinar el disco a utilizar (puedes cambiarlo por S3 u otros)
        $disk = 'local';  // Cambia esto según tu configuración de disco en config/filesystems.php

        // Guardar el archivo en el almacenamiento
        $path = Storage::disk($disk)->putFileAs('tmp/uploads', $file, $name);

        // Si se desea guardar la URL pública (solo en local o si tienes un disco público configurado)
        $url = Storage::disk($disk)->url($path);

        return response()->json([
            'name' => $name,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'url' => $url,  // Solo en caso de que desees retornar la URL
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);
    }
}
