<?php


use App\Http\Controllers\MenuController;
use App\Modules\Articles\Infrastructure\Controllers\ArticleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleSunatController;
use App\Modules\Advance\Infrastructure\Controllers\AdvanceController;
use App\Modules\ArticleType\Infrastructure\Controllers\ControllerArticleTypeModel;
use App\Modules\Auth\Infrastructure\Controllers\AuthController;
use App\Modules\Bank\Infrastructure\Controllers\BankController;
use App\Modules\Branch\Infrastructure\Controllers\BranchController;
use App\Modules\Brand\Infrastructure\Controllers\BrandController;
use App\Modules\BuildPc\Infrastructure\Controllers\BuildPcController;
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
use App\Modules\EntryGuides\Infrastructure\Controllers\ControllerEntryGuide;
use App\Modules\ExchangeRate\Infrastructure\Controllers\ExchangeRateController;
use App\Modules\IngressReason\Infrastructure\Controllers\IngressReasonController;
use App\Modules\MeasurementUnit\Infrastructure\Controllers\MeasurementUnitController;
use App\Modules\MonthlyClosure\Infrastructure\Controllers\MonthlyClosureController;
use App\Modules\PaymentType\Infrastructure\Controllers\PaymentTypeController;
use App\Modules\PercentageIGV\Infrastructure\Controllers\PercentageIGVController;
use App\Modules\PettyCashMotive\Infrastructure\Controllers\PettyCashMotiveController;
use App\Modules\PettyCashReceipt\Infrastructure\Controllers\PettyCashReceiptController;
use App\Modules\PurchaseOrder\Infrastructure\Controllers\PurchaseOrderController;
use App\Modules\Purchases\Infrastructure\Controllers\PurchaseController;
use App\Modules\RecordType\Infrastructure\Controllers\RecordTypeController;
use App\Modules\ReferenceCode\Infrastructure\Controllers\ReferenceCodeController;
use App\Modules\SubCategory\Infrastructure\Controllers\SubCategoryController;
use App\Modules\TransportCompany\Infrastructure\Controllers\TransportCompanyController;
use App\Modules\Ubigeo\Departments\Infrastructure\Controllers\DepartmentController;
use App\Modules\Ubigeo\Provinces\Infrastructure\Controllers\ProvinceController;
use App\Modules\User\Infrastructure\Controllers\UserController;
use App\Modules\VisibleArticles\Infrastructure\Controllers\VisibleArticleController;
use App\Modules\Warranty\Infrastructure\Controller\WarrantyController;
use App\Modules\WarrantyStatus\Infrastructure\Controller\WarrantyStatusController;
use Illuminate\Support\Facades\Route;
use App\Modules\Ubigeo\Districts\Infrastructure\Controllers\DistrictController;
use App\Modules\PaymentMethod\Infrastructure\Controllers\PaymentMethodController;
use App\Modules\DocumentType\Infrastructure\Controllers\DocumentTypeController;
use App\Modules\LoginAttempt\Infrastructure\Controllers\LoginAttemptController;
use App\Modules\Sale\Infrastructure\Controllers\SaleController;
use App\Modules\Collections\Infrastructure\Controllers\CollectionController;
use App\Modules\Dashboard\Infrastructure\Controller\DashboardController;
use App\Modules\DetailPcCompatible\Infrastructure\Controllers\DetailPcCompatibleController;
use App\Modules\Detraction\Infrastructure\Controller\DetractionController;
use App\Modules\DispatchArticleSerial\Infrastructure\Controllers\DispatchArticleSerialController;
use App\Modules\DispatchNotes\Infrastructure\Controllers\TransferOrderController;
use App\Modules\EntryItemSerial\Infrastructure\Controllers\EntryItemSerialController;
use App\Modules\Kardex\Infrastructure\Controllers\KardexController;
use App\Modules\Serie\Infrastructure\Controllers\SerieController;
use App\Modules\UserAssignment\Infrastructure\Controllers\UserAssignmentController;
use App\Modules\TransactionLog\Infrastructure\Controllers\TransactionLogController;
use App\Modules\NoteReason\Infrastructure\Controllers\NoteReasonController;
use App\Modules\PaymentConcept\Infrastructure\Controller\PaymentConceptController;
use App\Modules\PaymentMethodsSunat\Infrastructure\Controllers\PaymentMethoddSunatController;
use App\Modules\ScVoucher\Infrastructure\Controllers\ScVoucherController;
use App\Modules\ScVoucherdet\Infrastructure\Controllers\ScVoucherdetController;
use App\Modules\Withholding\Infrastructure\Controller\WithholdingController;
use App\Modules\Statistics\Infrastructure\Controllers\StatisticsController;
use App\Modules\PaymentMethodsSunat\Infrastructure\Controllers\PaymentMethodSunatController;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/users-name/{userName}', [UserController::class, 'FindByUserName']);

Route::middleware(['auth:api', 'auth.custom'])->group(function () {

    //visible Articulos
    Route::get('/visibleArticle/{id}', [VisibleArticleController::class, 'show']);
    Route::put('/visibleArticle/{id}', [VisibleArticleController::class, 'update']);
    Route::get('/visibleArticlelist/{id}', [VisibleArticleController::class, 'visibleBranch']);

    //articles
    Route::get('articles', [ArticleController::class, 'index']);
    Route::post('articles-save', [ArticleController::class, 'store']);
    Route::get('articles/{id}', [ArticleController::class, 'show']);
    Route::post('articlesupdate/{id}', [ArticleController::class, 'update']);
    Route::get('article-excel', [ArticleController::class, 'export']);
    Route::post('articles-notas-debito', [ArticleController::class, 'storeNotesDebito']);
    Route::get('articles-notas-listar', [ArticleController::class, 'indexNotesDebito']);
    Route::put('articles-notas-editar/{id}', [ArticleController::class, 'updateNotesDebito']);
    Route::put('articles-status/{id}', [ArticleController::class, 'updateStatus']);
    Route::get('articles-notas/{id}', [ArticleController::class, 'showNotesDebito']);
    Route::get('articles-required-serial/{id}', [ArticleController::class, 'requiredSerial']);
    Route::get('/articles-is-combo', [ArticleController::class, 'getIsCombo']);
    Route::get('/articles-placa-madre', [ArticleController::class, 'findArticlesByPlacaMadre']);
    // Customer portfolios - Cartera de clientes
    Route::get('customer-portfolios', [CustomerPortfolioController::class, 'index']);
    Route::get('/customer-portfolios-user/{id}', [CustomerPortfolioController::class, 'showUserByCustomer']);

    // Crear cliente
    Route::post('customers', [CustomerController::class, 'store']);
    Route::post('customers-save-api', [CustomerController::class, 'storeCustomerBySunatApi']);
    Route::get('customers-company', [CustomerController::class, 'findCustomerCompany']);
    Route::get('customers-no-company', [CustomerController::class, 'findAllCustomersExceptionCompanies']);

    // Series
    Route::get('/serie-number', [SerieController::class, 'findByDocumentType']);

    // Ruta para traer las sucursales asignadas a un usuario
    Route::get('/branches-by-user', [UserAssignmentController::class, 'indexBranchesByUser']);

    // Ruta para ventas
    Route::get('/sales-proformas', [SaleController::class, 'indexProformas']);
    Route::get('/sales-by-customer', [SaleController::class, 'findAllPendingSalesByCustomerId']);
    Route::get('/sales/by-document', [SaleController::class, 'showDocumentSale']);
    Route::get('/sales/by-document-debit', [SaleController::class, 'findSaleByDocumentForDebitNote']);
    Route::get('/sales/get-updated-quantities', [SaleController::class, 'getUpdatedQuantities']);
    Route::get('/sales', [SaleController::class, 'index']);
    Route::get('/sales/{id}', [SaleController::class, 'show']);
    Route::get('/sales-credit-notes/{id}', [SaleController::class, 'showCreditNote']);
    Route::get('/credit-notes-customer', [SaleController::class, 'indexCreditNotesByCustomer']);
    Route::post('/sales', [SaleController::class, 'store']);
    Route::post('/sales-credit-notes', [SaleController::class, 'storeCreditNote']);
    Route::put('/sales/{id}', [SaleController::class, 'update']);
    Route::put('/sales-credit-notes/{id}', [SaleController::class, 'updateCreditNote']);
    Route::get('/sales/{id}/pdf', [SaleController::class, 'generatePdf']);
    Route::get('/documents-by-customer', [SaleController::class, 'findAllDocumentsByCustomerId']);
    Route::put('/sales-status/{id}', [SaleController::class, 'updateStatus']);
    // Ruta para cobranzas
    Route::get('/collections', [CollectionController::class, 'index']);
    Route::post('/collections', [CollectionController::class, 'store']);
    Route::post('/collections-credits', [CollectionController::class, 'storeCollectionCreditNote']);
    Route::get('/collections/{id}', [CollectionController::class, 'showBySaleId']);
    Route::put('/collections/{id}', [CollectionController::class, 'cancelCharge']);
    Route::post('/collections-bulk', [CollectionController::class, 'storeBulkCollection']);

    //dispatch Notes
    Route::get('dispatchNote', [DispatchNotesController::class, 'index']);
    Route::post('dispatchNote-save', [DispatchNotesController::class, 'store']);
    Route::get('dispatchNote/{id}', [DispatchNotesController::class, 'show']);
    Route::put('dispatchNote-update/{id}', [DispatchNotesController::class, 'update']);
    Route::get('dispatchNote-PDF/{id}', [DispatchNotesController::class, 'generate']);
    Route::get('dispatchNote-proveedor', [DispatchNotesController::class, 'traerProovedores']);
    Route::put('dispatchNote-status/{id}', [DispatchNotesController::class, 'updateStatus']);
    Route::get('dispatchNote-excel', [DispatchNotesController::class, 'excelDowload']);
    Route::post('transfer-orders', [TransferOrderController::class, 'store']);
    Route::get('transfer-orders', [TransferOrderController::class, 'index']);
    Route::get('transfer-orders/{id}', [TransferOrderController::class, 'show']);
    Route::put('transfer-orders/{id}', [TransferOrderController::class, 'update']);

    // Ruta para traer los logs transaccionales
    Route::get('/logs-transaction', [TransactionLogController::class, 'index']);

    // Ruta para empresa de transportes
    Route::get('/private-transport', [TransportCompanyController::class, 'findPrivateTransport']);
    Route::get('/public-transport', [TransportCompanyController::class, 'indexPublicTransport']);

    // Ruta para validar contraseña de item de usuario
    Route::get('/validate-password', [UserController::class, 'validatedPassword']);

    // Ruta para articulos para ventas
    Route::get('/articles-price-convertion', [ArticleController::class, 'indexArticlesForSales']);

    // Ruta para traer los motivos de notas de credito o debito
    Route::get('/note-reasons', [NoteReasonController::class, 'index']);

    Route::get('/article-types', [ControllerArticleTypeModel::class, 'index']);
    Route::get('/article-types/{id}', [ControllerArticleTypeModel::class, 'show']);


    //Entry Guide
    Route::get('/entry-guides', [ControllerEntryGuide::class, 'index']);
    Route::get('/entryp', [ControllerEntryGuide::class, 'indexC']);
    Route::get('/entry-guides/{id}', [ControllerEntryGuide::class, 'show']);
    Route::post('/entry-guides', [ControllerEntryGuide::class, 'store']);
    Route::put('/entry-guides/{id}', [ControllerEntryGuide::class, 'update']);
    Route::post('/purchases/consolidate-guides', [ControllerEntryGuide::class, 'validateSameCustomer']);
    Route::get('/entry-guide-pdf/{id}', [ControllerEntryGuide::class, 'downloadPdf']);
    Route::put('/entry-guides-status/{id}', [ControllerEntryGuide::class, 'updateStatus']);
    Route::post('/entry-guides-procedure-fifo', [ControllerEntryGuide::class, 'getProcedureFIFO']);
    Route::get('/entry-guides-excel', [ControllerEntryGuide::class, 'excelDowload']);
    //PettyCashReceipt
    Route::get('/pettyCashReceipt', [PettyCashReceiptController::class, 'index']);
    Route::post('/pettyCashReceipt', [PettyCashReceiptController::class, 'store']);
    Route::put('/pettyCashReceipt/{id}', [PettyCashReceiptController::class, 'update']);
    Route::get('/pettyCashReceipt/{id}', [PettyCashReceiptController::class, 'show']);
    Route::put('/pettyCashReceiptstatus/{id}', [PettyCashReceiptController::class, 'updateStatus']);
    Route::post('/pettyCashReceipt/select-procedure', [PettyCashReceiptController::class, 'selectProcedure']);
    Route::post('/pettyCashReceipt/export-excel', [PettyCashReceiptController::class, 'exportExcel']);
    Route::post('/pettyCashReceipt/export-excel-cobranza-detalle', [PettyCashReceiptController::class, 'exportExcelCobranzaDetalle']);
    Route::post('/pettyCashReceipt/listartCobranzaDetalle', [PettyCashReceiptController::class, 'listartCobranzaDetalle']);
    //PettyCashReceiptMotive
    Route::get('/pettyCashMotive', [PettyCashMotiveController::class, 'index']);
    Route::get('/pettyCashMotive-by-receipt-type-infinite/{id}', [PettyCashMotiveController::class, 'indexByReceiptTypeInfinite']);
    Route::post('/pettyCashMotive', [PettyCashMotiveController::class, 'store']);
    Route::put('/pettyCashMotive/{id}', [PettyCashMotiveController::class, 'update']);
    Route::get('/pettyCashMotive/{id}', [PettyCashMotiveController::class, 'show']);
    Route::put('/pettyCashMotive/update-status/{id}', [PettyCashMotiveController::class, 'updateStatus']);

    //detailPcCompatible
    Route::get('/detailPcCompatible', [DetailPcCompatibleController::class, 'index']);
    Route::post('/detailPcCompatible', [DetailPcCompatibleController::class, 'store']);
    Route::post('/detailPcCompatible/article/{articleId}', [DetailPcCompatibleController::class, 'storeByArticle']);
    Route::get('/detailPcCompatible/article/{articleId}', [DetailPcCompatibleController::class, 'showByArticle']);
    Route::put('/detailPcCompatible/{id}', [DetailPcCompatibleController::class, 'update']);
    Route::get('/detailPcCompatible/{id}', [DetailPcCompatibleController::class, 'show']);
    //paymentMethodSunat
    Route::get('/paymentMethodSunat', [PaymentMethoddSunatController::class, 'index']);
    Route::post('/paymentMethodSunat', [PaymentMethoddSunatController::class, 'store']);
    Route::put('/paymentMethodSunat/{id}', [PaymentMethoddSunatController::class, 'update']);
    Route::get('/paymentMethodSunat/{id}', [PaymentMethoddSunatController::class, 'show']);
    //kardex
    Route::get('/kardex', [KardexController::class, 'index']);
    Route::post('/kardex', [KardexController::class, 'store']);
    Route::put('/kardex/{id}', [KardexController::class, 'update']);
    Route::get('/kardex/{id}', [KardexController::class, 'show']);
    Route::post('/kardex/by-product', [KardexController::class, 'getKardexByProduct']);
    Route::post('/kardex/excel', [KardexController::class, 'generateExcel']);
    //SCvaucher
    Route::get('/sc-voucher', [ScVoucherController::class, 'index']);
    Route::post('/sc-voucher', [ScVoucherController::class, 'store']);
    Route::put('/sc-voucher-status/{id}', [ScVoucherController::class, 'updateStatus']);
    Route::get('/sc-voucher/{id}', [ScVoucherController::class, 'show']);
    Route::put('/sc-voucher/{id}', [ScVoucherController::class, 'update']);
    Route::get('/sc-voucher-pdf/{id}', [ScVoucherController::class, 'generate']);
    Route::get('/sc-voucher-det/{id}', [ScVoucherController::class, 'getdetVoucher']);
    //scvoucherdetalle 
    Route::get('/sc-voucherdetalle', [ScVoucherdetController::class, 'index']);
    Route::post('/sc-voucherdetalle', [ScVoucherdetController::class, 'store']);
    Route::get('/sc-voucherdetalle/{id}', [ScVoucherdetController::class, 'show']);
    Route::put('/sc-voucherdetalle/{id}', [ScVoucherdetController::class, 'update']);

    // build pc
    Route::get('/build-pc', [BuildPcController::class, 'index']);
    Route::post('/build-pc', [BuildPcController::class, 'store']);
    Route::get('/build-pc/{id}', [BuildPcController::class, 'show']);
    Route::put('/build-pc/{id}', [BuildPcController::class, 'update']);
    Route::put('/build-pc/status/{id}', [BuildPcController::class, 'updateStatus']);

    // Ruta para las ordenes de compra
    Route::get('/purchase-orders', [PurchaseOrderController::class, 'index']);
    Route::post('/purchase-orders', [PurchaseOrderController::class, 'store']);
    Route::get('/purchase-orders/{id}', [PurchaseOrderController::class, 'show']);
    Route::put('/purchase-orders/{id}', [PurchaseOrderController::class, 'update']);
    Route::get('/purchase-orders/{id}/pdf', [PurchaseOrderController::class, 'generatePdf']);
    Route::post('/purchase-orders-customer', [PurchaseOrderController::class, 'getBySupplier']);
    
    // Ruta para traer las series de un articulo
    Route::get('/entry-item-serial/{articleId}', [EntryItemSerialController::class, 'findSerialByArticleId']);
    Route::get('/serial/consulta', [EntryItemSerialController::class, 'findSerialInDatabase']);

    // Ruta para traer los movimientos de transferencia de un articulo
    Route::get('/dispatch-serial-movements/{branchId}', [DispatchArticleSerialController::class, 'findAllMovements']);

    // Ruta para actualizar orden de salida
    Route::put('/transfer-orders-status/{id}', [TransferOrderController::class, 'updateStatusTransferOrder']);

    //purchase 
    Route::get('/purchases', [PurchaseController::class, 'index']);
    Route::post('/purchases', [PurchaseController::class, 'store']);
    Route::get('/purchases/{id}', [PurchaseController::class, 'show']);
    Route::put('/purchases/{id}', [PurchaseController::class, 'update']);
    Route::get('/purchases-pdf/{id}', [PurchaseController::class, 'downloadPdf']); 
    Route::get('/purchase-excel', [PurchaseController::class, 'exportExcel']);
    Route::post('/purchase-reporte', [PurchaseController::class, 'reporteVentasCompras']);
    // Advances - Anticipos
    Route::get('/advances', [AdvanceController::class, 'index']);
    Route::get('/advances/{customerId}', [AdvanceController::class, 'showAdvancesByCustomer']);
    Route::post('/advances', [AdvanceController::class, 'store']);

    // Dashboard
    Route::get('/dashboard/countProductsSoldByCategory', [DashboardController::class, 'countProductsSoldByCategory']);
    Route::get('/dashboard/topTenSellingProducts', [DashboardController::class, 'topTenSellingProducts']);
    Route::get('/dashboard/getSalesPurchasesAndUtility', [DashboardController::class, 'getSalesPurchasesAndUtility']);
    Route::get('/dashboard/getTopTenCustomers', [DashboardController::class, 'getTopTenCustomers']);

    // Withholding
    Route::get('/withholdings/{date}', [WithholdingController::class, 'findByDate']);

    // Detractions
    Route::get('/detractions', [DetractionController::class, 'index']);

    // PaymentConcepts - Conceptos de pago
    Route::get('/payment-concepts', [PaymentConceptController::class, 'index']);
    Route::post('/payment-concepts', [PaymentConceptController::class, 'store']);
    Route::get('/payment-concepts/{id}', [PaymentConceptController::class, 'show']);
    Route::put('/payment-concepts/{id}', [PaymentConceptController::class, 'update']);
    Route::put('/payment-concepts-status/{id}', [PaymentConceptController::class, 'updateStatus']);

    // Statistics - Estadísticas
    Route::get('/statistics/customer-consumed-items-json', [StatisticsController::class, 'getCustomerConsumedItemsJson']);
    Route::get('/statistics/customer-consumed-items', [StatisticsController::class, 'getCustomerConsumedItems']);
    Route::get('/statistics/articles-sold', [StatisticsController::class, 'getArticlesSold']);
    Route::get('/statistics/article-id-sold/{id}', [StatisticsController::class, 'getArticleIdSold']);
    Route::get('/statistics/article-id-purchase/{id}', [StatisticsController::class, 'getArticleIdPurchase']);
    Route::get('/statistics/article-id-purchase/{id}/export', [StatisticsController::class, 'exportArticleIdPurchase']);
    Route::get('/statistics/lista-precios/export', [StatisticsController::class, 'getListaPrecios']);
    Route::post('/statistics/lista-precios', [StatisticsController::class, 'listarPrecios']);
    // Ruta para envío sunat de venta
    Route::post('/sale-sunat-send/{id}', [SaleSunatController::class, 'store']);
    Route::get('/roles', [RoleController::class, 'index']);
    Route::get('/roles-paginate-infinite', [RoleController::class, 'indexPaginateInfinite']);
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
    Route::put('/companies/{id}', [CompanyController::class, 'update']);


    Route::get('/menus/search', [MenuController::class, 'searchChildren']);
    Route::get('/menus', [MenuController::class, 'index']);

    // User routes devuelvelo como estaba protegido
    Route::get('/users', [UserController::class, 'findAllUsers']);
    Route::get('/users/{id}', [UserController::class, 'show']);

    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::put('/users/status-login/{id}', [UserController::class, 'updateStLogin']);
    Route::get('/users-vendedor', [UserController::class, 'findAllUsersByVendedor']);
    Route::put('/users-status/{id}', [UserController::class, 'updateStatus']);

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
    Route::get('brands-paginate-infinite', [BrandController::class, 'indexPaginateInfinite']);
    Route::post('brands', [BrandController::class, 'store']);
    Route::get('brands/{id}', [BrandController::class, 'show']);
    Route::put('brands/{id}', [BrandController::class, 'update']);
    Route::put('brands-status/{id}', [BrandController::class, 'updateStatus']);

    // Drivers - conductores
    Route::get('drivers', [DriverController::class, 'index']);
    Route::post('drivers', [DriverController::class, 'store']);
    Route::get('drivers/{id}', [DriverController::class, 'show']);
    Route::put('drivers/{id}', [DriverController::class, 'update']);
    Route::post('drivers-sunatApi', [DriverController::class, 'storeCustomerBySunatApi']);
    Route::put('drivers-status/{id}', [DriverController::class, 'updateStatus']);


    // Categories - categorias
    Route::get('categories-infinite', [CategoryController::class, 'indexPaginateInfinite']);
    Route::get('categories', [CategoryController::class, 'indexPaginate']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::put('categories-status/{id}', [CategoryController::class, 'updateStatus']);


    // SubCategories - subcategorias
    Route::get('sub-categories', [SubCategoryController::class, 'index']);
    Route::get('sub-categories/category/{id}', [SubCategoryController::class, 'findByCategoryId']);
    Route::post('sub-categories', [SubCategoryController::class, 'store']);
    Route::get('sub-categories/{id}', [SubCategoryController::class, 'show']);
    Route::put('sub-categories/{id}', [SubCategoryController::class, 'update']);
    Route::put('sub-categories-status/{id}', [SubCategoryController::class, 'updateStatus']);
    Route::get('sub-categories-infinite/{id}', [SubCategoryController::class, 'indexPaginateInfinite']);

    // TransportCompanies - Empresa de transportes
    Route::get('transport-companies', [TransportCompanyController::class, 'index']);
    Route::post('transport-companies', [TransportCompanyController::class, 'store']);
    Route::get('transport-companies/{id}', [TransportCompanyController::class, 'show']);
    Route::put('transport-companies/{id}', [TransportCompanyController::class, 'update']);
    Route::post('transport-companies-sunat', [TransportCompanyController::class, 'storeCustomerBySunatApi']);
    Route::put('transport-companies-status/{id}', [TransportCompanyController::class, 'updateStatus']);

    // PercentageIGV - Porcentaje de IGV
    Route::get('percentage-igv', [PercentageIGVController::class, 'index']);
    Route::get('percentage-igv-current', [PercentageIGVController::class, 'findPercentageCurrent']);
    Route::post('percentage-igv', [PercentageIGVController::class, 'store']);
    Route::get('percentage-igv/{id}', [PercentageIGVController::class, 'show']);
    Route::put('percentage-igv/{id}', [PercentageIGVController::class, 'update']);

    // MeasurementUnits - Unidades de medida
    Route::get('measurement-units', [MeasurementUnitController::class, 'index']);
    Route::get('measurement-units-infinite', [MeasurementUnitController::class, 'indexPaginateInfinite']);
    Route::post('measurement-units', [MeasurementUnitController::class, 'store']);
    Route::get('measurement-units/{id}', [MeasurementUnitController::class, 'show']);
    Route::put('measurement-units/{id}', [MeasurementUnitController::class, 'update']);
    Route::put('measurement-units-status/{id}', [MeasurementUnitController::class, 'updateStatus']);

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
    Route::put('customers-status/{id}', [CustomerController::class, 'updateStatus']);
    Route::get('customers-suppliers', [CustomerController::class, 'findAllSuppliers']);

    // Customer phones - Telefonos de clientes
    Route::get('customer-phones', [CustomerPhoneController::class, 'index']);

    // UBIGEO
    Route::get('departments', [DepartmentController::class, 'index']);
    Route::get('provinces/{id}', [ProvinceController::class, 'index']);
    Route::get('districts/{coddep}/{codpro}', [DistrictController::class, 'index']);

    //PaymentType
    Route::get('paymentType', [PaymentTypeController::class, 'index']);
    Route::get('paymentType/{id}', [PaymentTypeController::class, 'show']);

    //currencyType
    Route::get('currency-type', [CurrencyTypeController::class, 'index']);


    // Exchange Rates - Tipo de cambio
    Route::get('exchange-rates', [ExchangeRateController::class, 'index']);
    Route::get('exchange-rates/current', [ExchangeRateController::class, 'current']);
    Route::get('exchange-rates/{id}', [ExchangeRateController::class, 'show']);
    Route::put('exchange-rates/{id}', [ExchangeRateController::class, 'update']);
    Route::put('exchange-rates-almacen/{id}', [ExchangeRateController::class, 'updateAlmacen']);
    Route::put('exchange-rates-compras/{id}', [ExchangeRateController::class, 'updateCompras']);
    Route::put('exchange-rates-ventas/{id}', [ExchangeRateController::class, 'updateVentas']);
    Route::put('exchange-rates-cobranzas/{id}', [ExchangeRateController::class, 'updateCobranzas']);
    Route::put('exchange-rates-pagos/{id}', [ExchangeRateController::class, 'updatePagos']);

    Route::get('/payment-methods', [PaymentMethodController::class, 'findAllPaymentMethods']);

    // Emission Reasons - Motivos de emisión
    Route::get('emission-reasons', [EmissionReasonController::class, 'index']);
    Route::get('reason-transfer-orders', [EmissionReasonController::class, 'indexForTransferOrder']);
    Route::get('emission-reason/{id}', [EmissionReasonController::class, 'show']);

    // Ingress Reasons - Motivos de ingreso
    Route::get('ingress-reasons', [IngressReasonController::class, 'index']);

    // Tipos de documentos
    Route::get('document-types', [DocumentTypeController::class, 'index']);
    Route::get('document-types/sales', [DocumentTypeController::class, 'indexSales']);
    Route::get('document-types/invoices', [DocumentTypeController::class, 'indexInvoices']);
    Route::get('document-types/petty-cash', [DocumentTypeController::class, 'indexPettyCash']);
    Route::get('document-types/petty-cash-infinite', [DocumentTypeController::class, 'indexPettyCashInfinite']);
    Route::get('document-types/document-sales', [DocumentTypeController::class, 'indexDocumentSales']);
    Route::get('document-types/purchases', [DocumentTypeController::class, 'indexPurchases']);
    Route::get('document-types/entry-guides', [DocumentTypeController::class, 'indexEntryGuides']);
    Route::get('document-types/references-sales', [DocumentTypeController::class, 'indexReferencesSales']);

    // Banks - Bancos
    Route::get('banks', [BankController::class, 'index']);
    Route::post('banks', [BankController::class, 'store']);
    Route::get('banks/{id}', [BankController::class, 'show']);
    Route::put('banks/{id}', [BankController::class, 'update']);
    Route::put('banks-status/{id}', [BankController::class, 'updateStatus']);

    // Digital Wallets - Billeteras digitales
    Route::get('digital-wallets', [DigitalWalletController::class, 'index']);
    Route::post('digital-wallets', [DigitalWalletController::class, 'store']);
    Route::get('digital-wallets/{id}', [DigitalWalletController::class, 'show']);
    Route::put('digital-wallets/{id}', [DigitalWalletController::class, 'update']);
    Route::put('digital-wallets-status/{id}', [DigitalWalletController::class, 'updateStatus']);

    // Customer portfolios - Cartera de clientes
    Route::post('customer-portfolios', [CustomerPortfolioController::class, 'store']);
    Route::put('customer-portfolios', [CustomerPortfolioController::class, 'updateAllCustomersByVendedor']);
    Route::put('customer-portfolios/{id}', [CustomerPortfolioController::class, 'update']);

    // Consignaciones
    Route::get('consignations', [TransferOrderController::class, 'indexConsignations']);

    // Logs de sesion
    Route::get('logs-login', [LoginAttemptController::class, 'index']);

    // Warranty Status
    Route::get('warranty-statuses', [WarrantyStatusController::class, 'index']);

    // Warranties
    Route::get('warranties', [WarrantyController::class,'index']);
    Route::post('warranties', [WarrantyController::class, 'store']);
    Route::get('warranties/{id}', [WarrantyController::class, 'show']);
    Route::get('warranties/documents/serial', [WarrantyController::class, 'findDocumentsBySerial']);
    Route::get('warranties/pdf/{id}', [WarrantyController::class, 'generatePdf']);
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
