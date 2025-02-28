<?php

namespace App\Http\Controllers;

use App\Models\Recetas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
/**
 * @OA\Info(
 *     title="API de Recetas",
 *     version="1.0.0",
 *     description="Documentación de la API de recetas, donde puedes obtener información sobre las recetas disponibles"
 * )
 */
class recetasController extends Controller
{
    /*Como anotacion hay que recalcar que el response()->json() es una funcion que nos permite retornar un JSON con un código de estado HTTP
    y que el metodo json() nos permite convertir un array en un JSON.
    El metodo json acepta dos parametros, el primero es el array que queremos convertir en JSON y el segundo es el código de estado HTTP que queremos retornar.
    */

    /**
 * @OA\Get(
 *     path="/api/recetas",
 *     tags={"Recetas"},
 *     summary="Obtener lista de recetas",
 *     description="Este endpoint devuelve todos las recetas disponibles en la tienda",
 *     @OA\Response(
 *         response=200,
 *         description="Lista de recetas",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="nombre", type="string", description="Nombre de la receta"),
 *                 @OA\Property(property="descripcion", type="text", description="Descripción de la receta"),
 *                 @OA\Property(property="imagen", type="string", description="URL de la imagen de la receta"),
 *                 @OA\Property(property="precio", type="number", format="float", description="Precio de la receta"),
 *                 @OA\Property(property="tipo", type="string", description="Tipo de receta")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Solicitud inválida"
 *     )
 * )
 */

    public function index(){
        $recetas=Recetas::all(); //Obtenemos todas las recetas
        return response()->json($recetas,200); //Retornamos todas las recetas en formato JSON
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [ //Validamos los datos que nos llegan
            'nombre' => 'required|max:100',
            'descripcion' => 'required',
            'imagen' => 'nullable|string', 
            'precio' => 'required|min:0|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'tipo' => 'required|in:desayuno,comida,cena'
        ]);

        if ($validator->fails()) { // Si hay errores de validación, retornamos un mensaje de error
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422);
        }
        $receta = Recetas::create([ //Creamos la receta
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'imagen' => $request->imagen,
            'precio' => $request->precio,
            'tipo' => $request->tipo,
        ]);
        return response()->json([ //Retornamos un mensaje de éxito y la receta creada
            'message' => 'Receta guardada',
            'receta' => $receta
        ],201);
    }

    public function show($identificador){
        $receta = Recetas::find($identificador); //Buscamos la receta por su identificador
        if($receta){
            return response()->json([
                'message' => 'Receta encontrada',
                'receta' => $receta
            ], 200);
        }else{
            return response()->json([
                'message' => 'Receta no encontrada'
            ],404);
        }
    }

    public function buscarPorTipo($tipoReceta){
        $recetas = Recetas::where('tipo', $tipoReceta)->get(); // Buscamos las recetas por el tipo
        if($recetas->isEmpty()){ // Si no se encuentran recetas, retornamos un mensaje de error
            return response()->json([
                'message' => 'No se encontraron recetas'
            ], 404);
        }
        return response()->json([
            'message' => 'Recetas encontradas',
            'recetas' => $recetas
        ], 200);
        
    }

/**
 * @OA\Delete(
 *     path="/api/recetas/{id}",
 *     tags={"Recetas"},
 *     summary="Eliminar una receta",
 *     description="Este endpoint permite eliminar una receta por su ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la receta",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Receta eliminada exitosamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Receta eliminada")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Receta no encontrada"
 *     )
 * )
 */
    
    public function destroy($identificador){
        $receta = Recetas::find($identificador);   //Buscamos la receta por su identificador
        if($receta){
            $receta->delete(); //Eliminamos la receta
            return response()->json([
                'message' => 'Receta eliminada'
            ],200);
        }else{
            return response()->json([
                'message' => 'Receta no encontrada'
            ],404);
        }
    }

    /**
 * @OA\Put(
 *     path="/api/recetas/{id}/editar",
 *     tags={"Recetas"},
 *     summary="Actualizar una receta existente",
 *     description="Este endpoint permite actualizar una receta por su ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID de la receta",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(property="nombre", type="string", description="Nombre de la receta"),
 *                 @OA\Property(property="descripcion", type="string", description="Descripción de la receta"),
 *                 @OA\Property(property="imagen", type="string", description="URL de la imagen de la receta"),
 *                 @OA\Property(property="precio", type="number", format="float", description="Precio de la receta"),
 *                 @OA\Property(property="tipo", type="string", description="Tipo de receta (desayuno, comida, cena)")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Receta actualizada exitosamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Receta actualizada"),
 *             @OA\Property(property="receta", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Errores de validación"
 *     )
 * )
 */

    public function update($identificador, Request $request){
        $validator = Validator::make($request->all(), [ //Validamos los datos que nos llegan
            'nombre' => 'required|max:100',
            'descripcion' => 'required',
            'imagen' => 'nullable|string',  // Ahora espera una URL, no un archivo
            'precio' => 'required|min:0|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'tipo' => 'required|in:desayuno,comida,cena'
        ]);
    
        if ($validator->fails()) { // Si hay errores de validación, retornamos un mensaje de error
            return response()->json([
                'message' => 'Errores de validación',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $receta = Recetas::find($identificador); // Buscamos la receta por su identificador
        $receta->nombre = $request->nombre;
        $receta->descripcion = $request->descripcion;
        $receta->precio = $request->precio;
        $receta->tipo = $request->tipo;
    
        if (!empty($request->imagen)) {
            $receta->imagen = $request->imagen;  // Guardamos la URL de la imagen
        }
    
        $receta->save();
    
        return response()->json([
            'message' => 'Receta actualizada',
            'receta' => $receta
        ], 200);
    }

    public function subirImagen(Request $request){
        $validator = Validator::make($request->all(), [ //Validamos los datos que nos llegan
            'imagen' => 'required|image|mimes:jpg,jpeg,png|max:5120'
        ]);

        if ($validator->fails()) { // Si hay errores de validación, retornamos un mensaje de error
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('imagen') && $request->file('imagen')->isValid()) { // Si hay un archivo y es válido
            $image = $request->file('imagen'); // Obtenemos la imagen
            $imageName = time() . '_' . $image->getClientOriginalName(); // Creamos un nombre único para la imagen
            $imagePath = $image->storeAs('img', $imageName, 'public'); // Guardamos la imagen en la carpeta 'img' del disco 'public'
            $imageUrl = asset('storage/' . 'img/' . $imageName); // Obtenemos la URL de la imagen
            return response()->json([ 
                'message' => 'Imagen subida correctamente',
                'image_url' => $imageUrl,
            ], 200);
        }

        return response()->json([
            'message' => 'Error al subir la imagen'
        ], 500);
    }


}
