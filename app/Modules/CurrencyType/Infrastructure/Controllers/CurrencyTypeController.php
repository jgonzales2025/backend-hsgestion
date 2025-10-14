<?php

namespace App\Modules\CurrencyType\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CurrencyType\Application\UseCases\FindAllCurrencyType;
use App\Modules\CurrencyType\Application\UseCases\FindAllCurrencyTypeUseCase;
use App\Modules\CurrencyType\Infrastructure\Persistence\EloquentCurrencyTypeRepository;
use App\Modules\CurrencyType\Infrastructure\Resources\CurrentTypeResource;

class CurrencyTypeController extends Controller{
      protected $currencyTypeRepository;

      public function __construct(){
        $this->currencyTypeRepository = new EloquentCurrencyTypeRepository();
      }
      public function index():array{
        $currencyTypeUseCase = new FindAllCurrencyTypeUseCase($this->currencyTypeRepository);
        $currencyType = $currencyTypeUseCase->execute();
        return CurrentTypeResource::collection($currencyType)->resolve();
      }
}