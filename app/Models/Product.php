<?php

namespace App\Models;

use App\Services\CurrencyService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    /**
     * @return float
     */
    public function getPriceEuroAttribute(): float
    {
        return (new CurrencyService())->convert(amount: $this->price, currencyFrom: 'usd', currencyTo: 'usd');
    }

}
