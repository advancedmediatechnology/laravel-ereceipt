<?php
namespace YourVendor\EReceipt\Facades;

use Illuminate\Support\Facades\Facade;

class EReceipt extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'ereceipt';
    }
}
