<?php

use App\Http\Controllers\AlertController;
use App\Http\Controllers\ArchingCashController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\BillReportController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\BuyController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactReportController;
use App\Http\Controllers\CreditNoteController;
use App\Http\Controllers\ExtraController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\PurchaseReportController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SaleNoteController;
use App\Http\Controllers\SaleReportController;
use App\Http\Controllers\SerieController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/'                          , [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login'                    , [LoginController::class, 'login'])->name('login.login');
Route::get('/logout'                    , [LoginController::class, 'logout'])->name('login.logout');

# Extra
Route::get('/prices'                    , [ExtraController::class, 'prices'])->name('admin.prices')->middleware('can:admin.prices'); 
Route::get('/faq'                       , [ExtraController::class, 'faq'])->name('admin.faq')->middleware('can:admin.faq'); 
Route::post('/send-voucher'             , [ExtraController::class, 'send_voucher'])->name('admin.send_voucher'); 

// Business
Route::get('/business'                  , [BusinessController::class, 'index'])->middleware('can:admin.business')->name('admin.business');
Route::post('/load-ubigeo'              , [BusinessController::class, 'load_ubigeo'])->name('admin.load_ubigeo');
Route::post('/load-provinces'           , [BusinessController::class, 'load_provinces'])->name('admin.load_provinces');
Route::post('/load-districts'           , [BusinessController::class, 'load_districts'])->name('admin.load_districts');
Route::post('/save-info-business'       , [BusinessController::class, 'save_info_business'])->name('admin.save_info_business');
Route::post('/save-info-user'           , [BusinessController::class, 'save_info_user'])->name('admin.save_info_user');
Route::post('/gen-json'                 , [BusinessController::class, 'gen_json'])->name('admin.gen_json');
## Home 
Route::get('/home'                      , [HomeController::class, 'index'])->name('admin.home')->middleware('auth'); 
Route::get('/test-whatsapp'             , [HomeController::class, 'test']);
Route::post('/load-alerts'              , [HomeController::class, 'alerts'])->name('admin.load_alerts');
Route::post('/dash-bills'               , [HomeController::class, 'dash_bills'])->name('admin.dash_bills');
Route::post('/dash-profits'             , [HomeController::class, 'dash_profits'])->name('admin.dash_profits');
Route::post('/dash-clients'             , [HomeController::class, 'dash_clients'])->name('admin.dash_clients');
Route::post('/dash-incomes'             , [HomeController::class, 'dash_incomes'])->name('admin.dash_incomes');
## Alerts 
Route::get('/alerts-stock'              , [AlertController::class, 'stock'])->name('admin.alerts_stock')->middleware('can:admin.alerts_stock');
Route::post('/tbody-stocks'             , [AlertController::class, 'tbody_stocks'])->name('admin.load_tbody_stocks');
Route::get('/alerts-expiration'         , [AlertController::class, 'expiration'])->name('admin.alerts_expiration')->middleware('can:admin.alerts_expiration');
Route::post('/tbody-expirations'        , [AlertController::class, 'tbody_expirations'])->name('admin.load_tbody_expirations');
Route::get('/alerts-sale'               , [AlertController::class, 'sale'])->name('admin.alerts_sale')->middleware('can:admin.alerts_sale');
Route::post('/tbody-sales'              , [AlertController::class, 'tbody_sales'])->name('admin.load_tbody_sales');
## Serie
Route::get('/series'                    , [SerieController::class, 'index'])->name('admin.series')->middleware('can:admin.series');
Route::get('/get-series'                , [SerieController::class, 'get'])->name('admin.get_series');
Route::post('/save-serie'               , [SerieController::class, 'save'])->name('admin.save_serie');
Route::post('/detail-serie'             , [SerieController::class, 'detail'])->name('admin.detail_serie');
Route::post('/store-serie'              , [SerieController::class, 'store'])->name('admin.store_serie');
Route::post('/delete-serie'             , [SerieController::class, 'delete'])->name('admin.delete_serie');
## Cash
Route::get('/list-cashes'               , [CashController::class, 'index'])->name('admin.list_cashes')->middleware('can:admin.list_cashes');
Route::get('/get-cashes'                , [CashController::class, 'get'])->name('admin.get_cashes');
Route::post('/save-cash'                , [CashController::class, 'save'])->name('admin.save_cash');
Route::post('/detail-cash'              , [CashController::class, 'detail'])->name('admin.detail_cash');
Route::post('/store-cash'               , [CashController::class, 'store'])->name('admin.store_cash');
Route::post('/delete-cash'              , [CashController::class, 'delete'])->name('admin.delete_cash');
## Client
Route::get('/clients'                   , [ClientController::class, 'index'])->name('admin.clients')->middleware('can:admin.clients');
Route::get('/get-clients'               , [ClientController::class, 'get'])->name('admin.get_clients');
Route::post('/load-ubigeo-client'       , [ClientController::class, 'load_ubigeo'])->name('admin.load_ubigeo_client');
Route::post('/search-dni-ruc'           , [ClientController::class, 'search'])->name('admin.search_dni_ruc');
Route::post('/save-client'              , [ClientController::class, 'save'])->name('admin.save_client');
Route::post('/detail-client'            , [ClientController::class, 'detail'])->name('admin.detail_client');
Route::post('/store-client'             , [ClientController::class, 'store'])->name('admin.store_client');
Route::post('/delete-client'            , [ClientController::class, 'delete'])->name('admin.delete_client');
## Provider
Route::get('/providers'                 , [ProviderController::class, 'index'])->name('admin.providers')->middleware('can:admin.providers');
Route::get('/get-providers'             , [ProviderController::class, 'get'])->name('admin.get_providers');
Route::post('/save-provider'            , [ProviderController::class, 'save'])->name('admin.save_provider');
Route::post('/detail-provider'          , [ProviderController::class, 'detail'])->name('admin.detail_provider');
Route::post('/store-provider'           , [ProviderController::class, 'store'])->name('admin.store_provider');
Route::post('/delete-provider'          , [ProviderController::class, 'delete'])->name('admin.delete_provider');
## Roles
Route::get('/roles'                     , [RoleController::class, 'index'])->name('admin.roles')->middleware('can:admin.roles');
Route::get('/get-roles'                 , [RoleController::class, 'get'])->name('admin.get_roles');
Route::post('/save-role'                , [RoleController::class, 'save'])->name('admin.save_role');
Route::post('/detail-role'              , [RoleController::class, 'detail'])->name('admin.detail_role');
Route::post('/store-role'               , [RoleController::class, 'store'])->name('admin.store_role');
Route::post('/delete-role'              , [RoleController::class, 'delete'])->name('admin.delete_role');
## Product
Route::get('/products'                  , [ProductController::class, 'index'])->name('admin.products')->middleware('can:admin.products');
Route::get('/get-products'              , [ProductController::class, 'get'])->name('admin.get_products');
Route::post('/save-product'             , [ProductController::class, 'save'])->name('admin.save_product');
Route::post('/detail-product'           , [ProductController::class, 'detail'])->name('admin.detail_product');
Route::post('/store-product'            , [ProductController::class, 'store'])->name('admin.store_product');
Route::post('/delete-product'           , [ProductController::class, 'delete'])->name('admin.delete_product');
Route::post('/upload-excel'             , [ProductController::class, 'upload'])->name('admin.upload_excel');
Route::get('/download-excel'            , [ProductController::class, 'download'])->name('admin.download_excel');
Route::post('/view-stocks'              , [ProductController::class, 'view_stocks'])->name('admin.view_stocks');
## Bill
Route::get('/bills'                     , [BillController::class, 'index'])->name('admin.bills')->middleware('can:admin.bills');
Route::get('/get-bills'                 , [BillController::class, 'get'])->name('admin.get_bills');
Route::post('/save-bill'                , [BillController::class, 'save'])->name('admin.save_bill');
Route::post('/delete-bill'              , [BillController::class, 'delete'])->name('admin.delete_bill');
## Buy
Route::get('/buys'                      , [BuyController::class, 'index'])->name('admin.buys')->middleware('can:admin.buys');
Route::get('/get-buys'                  , [BuyController::class, 'get'])->name('admin.get_buys');
Route::get('/create-buy'                , [BuyController::class, 'create'])->name('admin.create_buy')->middleware('can:admin.create_buy');
Route::post('/load-serie-buy'           , [BuyController::class, 'load_serie'])->name('admin.load_serie_buy');
Route::post('/get-serie-buy'            , [BuyController::class, 'get_serie'])->name('admin.get_serie_buy');
Route::post('/get-price-product-buy'    , [BuyController::class, 'get_price_product'])->name('admin.get_price_product_buy');
Route::post('/add-product-buy'          , [BuyController::class, 'add_product'])->name('admin.add_product_buy');
Route::post('/load-cart-buys'           , [BuyController::class, 'load_cart'])->name('admin.load_cart_buys');
Route::post('/delete-product-buy'       , [BuyController::class, 'delete_product'])->name('admin.delete_product_buy');
Route::post('/store-product-buy'        , [BuyController::class, 'store_product'])->name('admin.store_product_buy');
Route::post('/get-providers-update'     , [BuyController::class, 'get_providers_update'])->name('admin.get_providers_update');
Route::post('/save-buy'                 , [BuyController::class, 'save'])->name('admin.save_buy');
Route::post('/get-products-update'      , [BuyController::class, 'get_products_update'])->name('admin.get_products_update');
Route::post('/print-buy'                , [BuyController::class, 'print_buy'])->name('admin.print_buy');
Route::get('/download-buy/{id}'         , [BuyController::class, 'download'])->name('admin.download_buy');
Route::post('/options-print'            , [BuyController::class, 'options'])->name('admin.options_print');
Route::post('/delete-buy'               , [BuyController::class, 'delete'])->name('admin.delete_buy');
Route::post('/send-buy'                 , [BuyController::class, 'send'])->name('admin.send_buy');
## Quote
Route::get('/quotes'                    , [QuoteController::class, 'index'])->name('admin.quotes')->middleware('can:admin.quotes'); 
Route::get('/get-quotes'                , [QuoteController::class, 'get'])->name('admin.get_quotes');
Route::get('/create-quote'              , [QuoteController::class, 'create'])->name('admin.create_quote')->middleware('auth');
Route::post('/load-cart-quotes'         , [QuoteController::class, 'load_cart'])->name('admin.load_cart_quotes');
Route::post('/searc-product-cart'       , [QuoteController::class, 'search_product_cart'])->name('admin.search_product_cart');
Route::post('/add-product-quote'        , [QuoteController::class, 'add_product'])->name('admin.add_product_quote');
Route::post('/store-product-quote'      , [QuoteController::class, 'store_product'])->name('admin.store_product_quote');
Route::post('/get-clients-quotes'       , [QuoteController::class, 'get_clients'])->name('admin.get_clients_update');
Route::post('/get-products-update-q'    , [QuoteController::class, 'get_products_update'])->name('admin.get_products_update_q');
Route::post('/get-serie-quote'          , [QuoteController::class, 'get_serie'])->name('admin.get_serie_quote');
Route::post('/store-product-quote-price', [QuoteController::class, 'store_product_quote_price'])->name('admin.store_product_quote_price');
Route::post('/delete-product-quote'     , [QuoteController::class, 'delete_product'])->name('admin.delete_product_quote');
Route::post('/get-price-product-quote'  , [QuoteController::class, 'get_price_product'])->name('admin.get_price_product_quote');
Route::post('/save-quote'               , [QuoteController::class, 'save'])->name('admin.save_quote');
Route::post('/print-quote'              , [QuoteController::class, 'print_quote'])->name('admin.print_quote');
Route::get('/quotes/download-quote/{id}', [QuoteController::class, 'download'])->name('admin.download_quote');
Route::post('/options-print-quote'      , [QuoteController::class, 'options'])->name('admin.options_print_quote');
Route::get('/test-quote'                , [QuoteController::class, 'test'])->name('admin.test_quote');
Route::post('/gen-quote-voucher'        , [QuoteController::class, 'gen_voucher'])->name('admin.gen_quote_voucher');
Route::get('/edit-quote-{id}'           , [QuoteController::class, 'edit'])->name('admin.edit_quote');
Route::post('/get-product-quote-update'  , [QuoteController::class, 'get_product_update'])->name('admin.get_product_quote_update');
Route::post('/store-product-quote-update'  , [QuoteController::class, 'store_product_update'])->name('admin.store_product_quote_update');
Route::post('/gen-quote-update'         , [QuoteController::class, 'gen_update'])->name('admin.gen_quote_update');
## Arching Cash
Route::get('/cashes'                    , [ArchingCashController::class, 'index'])->name('admin.cashes')->middleware('can:admin.cashes');
Route::get('/get-arching-cashes'        , [ArchingCashController::class, 'get'])->name('admin.get_arching_cashes');
Route::post('/save-arching-cash'        , [ArchingCashController::class, 'save'])->name('admin.save_arching_cash');
Route::post('/close-cash'               , [ArchingCashController::class, 'close'])->name('admin.close_cash');
Route::post('/get-detail-cash'          , [ArchingCashController::class, 'get_detail_cash'])->name('admin.get_detail_cash');
Route::post('/get-detail-cashes'        , [ArchingCashController::class, 'get_detail_cashes'])->name('admin.get_detail_cashes');
Route::post('/get-summary'              , [ArchingCashController::class, 'get_summary'])->name('admin.get_summary');
Route::get('/download-detail-cash/{id}' , [ArchingCashController::class, 'download'])->name('admin.download_detail_cash');
## Sale Note
Route::get('/get-sale-notes'            , [SaleNoteController::class, 'get'])->name('admin.get_sale_notes');
Route::get('/sale-notes'                , [SaleNoteController::class, 'index'])->name('admin.sale_notes')->middleware('can:admin.sale_notes'); 
Route::get('/create-sale-note'          , [SaleNoteController::class, 'create'])->name('admin.create_sale_note')->middleware('auth');
Route::get('/test-sale'                 , [SaleNoteController::class, 'test'])->name('admin.test_sale');
Route::post('/load-serie-sale-note'     , [SaleNoteController::class, 'load_serie'])->name('admin.load_serie_sale_note');
Route::post('/load-cart-sale-notes'     , [SaleNoteController::class, 'load_cart'])->name('admin.load_cart_sale_notes');
Route::post('/add-product-sale-note'    , [SaleNoteController::class, 'add_product'])->name('admin.add_product_sale_note');
Route::post('/delete-product-sale-note' , [SaleNoteController::class, 'delete_product'])->name('admin.delete_product_sale_note');
Route::post('/store-product-sale-note'  , [SaleNoteController::class, 'store_product'])->name('admin.store_product_sale_note');
Route::post('/save-sale-note'           , [SaleNoteController::class, 'save'])->name('admin.save_sale_note');
Route::get('/test-ticket'               , [SaleNoteController::class, 'test_ticket'])->name('admin.test_ticket');
Route::post('/print-sale-note'          , [SaleNoteController::class, 'print'])->name('admin.print_sale_note');
Route::get('/download-sale-note/{id}'   , [SaleNoteController::class, 'download'])->name('admin.download_sale_note');
Route::post('/anulled-sale-note'        , [SaleNoteController::class, 'anulled'])->name('admin.anulled_sale_note');
## POS 
Route::get('/pos'                       , [PosController::class, 'index'])->name('admin.pos')->middleware('can:admin.pos'); 
Route::post('/load-view-products'       , [PosController::class, 'view_products'])->name('admin.load_view_products');
Route::post('/search-view-product'      , [PosController::class, 'search_view_product'])->name('admin.search_view_product');
Route::post('/load-cart-pos'            , [PosController::class, 'load_cart'])->name('admin.load_cart_pos');
Route::post('/add-product-pos'          , [PosController::class, 'add_product'])->name('admin.add_product_pos');
Route::post('/delete-product-pos'       , [PosController::class, 'delete_product'])->name('admin.delete_product_pos');
Route::post('/store-product-pos'        , [PosController::class, 'store_product'])->name('admin.store_product_pos');
Route::post('/cancel-cart-pos'          , [PosController::class, 'cancel_cart'])->name('admin.cancel_cart_pos');
Route::post('/load-serie-pos'           , [PosController::class, 'load_serie'])->name('admin.load_serie_pos');
Route::post('/get-serie-pos'            , [PosController::class, 'get_serie'])->name('admin.get_serie_pos');
Route::post('/process-pay-pos'          , [PosController::class, 'process_pay'])->name('admin.process_pay_pos');
Route::post('/save-billing-pos'         , [PosController::class, 'save'])->name('admin.save_billing_pos');
Route::get('/test-billing'              , [PosController::class, 'test'])->name('admin.test_billing');
Route::post('/check-cash-active'        , [PosController::class, 'check_cash'])->name('admin.check_cash_active');
## Billing 
Route::get('/billings'                  , [BillingController::class, 'index'])->name('admin.billings')->middleware('can:admin.billings'); 
Route::get('/get-billings'              , [BillingController::class, 'get'])->name('admin.get_billings');
Route::post('/update-cdr-bf'            , [BillingController::class, 'update_cdr_bf'])->name('admin.update_cdr_bf');
Route::post('/send-bf-sunat'            , [BillingController::class, 'send'])->name('admin.send_bf');
Route::post('/print-billing'            , [BillingController::class, 'print'])->name('admin.print_billing');
Route::get('/download-billing/{id}'     , [BillingController::class, 'download'])->name('admin.download_billing');
Route::get('/test-billing'              , [BillingController::class, 'test'])->name('admin.test_billing');
## Reports 
Route::get('/inventories-items'         , [InventoryReportController::class, 'products'])->name('admin.inventory_products')->middleware('can:admin.inventory_products');
Route::post('/search-inventory-products', [InventoryReportController::class, 'search_products'])->name('admin.search_inventory_products');
Route::post('/export-inventory-products', [InventoryReportController::class, 'export_products'])->name('admin.export_inventory_products');
// Customers
Route::get('/contacts-customers'        , [ContactReportController::class, 'customers'])->name('admin.contact_customers')->middleware('can:admin.contact_customers');
Route::post('/search-inventory-customers'   , [ContactReportController::class, 'search_customers'])->name('admin.search_contacts_customers');
Route::post('/export-inventory-customers'   , [ContactReportController::class, 'export_customers'])->name('admin.export_contacts_customers');
// Providers
Route::get('/contacts-providers'         , [ContactReportController::class, 'providers'])->name('admin.contact_providers')->middleware('can:admin.contact_providers');
Route::post('/search-inventory-providers'    , [ContactReportController::class, 'search_providers'])->name('admin.search_contacts_providers');
Route::post('/export-inventory-providers'    , [ContactReportController::class, 'export_providers'])->name('admin.export_contacts_providers');
// Sales General
Route::get('/sales-general'          , [SaleReportController::class, 'sales_general'])->name('admin.sales_general')->middleware('can:admin.sales_general');
Route::post('/search-sales-general'  , [SaleReportController::class, 'search_sales_general'])->name('admin.search_sales_general');
Route::post('/export-sales-general'  , [SaleReportController::class, 'export_sales_general'])->name('admin.export_sales_general');
// Sales Seller
Route::get('/sales-seller'          , [SaleReportController::class, 'sales_seller'])->name('admin.sales_seller')->middleware('can:admin.sales_seller');
Route::post('/search-sales-seller'  , [SaleReportController::class, 'search_sales_seller'])->name('admin.search_sales_seller');
Route::post('/export-sales-seller'  , [SaleReportController::class, 'export_sales_seller'])->name('admin.export_sales_seller');
// Sales Products
Route::get('/sales-product'          , [SaleReportController::class, 'sales_product'])->name('admin.sales_product')->middleware('auth');
Route::post('/search-sales-product'  , [SaleReportController::class, 'search_sales_product'])->name('admin.search_sales_product');
Route::post('/export-sales-product'  , [SaleReportController::class, 'export_sales_product'])->name('admin.export_sales_product');
// Buys General
Route::get('/purchases-general', [PurchaseReportController::class, 'purchases_general'])->name('admin.purchases_general')->middleware('can:admin.purchases_general');
Route::post('/search-purchase-general', [PurchaseReportController::class, 'search_purchases_general'])->name('admin.search_purchases_general');
Route::post('/export-purchase-general', [PurchaseReportController::class, 'export_purchases_general'])->name('admin.export_purchases_general');
// Buys Provider
Route::get('/purchases-provider'           , [PurchaseReportController::class, 'purchases_provider'])->name('admin.purchases_provider')->middleware('can:admin.purchases_provider');
Route::post('/search-purchase-provider'    , [PurchaseReportController::class, 'search_purchases_provider'])->name('admin.search_purchases_provider');
Route::post('/export-purchase-provider'    , [PurchaseReportController::class, 'export_purchases_provider'])->name('admin.export_purchases_provider');
// Bill
Route::get('/purchases-expenses'            , [BillReportController::class, 'purchases'])->name('admin.purchases_expenses')->middleware('can:admin.purchases_expenses');
Route::post('/search-purchase-expenses'     , [BillReportController::class, 'search_expenses'])->name('admin.search_purchases_expenses');
Route::post('/export-purchase-expenses'     , [BillReportController::class, 'export_expenses'])->name('admin.export_purchases_expenses');
## Credit Note
Route::get('/credit-notes'                   , [CreditNoteController::class, 'index'])->name('admin.credit_notes')->middleware('can:admin.credit_notes'); 
Route::get('/create-nc-{id}'                  , [CreditNoteController::class, 'create'])->name('admin.create_nc')->middleware('auth');
Route::get('/get-credit-notes'               , [CreditNoteController::class, 'get'])->name('admin.get_credit_notes');
Route::post('/save-nc'                       , [CreditNoteController::class, 'save'])->name('admin.save_nc');
Route::post('/send-nc'                       , [CreditNoteController::class, 'send'])->name('admin.send_nc');
Route::post('/update-cdr-nc'                 , [CreditNoteController::class, 'update_cdr'])->name('admin.update_cdr_nc');
## User
Route::get('/users'                         , [UserController::class, 'index'])->name('admin.users')->middleware('can:admin.users');
Route::get('/get-users'                     , [UserController::class, 'get'])->name('admin.get_users');
Route::post('/save-user'                    , [UserController::class, 'save'])->name('admin.save_user');
Route::post('/detail-user'                  , [UserController::class, 'detail'])->name('admin.detail_user');
Route::post('/store-user'                   , [UserController::class, 'store'])->name('admin.store_user');
Route::post('/delete-user'                  , [UserController::class, 'delete'])->name('admin.delete_user');
Route::post('/view-role'                    , [UserController::class, 'view_role'])->name('admin.view_role')->middleware('auth');
Route::post('/update-role'                  , [UserController::class, 'update'])->name('admin.update_role');
# Room
Route::get('/rooms'                         , [RoomController::class, 'index'])->name('admin.rooms')->middleware('can:admin.rooms');
Route::get('/get-rooms'                     , [RoomController::class, 'get'])->name('admin.get_rooms');
Route::post('/save-room'                    , [RoomController::class, 'save'])->name('admin.save_room');
Route::post('/detail-room'                  , [RoomController::class, 'detail'])->name('admin.detail_room');
Route::post('/store-room'                   , [RoomController::class, 'store'])->name('admin.store_room');
Route::post('/delete-room'                  , [RoomController::class, 'delete'])->name('admin.delete_room');
# Table
Route::get('/tables'                        , [TableController::class, 'index'])->name('admin.tables')->middleware('can:admin.tables');
Route::get('/get-tables'                    , [TableController::class, 'get'])->name('admin.get_tables');
Route::post('/save-table'                   , [TableController::class, 'save'])->name('admin.save_table');
Route::post('/detail-table'                 , [TableController::class, 'detail'])->name('admin.detail_table');
Route::post('/store-table'                  , [TableController::class, 'store'])->name('admin.store_table');
Route::post('/delete-table'                 , [TableController::class, 'delete'])->name('admin.delete_table');
## Order
Route::get('/orders'                        , [OrderController::class, 'index'])->name('admin.orders')->middleware('can:admin.orders');
Route::post('load-tables-order'             , [OrderController::class, 'load_tables'])->name('admin.load_tables_order');
Route::get('/get-orders'                    , [OrderController::class, 'get'])->name('admin.get_orders');
Route::get('/create-order'                  , [OrderController::class, 'create'])->name('admin.create_order')->middleware('can:admin.create_order');
Route::get('/register-order-{id}'           , [OrderController::class, 'register'])->name('admin.register_order')->middleware('can:admin.register_order');
Route::post('load-cart-order'               , [OrderController::class, 'load_cart'])->name('admin.load_cart_order');
Route::post('add-product-order'             , [OrderController::class, 'add_product'])->name('admin.add_product_order');
Route::post('delete-product-order'          , [OrderController::class, 'delete_product'])->name('admin.delete_product_order');
Route::post('store-product-order'           , [OrderController::class, 'store_product'])->name('admin.store_product_order');
Route::post('cancel-cart-order'             , [OrderController::class, 'cancel_cart'])->name('admin.cancel_cart_order');
Route::post('gen-order'                     , [OrderController::class, 'gen_order'])->name('admin.gen_order');
Route::post('print-command'                 , [OrderController::class, 'print_command'])->name('admin.print_command');
Route::post('print-pre-account'             , [OrderController::class, 'print_pre_account'])->name('admin.print_pre_account');
Route::post('anulled-order'                 , [OrderController::class, 'anulled'])->name('admin.anulled_order');
Route::post('get-product-order-update'      , [OrderController::class, 'get_product_order'])->name('admin.get_product_order_update');
Route::post('store-product-order-update'    , [OrderController::class, 'store_product_order'])->name('admin.store_product_order_update');
Route::post('gen-order-update'              , [OrderController::class, 'gen_order_update'])->name('admin.gen_order_update');
Route::post('get-tables-availables'         , [OrderController::class, 'get_tables_availables'])->name('admin.get_tables_availables');
Route::post('change-table-order'            , [OrderController::class, 'change_table'])->name('admin.change_table_order');
Route::post('process-pay-order'             , [OrderController::class, 'process_pay'])->name('admin.process_pay_order');
Route::post('save-billing-order'            , [OrderController::class, 'save_billing'])->name('admin.save_billing_order');
Route::get('/test-comanda'                  , [OrderController::class, 'test_comanda'])->name('admin.test_comanda');