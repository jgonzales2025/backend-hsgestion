<?php

namespace App\Modules\Customer\Infrastructure\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Customer\Application\DTOs\CustomerDTO;
use App\Modules\Customer\Application\UseCases\CreateCustomerSunatApiUseCase;
use App\Modules\Customer\Application\UseCases\CreateCustomerUseCase;
use App\Modules\Customer\Application\UseCases\FindAllCustomersExcludingCompaniesUseCase;
use App\Modules\Customer\Application\UseCases\FindAllCustomersSuppliers;
use App\Modules\Customer\Application\UseCases\FindAllCustomersUseCase;
use App\Modules\Customer\Application\UseCases\FindAllUnassignedCustomerUseCase;
use App\Modules\Customer\Application\UseCases\FindByIdCustomerUseCase;
use App\Modules\Customer\Application\UseCases\FindCustomerByDocumentNumberUseCase;
use App\Modules\Customer\Application\UseCases\FindCustomerCompanyUseCase;
use App\Modules\Customer\Application\UseCases\UpdateCustomerUseCase;
use App\Modules\Customer\Domain\Interfaces\CustomerRepositoryInterface;
use App\Modules\Customer\Infrastructure\Persistence\EloquentCustomerRepository;
use App\Modules\Customer\Infrastructure\Requests\StoreCustomerRequest;
use App\Modules\Customer\Infrastructure\Requests\UpdateCustomerRequest;
use App\Modules\Customer\Infrastructure\Resources\CustomerAllResource;
use App\Modules\Customer\Infrastructure\Resources\CustomerCompanyResource;
use App\Modules\Customer\Infrastructure\Resources\CustomerResource;
use App\Modules\CustomerAddress\Application\DTOs\CustomerAddressDTO;
use App\Modules\CustomerAddress\Application\UseCases\CreateCustomerAddressUseCase;
use App\Modules\CustomerAddress\Application\UseCases\FindByIdCustomerAddressUseCase;
use App\Modules\CustomerAddress\Application\UseCases\UpdateCustomerAddressUseCase;
use App\Modules\CustomerAddress\Domain\Interfaces\CustomerAddressRepositoryInterface;
use App\Modules\CustomerAddress\Infrastructure\Models\EloquentCustomerAddress;
use App\Modules\CustomerAddress\Infrastructure\Resources\CustomerAddressResource;
use App\Modules\CustomerEmail\Application\DTOs\CustomerEmailDTO;
use App\Modules\CustomerEmail\Application\UseCases\CreateCustomerEmailUseCase;
use App\Modules\CustomerEmail\Application\UseCases\FindByCustomerIdEmailUseCase;
use App\Modules\CustomerEmail\Application\UseCases\UpdateCustomerEmailUseCase;
use App\Modules\CustomerEmail\Domain\Interfaces\CustomerEmailRepositoryInterface;
use App\Modules\CustomerEmail\Infrastructure\Models\EloquentCustomerEmail;
use App\Modules\CustomerEmail\Infrastructure\Resources\CustomerEmailResource;
use App\Modules\CustomerPhone\Application\DTOs\CustomerPhoneDTO;
use App\Modules\CustomerPhone\Application\UseCases\CreateCustomerPhoneUseCase;
use App\Modules\CustomerPhone\Application\UseCases\FindByCustomerIdPhoneUseCase;
use App\Modules\CustomerPhone\Application\UseCases\UpdateCustomerPhonesUseCase;
use App\Modules\CustomerPhone\Domain\Interfaces\CustomerPhoneRepositoryInterface;
use App\Modules\CustomerPhone\Infrastructure\Models\EloquentCustomerPhone;
use App\Modules\CustomerPhone\Infrastructure\Resources\CustomerPhoneResource;
use App\Modules\CustomerPortfolio\Application\DTOs\CustomerPortfolioDTO;
use App\Modules\CustomerPortfolio\Application\UseCases\CreateCustomerPortfolioUseCase;
use App\Modules\CustomerPortfolio\Domain\Interfaces\CustomerPortfolioRepositoryInterface;
use App\Modules\Ubigeo\Departments\Domain\Interfaces\DepartmentRepositoryInterface;
use App\Modules\Ubigeo\Districts\Domain\Interfaces\DistrictRepositoryInterface;
use App\Modules\Ubigeo\Provinces\Domain\Interfaces\ProvinceRepositoryInterface;
use App\Modules\User\Domain\Interfaces\UserRepositoryInterface;
use App\Services\ApiSunatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Modules\Customer\Application\UseCases\UpdateStatusUseCase;
use App\Modules\CustomerDocumentType\Domain\Interfaces\CustomerDocumentTypeRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{

    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly CustomerPhoneRepositoryInterface $customerPhoneRepository,
        private readonly CustomerEmailRepositoryInterface $customerEmailRepository,
        private readonly CustomerAddressRepositoryInterface $customerAddressRepository,
        private readonly DepartmentRepositoryInterface $departmentRepository,
        private readonly ProvinceRepositoryInterface $provinceRepository,
        private readonly DistrictRepositoryInterface $districtRepository,
        private readonly CustomerPortfolioRepositoryInterface $customerPortfolioRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly ApiSunatService $apiSunatService,
        private readonly CustomerDocumentTypeRepositoryInterface $customerDocumentTypeRepository
    ) {
    }

    public function index(Request $request): array
    {
        $customerName = $request->query('customer_name');
        $documentNumber = $request->query('document_number');
        $customersUseCase = new FindAllCustomersUseCase($this->customerRepository);
        $customers = $customersUseCase->execute($customerName, $documentNumber);

        return CustomerAllResource::collection($customers)->resolve();
    }

    public function store(StoreCustomerRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $validatedData = $request->validated();
            $role = request()->get('role');

            $customerDTO = new CustomerDTO($validatedData);
            $customerUseCase = new CreateCustomerUseCase($this->customerRepository, $this->customerDocumentTypeRepository);
            $customer = $customerUseCase->execute($customerDTO);

            if ($role == 'Vendedor') {
                $userId = request()->get('user_id');
                $customerPortfolioDTO = new CustomerPortfolioDTO(['customer_ids' => [$customer->getId()], 'user_id' => $userId]);
                $customerPortfolioUseCase = new CreateCustomerPortfolioUseCase($this->customerPortfolioRepository, $this->customerRepository, $this->userRepository);
                $customerPorfolio = $customerPortfolioUseCase->execute($customerPortfolioDTO);
            }

            // Crear los teléfonos con foreach
            $phones = [];
            if (!empty($validatedData['phones'])) {
                $createPhoneUseCase = new CreateCustomerPhoneUseCase($this->customerPhoneRepository);

                foreach ($validatedData['phones'] as $phoneData) {
                    $customerPhoneDTO = new CustomerPhoneDTO([
                        'phone' => $phoneData['phone'],
                        'customer_id' => $customer->getId(),
                        'status' => 1
                    ]);
                    $phones[] = $createPhoneUseCase->execute($customerPhoneDTO);
                }
            }

            // Crear los emails con foreach
            $emails = [];
            if (!empty($validatedData['emails'])) {
                $createEmailUseCase = new CreateCustomerEmailUseCase($this->customerEmailRepository);

                foreach ($validatedData['emails'] as $emailData) {
                    $customerEmailDTO = new CustomerEmailDTO([
                        'email' => $emailData['email'],
                        'customer_id' => $customer->getId(),
                        'status' => 1
                    ]);
                    $emails[] = $createEmailUseCase->execute($customerEmailDTO);
                }
            }

            // Crear las direcciones con foreach
            $createCustomerAddressUseCase = new CreateCustomerAddressUseCase(
                $this->customerAddressRepository,
                $this->departmentRepository,
                $this->provinceRepository,
                $this->districtRepository,
            );

            $addresses = [];
            foreach ($validatedData['addresses'] as $addressData) {
                $customerAddressDTO = new CustomerAddressDTO([
                    'customer_id' => $customer->getId(),
                    'address' => $addressData['address'],
                    'department_id' => $addressData['department_id'],
                    'province_id' => $addressData['province_id'],
                    'district_id' => $addressData['district_id'],
                    'status' => 1,
                    'st_principal' => $addressData['st_principal'] ?? 0,
                ]);
                $addresses[] = $createCustomerAddressUseCase->execute($customerAddressDTO);
            }

            return response()->json([
                'customer' => (new CustomerResource($customer))->resolve(),
                'phones' => CustomerPhoneResource::collection($phones)->resolve(),
                'emails' => CustomerEmailResource::collection($emails)->resolve(),
                'addresses' => CustomerAddressResource::collection($addresses)->resolve(),
            ], 201);
        });
    }

    public function show(int $id): JsonResponse
    {
        $customerUseCase = new FindByIdCustomerUseCase($this->customerRepository);
        $customer = $customerUseCase->execute($id);

        return response()->json(
            [
                'customer' => (new CustomerResource($customer))->resolve(),
                'phones' => CustomerPhoneResource::collection($customer->getPhones())->resolve(),
                'emails' => CustomerEmailResource::collection($customer->getEmails())->resolve(),
                'addresses' => CustomerAddressResource::collection($customer->getAddresses())->resolve(),
            ],
            200
        );
    }

    public function update(UpdateCustomerRequest $request, int $id): JsonResponse
    {
        return DB::transaction(function () use ($request, $id) {
            $validatedData = $request->validated();

            $customerDTO = new CustomerDTO($validatedData);
            $customerUseCase = new UpdateCustomerUseCase($this->customerRepository, $this->customerDocumentTypeRepository);
            $customer = $customerUseCase->execute($id, $customerDTO);

            $phones = [];
            if (!empty($validatedData['phones'])) {
                EloquentCustomerPhone::where('customer_id', $id)->delete();
                $createPhoneUseCase = new CreateCustomerPhoneUseCase($this->customerPhoneRepository);
                foreach ($validatedData['phones'] as $phoneData) {
                    $customerPhoneDTO = new CustomerPhoneDTO([
                        'phone' => $phoneData['phone'],
                        'customer_id' => $id,
                        'status' => $phoneData['status'],
                    ]);
                    $phones[] = $createPhoneUseCase->execute($customerPhoneDTO);
                }
            }

            $emails = [];
            if (!empty($validatedData['emails'])) {
                EloquentCustomerEmail::where('customer_id', $id)->delete();
                $createEmailUseCase = new CreateCustomerEmailUseCase($this->customerEmailRepository);
                foreach ($validatedData['emails'] as $emailData) {
                    $customerEmailDTO = new CustomerEmailDTO([
                        'email' => $emailData['email'],
                        'customer_id' => $id,
                        'status' => $emailData['status'],
                    ]);
                    $emails[] = $createEmailUseCase->execute($customerEmailDTO);
                }
            }

            $addresses = [];
            if (!empty($validatedData['addresses'])) {
                EloquentCustomerAddress::where('customer_id', $id)->delete();
                $createCustomerAddressUseCase = new CreateCustomerAddressUseCase(
                    $this->customerAddressRepository,
                    $this->departmentRepository,
                    $this->provinceRepository,
                    $this->districtRepository,
                );
                foreach ($validatedData['addresses'] as $addressData) {
                    $customerAddressDTO = new CustomerAddressDTO([
                        'customer_id' => $id,
                        'address' => $addressData['address'],
                        'department_id' => $addressData['department_id'],
                        'province_id' => $addressData['province_id'],
                        'district_id' => $addressData['district_id'],
                        'status' => $addressData['status'],
                        'st_principal' => $addressData['st_principal'] ?? 0,
                    ]);
                    $addresses[] = $createCustomerAddressUseCase->execute($customerAddressDTO);
                }
            }

            return response()->json([
                'customer' => (new CustomerResource($customer))->resolve(),
                'phones' => CustomerPhoneResource::collection($phones)->resolve(),
                'emails' => CustomerEmailResource::collection($emails)->resolve(),
                'addresses' => CustomerAddressResource::collection($addresses)->resolve(),
            ]);
        });
    }

    public function findAllSuppliers(): array
    {
        $customersUseCase = new FindAllCustomersSuppliers($this->customerRepository);
        $customers = $customersUseCase->execute();

        return CustomerAllResource::collection($customers)->resolve();
    }

    public function findCustomerCompany(): JsonResponse
    {
        $customerUseCase = new FindCustomerCompanyUseCase($this->customerRepository);
        $customer = $customerUseCase->execute();

        return response()->json([
            'customer' => (new CustomerCompanyResource($customer))->resolve(),
            'addresses' => CustomerAddressResource::collection($customer->getAddresses())->resolve(),
        ]);

    }
    public function findAllCustomersExceptionCompanies(Request $request): JsonResponse
    {
        $customerName = $request->query('customer_name');
        $status = $request->query('status') !== null ? (int) $request->query('status') : null;
        $documentTypeId = $request->query('document_type_id');
        $recordTypeId = $request->query('record_type_id');

        $customersUseCase = new FindAllCustomersExcludingCompaniesUseCase($this->customerRepository);
        $customers = $customersUseCase->execute($customerName, $status, $documentTypeId, $recordTypeId);

        return new JsonResponse([
            'data' => CustomerAllResource::collection($customers)->resolve(),
            'current_page' => $customers->currentPage(),
            'per_page' => $customers->perPage(),
            'total' => $customers->total(),
            'last_page' => $customers->lastPage(),
            'next_page_url' => $customers->nextPageUrl(),
            'prev_page_url' => $customers->previousPageUrl(),
            'first_page_url' => $customers->url(1),
            'last_page_url' => $customers->url($customers->lastPage()),
        ]);
    }

    public function storeCustomerBySunatApi(Request $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $documentNumber = $request->query('document_number');
            $document = $request->query('document');

            if (!$documentNumber) {
                return response()->json(['error' => 'No ha enviado el número de documento'], 422);
            }

            $data = $this->apiSunatService->getDataByDocument($documentNumber);

            if (!$data['success']) {
                return response()->json(['error' => 'Documento no válido'], 422);
            }

            $findCustomerUseCase = new FindCustomerByDocumentNumberUseCase($this->customerRepository);
            $customer = $findCustomerUseCase->execute($documentNumber);

            if ($customer) {
                return response()->json(['error' => 'Cliente ya existe'], 409);
            }

            $documentNumberValue = $data['data']['ruc'] ?? $data['data']['document_number'];

            $customerDTO = new CustomerDTO([
                'customer_document_type_id' => strlen($documentNumber) == 11 ? 2 : 3,
                'document_number' => $documentNumberValue,
                'company_name' => $data['data']['razsoc'] ?? null,
                'name' => $data['data']['first_name'] ?? null,
                'lastname' => $data['data']['first_last_name'] ?? null,
                'second_lastname' => $data['data']['second_last_name'] ?? null,
                'is_withholding_applicable' => $data['data']['es_agente_retencion'] ?? null,
            ]);

            $customerUseCase = new CreateCustomerSunatApiUseCase($this->customerRepository, $this->customerDocumentTypeRepository);
            $customer = $customerUseCase->execute($customerDTO, $document);

            $address = null;

            if (isset($data['data']['ubigeo']) && $data['data']['ubigeo'] !== '-') {
                $ubigeo = $data['data']['ubigeo'];
                $departmentId = substr($ubigeo, 0, 2);
                $provinceId = substr($ubigeo, 2, 2);
                $districtId = substr($ubigeo, 4, 2);

                $createCustomerAddressUseCase = new CreateCustomerAddressUseCase(
                    $this->customerAddressRepository,
                    $this->departmentRepository,
                    $this->provinceRepository,
                    $this->districtRepository,
                );

                $customerAddressDTO = new CustomerAddressDTO([
                    'customer_id' => $customer->getId(),
                    'address' => $data['data']['direccion'],
                    'department_id' => $departmentId,
                    'province_id' => $provinceId,
                    'district_id' => $districtId,
                    'status' => 1,
                    'st_principal' => 1,
                ]);
                $address = $createCustomerAddressUseCase->execute($customerAddressDTO);
            }

            $userId = request()->get('user_id');
            $customerPortfolioDTO = new CustomerPortfolioDTO(['customer_ids' => [$customer->getId()], 'user_id' => $userId]);
            $customerPortfolioUseCase = new CreateCustomerPortfolioUseCase($this->customerPortfolioRepository, $this->customerRepository, $this->userRepository);
            $customerPorfolio = $customerPortfolioUseCase->execute($customerPortfolioDTO);

            return response()->json([
                'customer' => (new CustomerResource($customer))->resolve(),
                'address' => $address ? (new CustomerAddressResource($address))->resolve() : null
            ], 201);
        });
    }

    public function updateStatus(int $id, Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $status = $validatedData['status'];

        $updateStatusUseCase = new UpdateStatusUseCase($this->customerRepository);
        $updateStatusUseCase->execute($id, $status);

        return response()->json(['message' => 'Estado actualizado correctamente'], 200);
    }

    public function findAllUnassigned(): array
    {
        $customersUseCase = new FindAllUnassignedCustomerUseCase($this->customerRepository);
        $customers = $customersUseCase->execute();

        return CustomerAllResource::collection($customers)->resolve();
    }
}
