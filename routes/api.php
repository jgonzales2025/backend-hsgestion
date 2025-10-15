<?php


use App\Http\Controllers\MenuController;
use App\Modules\Articles\Infrastructure\Controllers\ArticleController;
use App\Http\Controllers\RoleController;
use App\Modules\Auth\Infrastructure\Controllers\AuthController;
use App\Modules\Bank\Infrastructure\Controllers\BankController;
use App\Modules\Branch\Infrastructure\Controllers\BranchController;
use App\Modules\Brand\Infrastructure\Controllers\BrandController;
use App\Modules\Category\Infrastructure\Controllers\CategoryController;
use App\Modules\Company\Infrastructure\Controllers\CompanyController;
use App\Modules\CurrencyType\Infrastructure\Controllers\CurrencyTypeController;
use App\Modules\Customer\Infrastructure\Controllers\CustomerController;
use App\Modules\CustomerDocumentType\Infrastructure\Controllers\CustomerDocumentTypeController;
use App\Modules\CustomerPhone\Infrastructure\Controllers\CustomerPhoneController;
use App\Modules\CustomerType\Infrastructure\Controllers\CustomerTypeController;
use App\Modules\Driver\Infrastructure\Controllers\DriverController;
use App\Modules\EmissionReason\Infrastructure\Controllers\EmissionReasonController;
use App\Modules\ExchangeRate\Infrastructure\Controllers\ExchangeRateController;
use App\Modules\IngressReason\Infrastructure\Controllers\IngressReasonController;
use App\Modules\MeasurementUnit\Infrastructure\Controllers\MeasurementUnitController;
use App\Modules\PaymentType\Infrastructure\Controllers\PaymentTypeController;
use App\Modules\PercentageIGV\Infrastructure\Controllers\PercentageIGVController;
use App\Modules\RecordType\Infrastructure\Controllers\RecordTypeController;
use App\Modules\SubCategory\Infrastructure\Controllers\SubCategoryController;
use App\Modules\TransportCompany\Infrastructure\Controllers\TransportCompanyController;
use App\Modules\Ubigeo\Departments\Infrastructure\Controllers\DepartmentController;
use App\Modules\Ubigeo\Provinces\Infrastructure\Controllers\ProvinceController;
use App\Modules\User\Infrastructure\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Modules\Ubigeo\Districts\Infrastructure\Controllers\DistrictController;
use App\Modules\PaymentMethod\Infrastructure\Controllers\PaymentMethodController;
use App\Modules\DocumentType\Infrastructure\Controllers\DocumentTypeController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/roles', [RoleController::class, 'index']);
Route::get('/roles/{id}', [RoleController::class, 'show']);
Route::get('/permissions', [RoleController::class, 'indexPermissions']);
Route::post('/roles', [RoleController::class, 'store']);
Route::put('/roles/{id}', [RoleController::class, 'update']);

Route::get('/usernames', [UserController::class, 'findAllUserName']);



//recordType
Route::get('/recordType', [RecordTypeController::class, 'index']);



//branches
Route::get('/branches', [BranchController::class, 'index']);
Route::get('/branches/{id}', [BranchController::class, 'show']);
Route::put('/branches/{id}', [BranchController::class, 'update']);
Route::get('/branchesID/{id}', [BranchController::class, 'showId']);

//company
Route::get('/companies', [CompanyController::class, 'index']);
Route::get('/companies/{id}', [CompanyController::class, 'show']);
Route::get('/companies-user', [CompanyController::class, 'indexByUser']);


Route::get('/menus', [MenuController::class, 'index']);

// User routes devuelvelo como estaba protegido
Route::get('/users', [UserController::class, 'findAllUsers']);
Route::get('/users-vendedor', [UserController::class, 'findAllUsersByVendedor']);
Route::get('/users-almacen', [UserController::class, 'findAllUsersByAlmacen']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);


// TIPOS DE DOCUMENTOS (DNI, RUC, ETC)
Route::get('driver-document-types', [CustomerDocumentTypeController::class, 'indexForDrivers']);

// Marcas
Route::get('brands', [BrandController::class, 'index']);
Route::post('brands', [BrandController::class, 'store']);
Route::get('brands/{id}', [BrandController::class, 'show']);
Route::put('brands/{id}', [BrandController::class, 'update']);

// Drivers - conductores
Route::get('drivers',[DriverController::class, 'index']);
Route::post('drivers',[DriverController::class, 'store']);
Route::get('drivers/{id}',[DriverController::class, 'show']);
Route::put('drivers/{id}',[DriverController::class, 'update']);

// Categories - categorias
Route::get('categories',[CategoryController::class, 'index']);
Route::post('categories',[CategoryController::class, 'store']);
Route::get('categories/{id}',[CategoryController::class, 'show']);
Route::put('categories/{id}',[CategoryController::class, 'update']);

// SubCategories - subcategorias
Route::get('sub-categories',[SubCategoryController::class, 'index']);
Route::get('sub-categories/category/{id}',[SubCategoryController::class, 'findByCategoryId']);
Route::post('sub-categories',[SubCategoryController::class, 'store']);
Route::get('sub-categories/{id}',[SubCategoryController::class, 'show']);
Route::put('sub-categories/{id}',[SubCategoryController::class, 'update']);

// TransportCompanies - Empresa de transportes
Route::get('transport-companies',[TransportCompanyController::class, 'index']);
Route::post('transport-companies',[TransportCompanyController::class, 'store']);
Route::get('transport-companies/{id}',[TransportCompanyController::class, 'show']);
Route::put('transport-companies/{id}',[TransportCompanyController::class, 'update']);

// PercentageIGV - Porcentaje de IGV
Route::get('percentage-igv',[PercentageIGVController::class, 'index']);
Route::post('percentage-igv',[PercentageIGVController::class, 'store']);
Route::get('percentage-igv/{id}',[PercentageIGVController::class, 'show']);
Route::put('percentage-igv/{id}',[PercentageIGVController::class, 'update']);

// MeasurementUnits - Unidades de medida
Route::get('measurement-units', [MeasurementUnitController::class, 'index']);
Route::post('measurement-units', [MeasurementUnitController::class, 'store']);
Route::get('measurement-units/{id}', [MeasurementUnitController::class, 'show']);
Route::put('measurement-units/{id}', [MeasurementUnitController::class, 'update']);

// Customer types - Tipos de clientes
Route::get('customer-types', [CustomerTypeController::class, 'index']);

//customer
Route::get('customer-document-types', [CustomerDocumentTypeController::class, 'index']);


// Customers - Clientes
Route::get('customers', [CustomerController::class, 'index']);
Route::post('customers', [CustomerController::class, 'store']);
Route::get('customers/{id}', [CustomerController::class, 'show']);
Route::put('customers/{id}', [CustomerController::class, 'update']);


// Customer phones - Telefonos de clientes
Route::get('customer-phones', [CustomerPhoneController::class, 'index']);

// UBIGEO
Route::get('departments', [DepartmentController::class, 'index']);
Route::get('provinces/{id}', [ProvinceController::class, 'index']);
Route::get('districts/{coddep}/{codpro}', [DistrictController::class, 'index']);

//PaymentType
Route::get('paymentType', [PaymentTypeController::class,'index']);
Route::get('paymentType/{id}', [PaymentTypeController::class,'show']);

//currencyType
Route::get('currencyType',[CurrencyTypeController::class,'index']);
//articles
Route::get('articles',[ArticleController::class,'index']);
Route::post('articles-save',[ArticleController::class,'store']);
Route::get('articles/{id}',[ArticleController::class,'show']);
Route::put('articles/{id}',[ArticleController::class,'update']);

// Exchange Rates - Tipo de cambio
Route::get('exchange-rates', [ExchangeRateController::class, 'index']);
Route::get('exchange-rates/current', [ExchangeRateController::class, 'current']);
Route::get('exchange-rates/{id}', [ExchangeRateController::class, 'show']);
Route::put('exchange-rates/{id}', [ExchangeRateController::class, 'update']);

Route::get('/payment-methods', [PaymentMethodController::class, 'findAllPaymentMethods']);

// Emission Reasons - Motivos de emisión
Route::get('emission-reasons', [EmissionReasonController::class, 'index']);

// Ingress Reasons - Motivos de ingreso
Route::get('ingress-reasons', [IngressReasonController::class, 'index']);

// Tipos de documentos
Route::get('document-types', [DocumentTypeController::class, 'index']);

// Banks - Bancos
Route::get('banks', [BankController::class, 'index']);
Route::post('banks', [BankController::class, 'store']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

});

