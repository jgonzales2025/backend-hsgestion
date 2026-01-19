<?php

namespace App\Modules\Warranty\Infrastructure\Model;

use App\Modules\Articles\Infrastructure\Models\EloquentArticle;
use App\Modules\Branch\Infrastructure\Models\EloquentBranch;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use App\Modules\Customer\Infrastructure\Models\EloquentCustomer;
use App\Modules\EntryGuides\Infrastructure\Models\EloquentEntryGuide;
use App\Modules\Sale\Infrastructure\Models\EloquentSale;
use App\Modules\WarrantyStatus\Infrastructure\Model\EloquentWarrantyStatus;
use Illuminate\Database\Eloquent\Model;

class EloquentWarranty extends Model
{
    protected $table = "warranties";

    protected $fillable = [
        "document_type_warranty_id",
        "company_id",
        "branch_id",
        "branch_sale_id",
        "serie",
        "correlative",
        "article_id",
        "serie_art",
        "date",
        "reference_sale_id",
        "customer_id",
        "customer_phone",
        "customer_email",
        "failure_description",
        "observations",
        "diagnosis",
        "supplier_id",
        "entry_guide_id",
        "contact",
        "follow_up_diagnosis",
        "follow_up_status",
        "solution",
        "warranty_status_id",
        "solution_date",
        "delivery_description",
        "delivery_serie_art",
        "credit_note_serie",
        "credit_note_correlative",
        "delivery_date",
        "dispatch_note_serie",
        "dispatch_note_correlative",
        "dispatch_note_date"
    ];

    protected $hidden = ['created_at', 'updated_at'];

    public function company()
    {
        return $this->belongsTo(EloquentCompany::class);
    }

    public function branch()
    {
        return $this->belongsTo(EloquentBranch::class);
    }

    public function branch_sale()
    {
        return $this->belongsTo(EloquentBranch::class);
    }

    public function article()
    {
        return $this->belongsTo(EloquentArticle::class);
    }

    public function reference_sale()
    {
        return $this->belongsTo(EloquentSale::class);
    }

    public function customer()
    {
        return $this->belongsTo(EloquentCustomer::class);
    }

    public function supplier()
    {
        return $this->belongsTo(EloquentCustomer::class);
    }

    public function entry_guide()
    {
        return $this->belongsTo(EloquentEntryGuide::class);
    }

    public function warranty_status()
    {
        return $this->belongsTo(EloquentWarrantyStatus::class);
    }
}