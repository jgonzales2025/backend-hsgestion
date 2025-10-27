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
use App\Modules\CustomerPortfolio\Infrastructure\Controllers\CustomerPortfolioController;
use App\Modules\CustomerType\Infrastructure\Controllers\CustomerTypeController;
use App\Modules\DigitalWallet\Infrastructure\Controllers\DigitalWalletController;
use App\Modules\DispatchArticle\Infrastructure\Controllers\DispatchArticleController;
use App\Modules\DispatchNotes\Infrastructure\Controllers\DispatchNotesController;
use App\Modules\Driver\Infrastructure\Controllers\DriverController;
use App\Modules\EmissionReason\Infrastructure\Controllers\EmissionReasonController;
use App\Modules\ExchangeRate\Infrastructure\Controllers\ExchangeRateController;
use App\Modules\IngressReason\Infrastructure\Controllers\IngressReasonController;
use App\Modules\MeasurementUnit\Infrastructure\Controllers\MeasurementUnitController;
use App\Modules\MonthlyClosure\Infrastructure\Controllers\MonthlyClosureController;
use App\Modules\PaymentType\Infrastructure\Controllers\PaymentTypeController;
use App\Modules\PercentageIGV\Infrastructure\Controllers\PercentageIGVController;
use App\Modules\RecordType\Infrastructure\Controllers\RecordTypeController;
use App\Modules\ReferenceCode\Infrastructure\Controllers\ReferenceCodeController;
use App\Modules\SubCategory\Infrastructure\Controllers\SubCategoryController;
use App\Modules\TransportCompany\Infrastructure\Controllers\TransportCompanyController;
use App\Modules\Ubigeo\Departments\Infrastructure\Controllers\DepartmentController;
use App\Modules\Ubigeo\Provinces\Infrastructure\Controllers\ProvinceController;
use App\Modules\User\Infrastructure\Controllers\UserController;
use App\Modules\VisibleArticles\Infrastructure\Controllers\VisibleArticleController;
use Illuminate\Support\Facades\Route;
use App\Modules\Ubigeo\Districts\Infrastructure\Controllers\DistrictController;
use App\Modules\PaymentMethod\Infrastructure\Controllers\PaymentMethodController;
use App\Modules\DocumentType\Infrastructure\Controllers\DocumentTypeController;
use App\Modules\LoginAttempt\Infrastructure\Controllers\LoginAttemptController;
use App\Modules\Sale\Infrastructure\Controllers\SaleController;
use App\Modules\Collections\Infrastructure\Controllers\CollectionController;
use App\Modules\Serie\Infrastructure\Controllers\SerieController;
use App\Modules\UserAssignment\Infrastructure\Controllers\UserAssignmentController;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/roles', [RoleController::class, 'index']);
Route::get('/roles/{id}', [RoleController::class, 'show']);
Route::get('/permissions', [RoleController::class, 'indexPermissions']);
Route::post('/roles', [RoleController::class, 'store']);
Route::put('/roles/{id}', [RoleController::class, 'update']);

Route::get('/usernames', [UserController::class, 'findAllUserName']);

//visible Articulos
Route::get('/visibleArticle/{id}', [VisibleArticleController::class, 'show']);
Route::put('/visibleArticle/{id}', [VisibleArticleController::class, 'update']);
Route::get('/visibleArticlelist/{id}', [VisibleArticleController::class, 'visibleBranch']);
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
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users-name/{userName}', [UserController::class, 'FindByUserName']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::put('/users/status-login/{id}', [UserController::class, 'updateStLogin']);
Route::get('/users-vendedor', [UserController::class, 'findAllUsersByVendedor']);

// TIPOS DE DOCUMENTOS (DNI, RUC, ETC)
Route::get('driver-document-types', [CustomerDocumentTypeController::class, 'indexForDrivers']);

//reference code
Route::get('referenceCode', [ReferenceCodeController::class, 'index']);
Route::get('referenceCode/{id}', [ReferenceCodeController::class, 'show']);
Route::put('referenceCode/{id}', [ReferenceCodeController::class, 'update']);
Route::get('referenceCodeId/{id}', [ReferenceCodeController::class, 'indexid']);
Route::post('referenceCode-save/{id}', [ReferenceCodeController::class, 'store']);


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

//dispatchArticle
Route::get('dispatch-Article', [DispatchArticleController::class, 'index']);
Route::get('dispatch-Article/{id}', [DispatchArticleController::class, 'show']);

// Customers - Clientes
Route::get('customers', [CustomerController::class, 'index']);
Route::get('customers/unassigned', [CustomerController::class, 'findAllUnassigned']);
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
Route::get('currency-type',[CurrencyTypeController::class,'index']);
//articles
Route::get('articles',[ArticleController::class,'index']);
Route::post('articles-save',[ArticleController::class,'store']);
Route::get('articles/{id}',[ArticleController::class,'show']);
Route::post('articlesupdate/{id}',[ArticleController::class,'update']);

// Exchange Rates - Tipo de cambio
Route::get('exchange-rates', [ExchangeRateController::class, 'index']);
Route::get('exchange-rates/current', [ExchangeRateController::class, 'current']);
Route::get('exchange-rates/{id}', [ExchangeRateController::class, 'show']);
Route::put('exchange-rates/{id}', [ExchangeRateController::class, 'update']);

Route::get('/payment-methods', [PaymentMethodController::class, 'findAllPaymentMethods']);

// Emission Reasons - Motivos de emisión
Route::get('emission-reasons', [EmissionReasonController::class, 'index']);
Route::get('emission-reason/{id}', [EmissionReasonController::class, 'show']);

// Ingress Reasons - Motivos de ingreso
Route::get('ingress-reasons', [IngressReasonController::class, 'index']);

// Tipos de documentos
Route::get('document-types', [DocumentTypeController::class, 'index']);
Route::get('document-types/sales', [DocumentTypeController::class, 'indexSales']);

// Banks - Bancos
Route::get('banks', [BankController::class, 'index']);
Route::post('banks', [BankController::class, 'store']);
Route::get('banks/{id}', [BankController::class, 'show']);
Route::put('banks/{id}', [BankController::class, 'update']);

// Digital Wallets - Billeteras digitales
//Route::get('digital-wallets', [DigitalWalletController::class, 'index']);
Route::post('digital-wallets', [DigitalWalletController::class, 'store']);
Route::get('digital-wallets/{id}', [DigitalWalletController::class, 'show']);
Route::put('digital-wallets/{id}', [DigitalWalletController::class, 'update']);

// Customer portfolios - Cartera de clientes
Route::post('customer-portfolios', [CustomerPortfolioController::class, 'store']);
Route::put('customer-portfolios', [CustomerPortfolioController::class, 'updateAllCustomersByVendedor']);
Route::put('customer-portfolios/{id}', [CustomerPortfolioController::class, 'update']);

//dispatch Notes
Route::get('dispatchNote', [DispatchNotesController::class, 'index']);
Route::post('dispatchNote-save', [DispatchNotesController::class, 'store']);
Route::get('dispatchNote/{id}', [DispatchNotesController::class, 'show']);

// Logs de sesion
Route::get('logs-login', [LoginAttemptController::class, 'index']);

Route::middleware(['auth:api', 'auth.custom'])->group(function () {
    // Customer portfolios - Cartera de clientes
    Route::get('customer-portfolios', [CustomerPortfolioController::class, 'index']);

    // Digital Wallets - Billeteras digitales
    Route::get('digital-wallets', [DigitalWalletController::class, 'index']);

    // Crear cliente
    Route::post('customers', [CustomerController::class, 'store']);

    // Series
    Route::get('/serie-number', [SerieController::class, 'findByDocumentType']);

    // Ruta para traer las sucursales asignadas a un usuario
    Route::get('/branches-by-user', [UserAssignmentController::class, 'indexBranchesByUser']);

    // Ruta para ventas
    Route::get('/sales', [SaleController::class, 'index']);
    Route::get('/sales/by-document', [SaleController::class, 'showDocumentSale']);
    Route::get('/sales/{id}', [SaleController::class, 'show']);
    Route::post('/sales', [SaleController::class, 'store']);
    Route::put('/sales/{id}', [SaleController::class, 'update']);

    // Ruta para proformas
    Route::get('/sales-proformas', [SaleController::class, 'indexProformas']);

    // Ruta para cobranzas
    Route::get('/collections', [CollectionController::class, 'index']);
    Route::post('/collections', [CollectionController::class, 'store']);
    Route::get('/collections/{id}', [CollectionController::class, 'showBySaleId']);
    Route::put('/collections/{id}', [CollectionController::class, 'cancelCharge']);

    // Ruta para traer los logs transaccionales
    Route::get('/logs-transaction', [\App\Modules\TransactionLog\Infrastructure\Controllers\TransactionLogController::class, 'index']);
});

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);


});

Route::post('/refresh', [AuthController::class, 'refresh']);

// Rutas para el modulo de cierres mensuales
Route::get('/monthly-closures', [MonthlyClosureController::class, 'index']);
Route::post('/monthly-closures', [MonthlyClosureController::class, 'store']);
Route::get('/monthly-closures/{id}', [MonthlyClosureController::class, 'show']);
Route::put('/monthly-closures-sales/{id}', [MonthlyClosureController::class, 'updateStSales']);
