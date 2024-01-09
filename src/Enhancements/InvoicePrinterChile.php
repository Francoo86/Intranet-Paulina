<?php

namespace App\Enhancements;

use Konekt\PdfInvoice\InvoicePrinter;
use Override;

class ChileanInvoicePrinter extends InvoicePrinter{

    public function __construct($size = self::INVOICE_SIZE_A4, $currency = '$', $language = 'es')
    {
        parent::__construct($size, $currency, $language);
    }

    #[Override]
    public function price($price)
    {
        $decimalPoint = $this->referenceformat['decimals_sep'];
        $thousandSeparator = $this->referenceformat['thousands_sep'];
        $alignment = $this->referenceformat['alignment'] ?? self::NUMBER_ALIGNMENT_LEFT;
        $spaceBetweenCurrencyAndAmount = isset($this->referenceformat['space']) ? (bool) $this->referenceformat['space'] : true;
        $space = $spaceBetweenCurrencyAndAmount ? ' ' : '';
        $negativeParenthesis = isset($this->referenceformat['negativeParenthesis']) ? (bool) $this->referenceformat['negativeParenthesis'] : false;
    
        $number = number_format($price, 2, $decimalPoint, $thousandSeparator);
    
        // Remove decimal part if it's .00
        $number = rtrim($number, '0');
        $number = rtrim($number, $decimalPoint);
    
        if ($negativeParenthesis && $price < 0) {
            $number = substr($number, 1);
            if ($alignment === self::NUMBER_ALIGNMENT_RIGHT) {
                return '(' . $number . $space . $this->currency . ')';
            } else {
                return '(' . $this->currency . $space . $number . ')';
            }
        } else {
            if ($alignment === self::NUMBER_ALIGNMENT_RIGHT) {
                return $number . $space . $this->currency;
            } else {
                return $this->currency . $space . $number;
            }
        }
    }
    
}



?>