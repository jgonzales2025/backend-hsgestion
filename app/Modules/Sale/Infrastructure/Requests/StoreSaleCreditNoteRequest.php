<?php

namespace App\Modules\Sale\Infrastructure\Requests;

use App\Modules\Sale\Application\UseCases\FindSaleWithUpdatedQuantitiesUseCase;
use App\Modules\Sale\Domain\Interfaces\SaleRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;

class StoreSaleCreditNoteRequest extends FormRequest
{

    public function __construct(
        private readonly SaleRepositoryInterface $saleRepository,
        private readonly FindSaleWithUpdatedQuantitiesUseCase $findSaleWithUpdatedQuantitiesUseCase
    ) {}

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'branch_id' => 'required|integer|exists:branches,id',
            'document_type_id' => 'required|integer|exists:document_types,id',
            'serie' => 'required|string|max:10',
            'parallel_rate' => 'required|numeric|min:0',
            'customer_id' => 'required|integer|exists:customers,id',
            'date' => 'required|date',
            'due_date' => 'required|date',
            'days' => 'required|integer',
            'user_id' => 'required|integer|exists:users,id',
            'payment_type_id' => 'required|integer|exists:payment_types,id',
            'currency_type_id' => 'required|integer|exists:currency_types,id',
            'subtotal' => 'required|numeric|min:0',
            'igv' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'reference_document_type_id' => 'required_if:document_type_id,7|required_if:document_type_id,8|integer|exists:document_types,id',
            'reference_serie' => 'required_if:document_type_id,7|required_if:document_type_id,8|string|max:10',
            'reference_correlative' => 'required_if:document_type_id,7|required_if:document_type_id,8|string|max:10',
            'note_reason_id' => 'required_if:document_type_id,7|required_if:document_type_id,8|integer|exists:note_reasons,id',

            'sale_articles' => 'required|array|min:1',
            'sale_articles.*.article_id' => 'required|integer',
            'sale_articles.*.description' => 'required|string',
            'sale_articles.*.quantity' => 'required|integer|min:1',
            'sale_articles.*.unit_price' => 'required|numeric|min:0',
            'sale_articles.*.public_price' => 'required|numeric|min:0',
            'sale_articles.*.subtotal' => 'required|numeric|min:0',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('document_type_id') == 7) {
                $paddedCorrelative = str_pad($this->input('reference_correlative'), 5, '0', STR_PAD_LEFT);
                $result = $this->findSaleWithUpdatedQuantitiesUseCase->execute(
                    (int) $this->input('reference_document_type_id'),
                    $this->input('reference_serie'),
                    $paddedCorrelative
                );

                if (!$result) {
                    $validator->errors()->add('reference_correlative', 'La venta de referencia no existe.');
                    return;
                }

                $articles = $result['articles'];
                $saleArticles = $this->input('sale_articles', []);

                foreach ($saleArticles as $index => $article) {
                    $articleId = $article['article_id'];
                    $requestedQuantity = $article['quantity'];

                    $originalArticle = collect($articles)->firstWhere('article_id', $articleId);
                    if (!$originalArticle) {
                        $validator->errors()->add("sale_articles.{$index}.article_id", 'El artículo no existe en la venta original.');
                        continue;
                    }

                    $availableQuantity = $originalArticle['updated_quantity'];
                    if ($requestedQuantity > $availableQuantity) {
                        $validator->errors()->add("sale_articles.{$index}.quantity", "La cantidad solicitada ({$requestedQuantity}) excede la disponible ({$availableQuantity}) en la venta original.");
                    }
                }
            }
            if ($this->input('document_type_id') == 8) {
                $saleArticles = $this->input('sale_articles', []);
                foreach ($saleArticles as $index => $article) {
                    if (isset($article['unit_price']) && $article['unit_price'] <= 0) {
                        $validator->errors()->add("sale_articles.{$index}.unit_price", 'El precio unitario debe ser mayor a 0 para notas de débito.');
                    }
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'company_id.required' => 'La empresa es obligatoria.',
            'company_id.exists' => 'La empresa seleccionada no existe.',
            'branch_id.required' => 'La sucursal es obligatoria.',
            'branch_id.exists' => 'La sucursal seleccionada no existe.',
            'document_type_id.required' => 'El tipo de documento es obligatorio.',
            'document_type_id.exists' => 'El tipo de documento seleccionado no existe.',
            'serie.required' => 'La serie es obligatoria.',
            'serie.max' => 'La serie no puede exceder :max caracteres.',
            'parallel_rate.required' => 'El tipo de cambio es obligatorio.',
            'parallel_rate.numeric' => 'El tipo de cambio debe ser un número.',
            'parallel_rate.min' => 'El tipo de cambio debe ser mayor o igual a 0.',
            'customer_id.required' => 'El cliente es obligatorio.',
            'customer_id.exists' => 'El cliente seleccionado no existe.',
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'La fecha debe ser una fecha válida.',
            'due_date.required' => 'La fecha de vencimiento es obligatoria.',
            'due_date.date' => 'La fecha de vencimiento debe ser una fecha válida.',
            'days.required' => 'Los días son obligatorios.',
            'days.integer' => 'Los días deben ser un número entero.',
            'user_id.required' => 'El usuario es obligatorio.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
            'payment_type_id.required' => 'El tipo de pago es obligatorio.',
            'payment_type_id.exists' => 'El tipo de pago seleccionado no existe.',
            'currency_type_id.required' => 'La moneda es obligatoria.',
            'currency_type_id.exists' => 'La moneda seleccionada no existe.',
            'subtotal.required' => 'El subtotal es obligatorio.',
            'subtotal.numeric' => 'El subtotal debe ser un número.',
            'subtotal.min' => 'El subtotal debe ser mayor o igual a 0.',
            'igv.required' => 'El IGV es obligatorio.',
            'igv.numeric' => 'El IGV debe ser un número.',
            'igv.min' => 'El IGV debe ser mayor o igual a 0.',
            'total.required' => 'El total es obligatorio.',
            'total.numeric' => 'El total debe ser un número.',
            'total.min' => 'El total debe ser mayor o igual a 0.',
            'reference_document_type_id.required_if' => 'El tipo de documento de referencia es obligatorio para notas de crédito/débito.',
            'reference_document_type_id.exists' => 'El tipo de documento de referencia seleccionado no existe.',
            'reference_serie.required_if' => 'La serie de referencia es obligatoria para notas de crédito/débito.',
            'reference_serie.max' => 'La serie de referencia no puede exceder :max caracteres.',
            'reference_correlative.required_if' => 'El correlativo de referencia es obligatorio para notas de crédito/débito.',
            'reference_correlative.max' => 'El correlativo de referencia no puede exceder :max caracteres.',
            'note_reason_id.required_if' => 'El motivo de la nota es obligatorio para notas de crédito/débito.',
            'note_reason_id.exists' => 'El motivo de la nota seleccionado no existe.',
            'sale_articles.required' => 'Debe agregar al menos un artículo.',
            'sale_articles.array' => 'Los artículos deben ser un arreglo.',
            'sale_articles.min' => 'Debe agregar al menos un artículo.',
            'sale_articles.*.article_id.required' => 'El artículo es obligatorio.',
            'sale_articles.*.article_id.exists' => 'El artículo seleccionado no existe.',
            'sale_articles.*.description.required' => 'La descripción del artículo es obligatoria.',
            'sale_articles.*.quantity.required' => 'La cantidad es obligatoria.',
            'sale_articles.*.quantity.min' => 'La cantidad debe ser al menos 1.',
            'sale_articles.*.unit_price.required' => 'El precio unitario es obligatorio.',
            'sale_articles.*.unit_price.min' => 'El precio unitario debe ser mayor o igual a 0.',
            'sale_articles.*.public_price.required' => 'El precio público es obligatorio.',
            'sale_articles.*.public_price.min' => 'El precio público debe ser mayor o igual a 0.',
            'sale_articles.*.subtotal.required' => 'El subtotal del artículo es obligatorio.',
            'sale_articles.*.subtotal.min' => 'El subtotal del artículo debe ser mayor o igual a 0.',
        ];
    }
}
