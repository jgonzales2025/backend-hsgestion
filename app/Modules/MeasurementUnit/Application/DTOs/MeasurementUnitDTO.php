<?php

namespace App\Modules\MeasurementUnit\Application\DTOs;

class MeasurementUnitDTO
{
    public $name;
    public $abbreviation;
    public $status;

    public function __construct(array $data)
    {
        $this->name = $data['name'];
        $this->abbreviation = $data['abbreviation'];
        $this->status = $data['status'];
    }
}
