<?php

namespace App\Modules\DetailPcCompatible\Domain\Interface;

use App\Modules\DetailPcCompatible\Domain\Entities\DetailPcCompatible;

interface DetailPcCompatibleRepositoryInterface
{
    public function findAll():array;
    public function findById(int $id):?DetailPcCompatible;
    public function create(DetailPcCompatible $data):?DetailPcCompatible;
    public function update(DetailPcCompatible $data):?DetailPcCompatible;
}