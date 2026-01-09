<?php

namespace App\Modules\EntryGuides\Infrastructure\Request;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\EntryItemSerial\Infrastructure\Models\EloquentEntryItemSerial;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGuideRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'branch_id' => 'required|integer|exists:branches,id',
            'user_id' => 'required|integer|exists:users,id',
            'date' => 'string',
            'customer_id' => 'required|integer|exists:customers,id',
            'observations' => 'nullable|string',
            'ingress_reason_id' => 'required|integer|exists:ingress_reasons,id',
            'reference_serie' => 'nullable|string',
            'reference_correlative' => 'nullable|string',
            'subtotal' => 'nullable|numeric',
            'total_descuento' => 'nullable|numeric',
            'reference_document_id' => 'required|integer|exists:document_types,id',
            'total' => 'nullable|numeric',
            'descuento' => 'nullable|numeric',
            'update_price' => 'nullable|boolean',
            'entry_guide_articles' => 'required|array|min:1',
            'entry_guide_articles.*.article_id' => 'required|integer|exists:articles,id',
            'entry_guide_articles.*.description' => 'required|string',
            'entry_guide_articles.*.quantity' => 'required|numeric',
            'entry_guide_articles.*.serials' => 'nullable|array',
            'entry_guide_articles.*.serials.*' => 'required|string|distinct',
            'entry_guide_articles.*.precio_costo' => 'nullable|numeric',
            'entry_guide_articles.*.subtotal' => 'nullable|numeric',
            'entry_guide_articles.*.total' => 'nullable|numeric',
            'entry_guide_articles.*.descuento' => 'nullable|numeric',
            'entry_guide_articles.*.saldo' => 'nullable|numeric',
            'order_purchase_id' => 'nullable|array',
            // 'order_purchase_id.*.entry_guide_id' => 'required|integer',

            'document_entry_guide' => 'required|array',
            'document_entry_guide.reference_document_id' => 'required|integer|exists:document_types,id',
            'document_entry_guide.reference_serie' => 'nullable|string',
            'document_entry_guide.reference_correlative' => 'nullable|string',
            'currency_id' => 'required|integer|exists:currency_types,id',
            'entry_igv' => 'nullable|numeric',
            'includ_igv' => 'nullable|boolean',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateSerials($validator);
        });
    }

    protected function validateSerials($validator)
    {
        $entryGuideArticles = $this->input('entry_guide_articles', []);
        $entryGuideId = $this->route('id');
        $allSerials = [];

        foreach ($entryGuideArticles as $index => $entryGuideArticle) {
            $article = EloquentArticle::find($entryGuideArticle['article_id']);

            if (!$article) {
                continue;
            }

            $serials = $entryGuideArticle['serials'] ?? [];
            $quantity = $entryGuideArticle['quantity'];

            // Validar artículos que requieren serie
            if ($article->series_enabled) {
                if (empty($serials)) {
                    $validator->errors()->add(
                        "entry_guide_articles.{$index}.serials",
                        "El artículo '{$article->description}' requiere series."
                    );
                } elseif (count($serials) !== $quantity) {
                    $validator->errors()->add(
                        "entry_guide_articles.{$index}.serials",
                        "El artículo '{$article->description}' requiere {$quantity} series, pero se proporcionaron " . count($serials) . "."
                    );
                }

                // Obtener series actuales de la base de datos para este artículo y guía
                $existingSerialsInDb = EloquentEntryItemSerial::where('entry_guide_id', $entryGuideId)
                    ->where('article_id', $article->id)
                    ->get();

                $existingSerialsList = $existingSerialsInDb->pluck('serial')->toArray();

                // 1. Validar series QUE FALTAN (que se están eliminando)
                $missingSerials = array_diff($existingSerialsList, $serials);

                foreach ($missingSerials as $missingSerial) {
                    // Buscar la serie en la colección traída de BD
                    $dbSerial = $existingSerialsInDb->firstWhere('serial', $missingSerial);

                    // Si la serie que se quiere borrar NO está en estado 1, error.
                    if ($dbSerial && $dbSerial->status != 1) {
                        $validator->errors()->add(
                            "entry_guide_articles.{$index}.serials",
                            "La serie '{$missingSerial}' no se puede eliminar porque ya ha sido usada."
                        );
                    }
                }

                // 2. Validar series QUE SE AGREGAN o QUE SE MANTIENEN (solo por integridad, aunque el usuario dijo "validar que solo permita actualizar las series que tienen estado 1")
                // El requerimiento original decía: "validar que solo se permita actualizar las series que tienen estado 1"
                // El nuevo requerimiento dice: "consultar el registro previamente, hacer una comparacion de que si hay series faltantes, y si las hay que recien entre en la validación las series que están faltando"
                //
                // Interpretación: 
                // Si yo quito una serie, debo validar que esa serie sea "borrable/status=1".
                // Si yo agrego/mantengo una serie, ¿debo validarla?
                // El usuario dijo "el frontend me manda las series a actualizar... nunca va a entrar a validacion porque no me manda la serie borrada".
                // Esto implica que su preocupación principal es evitar BORRAR series usadas.
                // PERO, si yo incluyo una serie que YA ESTABA usada (status=0) y la mando de vuelta... el código anterior validaba que TODAS las series en el request tengan status 1. 
                // Si una serie ya se usó (status=0), y yo la mando de nuevo en el request (porque no la borré), el código anterior fallaba diciendo "ya usada".
                // ¿Es esto deseado? "validar que solo se permita actualizar las series que tienen estado 1" -> Si la serie ya se usó, ¿puedo mantenerla en la guía?
                // Probablemente NO debería poder editar la guía si hay series usadas involucradas, O, debería permitir dejarlas quietas?
                // Generalmente, si una serie ya se vendió, no deberías poder "tocarla" en la guía de ingreso original. 
                // Pero si solo estoy cambiando la cantidad de OTRO artículo, y re-evío la serie usada... ¿debería fallar?
                // El usuario dijo: "solo se permita actualizar las series que tienen estado 1, si tiene otro estado que no permita".
                // Esto sugiere que NO se puede tocar (ni agregar ni quitar ni mantener?) series con status != 1.
                //
                // Sin embargo, el NUEVO comentario se enfoca en las BORRADAS. "nunca va a entrar a esa validación porque no me manda la serie borrada".
                // Si la serie NO se borra (se manda en el request), mi código anterior fallaba si status != 1.
                // Si la serie ESTÁ en la BD como usada, y la mando igual... Validar que "ya usada" bloquearía cualquier update.
                // 
                // Asumiré que el objetivo es PROTEGER series usadas de ser manipuladas.
                // 1. Si intento BORRAR una serie usada -> ERROR (No puedo desvincularla porque ya se vendió).
                // 2. Si intento AGREGAR una serie (que ya existe en otro lado y está usada) -> ERROR.
                // 3. Si MANTENGO una serie usada (la mando igual que estaba) -> ¿Error o Pass?
                //
                // Si mi código anterior fallaba al encontrar status!=1 en el request, entonces bloqueaba MANTENER series usadas.
                // Si el sistema re-guarda todo, idealmente no debería tocar lo que no cambia.
                //
                // Voy a mantener SOLO la validación de las FALTANTES como pidió explícitamente ahora, 
                // Y quizás la validación de las PRESENTES solo si son NUEVAS? o "state of check".
                //
                // "validar que solo se permita actualizar las series que tienen estado 1" -> Esto puede significar "Solo puedes cambiar (CRUD) series en estado 1".
                // Si una serie está en estado 0, debería ser inmutable. 
                // Si la mando IGUAL, no la estoy actualizando en teoria (aunque el PUT pise todo).
                // 
                // Vamos con la lógica pedida: "hacer una comparación de que si hay series faltantes, y si las hay que recien entre en la validación las series que están faltando y muestre ese mensaje."
                // Parece que QUIERE validar SOLO las faltantes.
                // Pero si agrego una serie usada? Debería validarlo?
                // Me limitaré estrictamente a las FALTANTES como pidió para solucionar SU problema específico.
                // Pero dejaré la validación de duplicados global.

                $allSerials = array_merge($allSerials, $serials);
            } else {
                // Validar que artículos sin serie no tengan series
                if (!empty($serials)) {
                    $validator->errors()->add(
                        "entry_guide_articles.{$index}.serials",
                        "El artículo '{$article->description}' no maneja series."
                    );
                }
            }
        }

        // Validar que no haya series duplicadas en toda la venta
        if (count($allSerials) !== count(array_unique($allSerials))) {
            $validator->errors()->add(
                'entry_guide_articles',
                'Hay números de serie duplicados en la guía de ingreso.'
            );
        }
    }
    public function messages()
    {
        return [
            'company_id.required' => 'La compañia es obligatoria.',
            'currency_id.required' => 'La moneda es obligatoria.',
            'branch_id.required' => 'La sucursal es obligatoria.',
            'customer_id.required' => 'El cliente es obligatorio.',
            'ingress_reason_id.required' => 'La razón de ingreso es obligatoria.',
            'reference_document_id.required' => 'El documento de referencia es obligatorio.',
            'entry_guide_articles.required' => 'Los artículos de la guía de ingreso son obligatorios.',
            'entry_guide_articles.*.article_id.required' => 'El artículo es obligatorio.',
            'entry_guide_articles.*.quantity.required' => 'La cantidad es obligatoria.',
            // 'entry_guide_articles.*.serials.required' => 'Los números de serie son obligatorios.',
            // 'entry_guide_articles.*.serials.*.required' => 'El número de serie es obligatorio.',
            'entry_guide_articles.*.serials.*.distinct' => 'No se permiten números de serie duplicados.',
            // 'entry_guide_articles.*.subtotal.required' => 'El subtotal es obligatorio.',
            // 'entry_guide_articles.*.total.required' => 'El total es obligatorio.',
            // 'entry_guide_articles.*.precio_costo.required' => 'El precio de costo es obligatorio.',
            // 'entry_guide_articles.*.descuento.required' => 'El descuento es obligatorio.',
            'document_entry_guide.reference_document_id.required' => 'El documento de referencia es obligatorio.',
            // 'document_entry_guide.reference_serie.required' => 'La serie de referencia es obligatoria.',
            // 'document_entry_guide.reference_correlative.required' => 'El correlativo de referencia es obligatorio.',
            // 'document_entry_guide.reference_document_id.exists' => 'El documento de referencia no existe.',
            // 'document_entry_guide.reference_serie.exists' => 'La serie de referencia no existe.',
            // 'document_entry_guide.reference_correlative.exists' => 'El correlativo de referencia no existe.',
        ];
    }
}
