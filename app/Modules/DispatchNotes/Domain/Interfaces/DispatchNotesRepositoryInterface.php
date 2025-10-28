<?php

namespace App\Modules\DispatchNotes\Domain\Interfaces;

use App\Modules\DispatchNotes\Domain\Entities\DispatchNote;

interface DispatchNotesRepositoryInterface {
    public function findAll():array;
     public function save(DispatchNote $dispatchNote):?DispatchNote;
      public function findById(int $id):?DispatchNote;
       public function update(DispatchNote $dispatchNote):?DispatchNote;

}