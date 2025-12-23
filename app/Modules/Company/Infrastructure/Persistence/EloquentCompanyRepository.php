<?php
namespace App\Modules\Company\Infrastructure\Persistence;

use App\Modules\Company\Domain\Entities\Company;
use App\Modules\Company\Domain\Entities\UpdateCompany;
use App\Modules\Company\Domain\Interfaces\CompanyRepositoryInterface;
use App\Modules\Company\Infrastructure\Model\EloquentCompany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;



class EloquentCompanyRepository implements CompanyRepositoryInterface
{
    public function findAllCompanys(?string $description, ?int $status){
       $companys = EloquentCompany::with('branches')->orderBy('created_at')
       ->when($description, fn($query) => $query->where('company_name', 'like', "%{$description}%")
           ->orWhere('ruc', 'like', "%{$description}%"))
       ->when($status !== null, fn($query) => $query->where('status', $status))
       ->paginate(10);
       
        $companys->getCollection()->transform(fn($company) => new Company(
                id:$company->id,
                ruc:$company->ruc,
                company_name:$company->company_name,
                address:$company->address,
                start_date:$company->start_date,
                ubigeo:$company->ubigeo,
                status:$company->status,
                default_currency_type:$company->defaultCurrencyType->toDomain($company->defaultCurrencyType),
                min_profit:$company->min_profit,
                max_profit:$company->max_profit,
                detrac_cta_banco:$company->detrac_cta_banco,
            ));
        return $companys;
    }
    public function findById(int $id):?Company{
        $company = EloquentCompany::with('assignments')->find($id);
        if (!$company) {
            return null;
        }

        //  Log::info('companys', $company->toArray());
        return new Company(
            id:$company->id,
            ruc:$company->ruc,
            company_name:$company->company_name,
            address:$company->address,
            start_date:$company->start_date,
            ubigeo:$company->ubigeo,
            status:$company->status,
            default_currency_type:$company->defaultCurrencyType->toDomain($company->defaultCurrencyType),
            min_profit:$company->min_profit,
            max_profit:$company->max_profit,
            detrac_cta_banco:$company->detrac_cta_banco,
        );
    }
   public function indexByUser(int $userId): array
    {
        $companies = EloquentCompany::with('assignments')
            ->whereHas('assignments', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->get();

        if ($companies->isEmpty()) {
            return [];
        }

        return $companies->map(function ($company) {
            return new Company(
                id: $company->id,
                ruc: $company->ruc,
                company_name: $company->company_name,
                address: $company->address,
                start_date: $company->start_date,
                ubigeo: $company->ubigeo,
                status: $company->status,
                default_currency_type:$company->defaultCurrencyType->toDomain($company->defaultCurrencyType),
                min_profit:$company->min_profit,
                max_profit:$company->max_profit,
                detrac_cta_banco:$company->detrac_cta_banco,
            );
        })->toArray();
    }

    public function update(int $id, UpdateCompany $updateCompany): void
    {
        EloquentCompany::where('id', $id)->update([
            'default_currency_type_id' => $updateCompany->getDefaultCurrencyTypeId(),
            'min_profit' => $updateCompany->getMinProfit(),
            'max_profit' => $updateCompany->getMaxProfit(),
            'detrac_cta_banco' => $updateCompany->getDetracCtaBanco(),
        ]);
    }

}
