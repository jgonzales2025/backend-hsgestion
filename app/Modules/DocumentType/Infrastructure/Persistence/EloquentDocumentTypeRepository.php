<?php

namespace App\Modules\DocumentType\Infrastructure\Persistence;

use App\Modules\DocumentType\Domain\Entities\DocumentType;
use App\Modules\DocumentType\Domain\Interfaces\DocumentTypeRepositoryInterface;
use App\Modules\DocumentType\Infrastructure\Models\EloquentDocumentType;

class EloquentDocumentTypeRepository implements DocumentTypeRepositoryInterface
{

    public function findAll(): array
    {
        $eloquentDocumentType = EloquentDocumentType::all();

        if ($eloquentDocumentType->isEmpty()){
            return [];
        }

        return $eloquentDocumentType->map(function ($eloquentDocumentType){
            return new DocumentType(
                id: $eloquentDocumentType->id,
                cod_sunat: $eloquentDocumentType->cod_sunat,
                description: $eloquentDocumentType->description,
                abbreviation: $eloquentDocumentType->abbreviation,
                st_sales: $eloquentDocumentType->st_sales,
                st_purchases: $eloquentDocumentType->st_purchases,
                st_collections: $eloquentDocumentType->st_collections,
                st_invoices: $eloquentDocumentType->st_invoices,
                status: $eloquentDocumentType->status
            );
        })->toArray();
    }

    public function findById($id): ?DocumentType
    {
        $eloquentDocumentType = EloquentDocumentType::find($id);

        if (!$eloquentDocumentType){
            return null;
        }

        return new DocumentType(
            id: $eloquentDocumentType->id,
            cod_sunat: $eloquentDocumentType->cod_sunat,
            description: $eloquentDocumentType->description,
            abbreviation: $eloquentDocumentType->abbreviation,
            st_sales: $eloquentDocumentType->st_sales,
            st_purchases: $eloquentDocumentType->st_purchases,
            st_collections: $eloquentDocumentType->st_collections,
            st_invoices: $eloquentDocumentType->st_invoices,
            status: $eloquentDocumentType->status
        );
    }

    public function findAllForSales(): array
    {
        $eloquentDocumentTypes = EloquentDocumentType::where('st_sales', true)->get();

        if ($eloquentDocumentTypes->isEmpty()){
            return [];
        }

        return $eloquentDocumentTypes->map(function ($eloquentDocumentType){
            return new DocumentType(
                id: $eloquentDocumentType->id,
                cod_sunat: $eloquentDocumentType->cod_sunat,
                description: $eloquentDocumentType->description,
                abbreviation: $eloquentDocumentType->abbreviation,
                st_sales: $eloquentDocumentType->st_sales,
                st_purchases: $eloquentDocumentType->st_purchases,
                st_collections: $eloquentDocumentType->st_collections,
                st_invoices: $eloquentDocumentType->st_invoices,
                status: $eloquentDocumentType->status
            );
        })->toArray();
    }

    public function findAllForInvoices(): array
    {
        $eloquentDocumentTypes = EloquentDocumentType::where('st_invoices', true)->get();

        if ($eloquentDocumentTypes->isEmpty()){
            return [];
        }

        return $eloquentDocumentTypes->map(function ($eloquentDocumentType){
            return new DocumentType(
                id: $eloquentDocumentType->id,
                cod_sunat: $eloquentDocumentType->cod_sunat,
                description: $eloquentDocumentType->description,
                abbreviation: $eloquentDocumentType->abbreviation,
                st_sales: $eloquentDocumentType->st_sales,
                st_purchases: $eloquentDocumentType->st_purchases,
                st_collections: $eloquentDocumentType->st_collections,
                st_invoices: $eloquentDocumentType->st_invoices,
                status: $eloquentDocumentType->status
            );
        })->toArray();
    }

    public function findAllForPettyCash(): array
    {
        $eloquentDocumentTypes = EloquentDocumentType::where('st_petty_cash', true)->get();

        if ($eloquentDocumentTypes->isEmpty()){
            return [];
        }

        return $eloquentDocumentTypes->map(function ($eloquentDocumentType){
            return new DocumentType(
                id: $eloquentDocumentType->id,
                cod_sunat: $eloquentDocumentType->cod_sunat,
                description: $eloquentDocumentType->description,
                abbreviation: $eloquentDocumentType->abbreviation,
                st_sales: $eloquentDocumentType->st_sales,
                st_purchases: $eloquentDocumentType->st_purchases,
                st_collections: $eloquentDocumentType->st_collections,
                st_invoices: $eloquentDocumentType->st_invoices,
                status: $eloquentDocumentType->status
            );
        })->toArray();
    }
    public function findAllForPettyCashInfinite()
    {
        return EloquentDocumentType::query()
            ->where('st_petty_cash', true)
            ->orderBy('id', 'asc')
            ->cursorPaginate(10);
    }

    public function findAllForDocumentSales(): array
    {
        $eloquentDocumentTypes = EloquentDocumentType::where('st_document_sales', true)->get();

        if ($eloquentDocumentTypes->isEmpty()){
            return [];
        }

        return $eloquentDocumentTypes->map(function ($eloquentDocumentType){
            return new DocumentType(
                id: $eloquentDocumentType->id,
                cod_sunat: $eloquentDocumentType->cod_sunat,
                description: $eloquentDocumentType->description,
                abbreviation: $eloquentDocumentType->abbreviation,
                st_sales: $eloquentDocumentType->st_sales,
                st_purchases: $eloquentDocumentType->st_purchases,
                st_collections: $eloquentDocumentType->st_collections,
                st_invoices: $eloquentDocumentType->st_invoices,
                status: $eloquentDocumentType->status
            );
        })->toArray();
    }

    public function findAllForPurchases(): array
    {
        $eloquentDocumentTypes = EloquentDocumentType::where('st_purchases', true)->get();

        if ($eloquentDocumentTypes->isEmpty()){
            return [];
        }

        return $eloquentDocumentTypes->map(function ($eloquentDocumentType){
            return new DocumentType(
                id: $eloquentDocumentType->id,
                cod_sunat: $eloquentDocumentType->cod_sunat,
                description: $eloquentDocumentType->description,
                abbreviation: $eloquentDocumentType->abbreviation,
                st_sales: $eloquentDocumentType->st_sales,
                st_purchases: $eloquentDocumentType->st_purchases,
                st_collections: $eloquentDocumentType->st_collections,
                st_invoices: $eloquentDocumentType->st_invoices,
                status: $eloquentDocumentType->status
            );
        })->toArray();
    }

    public function findAllForEntryGuides(): array
    {
        $eloquentDocumentTypes = EloquentDocumentType::where('st_almacen', true)->get();

        if ($eloquentDocumentTypes->isEmpty()){
            return [];
        }

        return $eloquentDocumentTypes->map(function ($eloquentDocumentType){
            return new DocumentType(
                id: $eloquentDocumentType->id,
                cod_sunat: $eloquentDocumentType->cod_sunat,
                description: $eloquentDocumentType->description,
                abbreviation: $eloquentDocumentType->abbreviation,
                st_sales: $eloquentDocumentType->st_sales,
                st_purchases: $eloquentDocumentType->st_purchases,
                st_collections: $eloquentDocumentType->st_collections,
                st_invoices: $eloquentDocumentType->st_invoices,
                status: $eloquentDocumentType->status
            );
        })->toArray();
    }

    public function findAllForReferencesSales(): array
    {
        $eloquentDocumentTypes = EloquentDocumentType::where('st_references_sales', true)->get();

        if ($eloquentDocumentTypes->isEmpty()){
            return [];
        }

        return $eloquentDocumentTypes->map(function ($eloquentDocumentType){
            return new DocumentType(
                id: $eloquentDocumentType->id,
                cod_sunat: $eloquentDocumentType->cod_sunat,
                description: $eloquentDocumentType->description,
                abbreviation: $eloquentDocumentType->abbreviation,
                st_sales: $eloquentDocumentType->st_sales,
                st_purchases: $eloquentDocumentType->st_purchases,
                st_collections: $eloquentDocumentType->st_collections,
                st_invoices: $eloquentDocumentType->st_invoices,
                status: $eloquentDocumentType->status
            );
        })->toArray();
    }
}
