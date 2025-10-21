<?php

namespace App\Modules\DispatchNotes\Domain\Interfaces;

interface DispatchNotesRepositoryInterface {
    public function findAll():array;
}