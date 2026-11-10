<?php

use App\Http\Controllers\Api\V1\CompanyController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\ContractController;
use App\Http\Controllers\Api\V1\TimeEntryController;
use App\Http\Controllers\Api\V1\BillingController;
use App\Http\Controllers\Api\V1\FinanceController;
use App\Http\Controllers\Api\V1\OrganizationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->middleware(['auth:sanctum', 'organization.context'])->group(function (): void {
    Route::get('company', [CompanyController::class, 'index']);
    Route::post('company', [CompanyController::class, 'store']);
    Route::get('customers', [CustomerController::class, 'index']);
    Route::post('customers', [CustomerController::class, 'store']);
    Route::get('contracts', [ContractController::class, 'index']);
    Route::post('contracts', [ContractController::class, 'store']);
    Route::patch('contracts/{contract}/status', [ContractController::class, 'transition']);
    Route::get('time-entries', [TimeEntryController::class, 'index']);
    Route::post('time-entries', [TimeEntryController::class, 'store']);
    Route::patch('time-entries/{timeEntry}/review', [TimeEntryController::class, 'review']);
    Route::post('billing/hourly', [BillingController::class, 'generate']);
    Route::post('billing/periods/{billingPeriod}/finalize', [BillingController::class, 'finalize']);
    Route::get('receivables', [FinanceController::class, 'receivables']);
    Route::post('receivables', [FinanceController::class, 'createReceivable']);
    Route::post('receivables/{receivable}/payments', [FinanceController::class, 'registerPayment']);
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function (): void {
    Route::get('organizations', [OrganizationController::class, 'index']);
    Route::post('organizations', [OrganizationController::class, 'store']);
    Route::post('organizations/{organization}/switch', [OrganizationController::class, 'switch']);
});
