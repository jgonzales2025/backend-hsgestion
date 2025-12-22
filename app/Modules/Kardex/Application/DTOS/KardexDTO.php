<?php
namespace App\Modules\Kardex\Application\DTOS;


class KardexDTO
{
        public $company_id;
        public $branch_id;
        public $codigo;
        public $is_today;
        public $description;
        public $before_fech;
        public $after_fech;
        public $status;

    public function __construct(array $data){
        $this->company_id = $data['company_id'];
        $this->branch_id = $data['branch_id'];
        $this->codigo = $data['codigo'];
        $this->is_today = $data['is_today'];
        $this->description = $data['description'];
        $this->before_fech = $data['before_fech'];
        $this->after_fech = $data['after_fech'];
        $this->status = $data['status'];
    }
}