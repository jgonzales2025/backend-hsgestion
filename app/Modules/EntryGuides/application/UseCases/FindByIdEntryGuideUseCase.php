<?php
namespace App\Modules\EntryGuides\Application\UseCases;

use App\Modules\EntryGuides\Domain\Interfaces\EntryGuideRepositoryInterface;


class FindByIdEntryGuideUseCase
{
    public function __construct(private readonly EntryGuideRepositoryInterface $entryGuideRepositoryInterface)
    {
    }


    public function execute(int $id)
    {
        return $this->entryGuideRepositoryInterface->findById($id);
    }
}