<?php

namespace App\Modules\EntryGuides\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\EntryGuides\Application\UseCases\FindAllEntryGuide;
use App\Modules\EntryGuides\Application\UseCases\FindAllEntryGuideUseCase;
use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;
use Illuminate\Http\JsonResponse;


class ControllerEntryGuide extends Controller {

    public function __construct(private readonly EntryGuideRepositoryInterface $entryGuideRepositoryInterface){}

    public function index():JsonResponse{
        $entryGuideUseCase = new FindAllEntryGuideUseCase($this->entryGuideRepositoryInterface);
        $entryGuide = $entryGuideUseCase->execute();

        return new JsonResponse($entryGuide);
        
    }
}