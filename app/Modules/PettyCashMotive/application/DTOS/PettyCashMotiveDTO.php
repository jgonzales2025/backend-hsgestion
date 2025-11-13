<?php
namespace App\Modules\PettyCashMotive\Application\DTOS;

class PettyCashMotiveDTO{
    public int $company_id;
    public string $description;
    public int $receipt_type;
    public int $user_id;
    public string $date;
    public int $user_mod;
    public string $date_mod;
    public int $status;
    public function __construct(array $array){
        $this->company_id = $array['company_id'] ;
        $this->description = $array['description'] ?? '';
        $this->receipt_type = $array['receipt_type'] ?? 1;
        $this->user_id = $array['user_id'];
        $this->date = $array['date'] ?? '2025-11-13';
        $this->user_mod = $array['user_mod']??1;
        $this->date_mod = $array['date_mod']?? '2025-11-13';
        $this->status = $array['status'] ?? 1;
    }
}