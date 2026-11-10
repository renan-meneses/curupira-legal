<?php

declare(strict_types=1);

namespace App\Domain\Finance;

enum PaymentMethod: string
{
    case Pix = 'pix';
    case BankTransfer = 'bank_transfer';
    case CreditCard = 'credit_card';
    case Cash = 'cash';
    case Other = 'other';
}
