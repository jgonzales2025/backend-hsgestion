<?php 
namespace App\Modules\RecordType\Application\DTOs;
class RecordTypeDTO
{
    public $name;
    public $abbreviation;
    public $status;

    public function __construct(array $data){
         $this ->name = $data['name'];
         $this -> abbreviation = $data['abbreviation'];
         $this -> status = $data['status'];
    }
}