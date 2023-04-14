<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Validator;
use AmrShawky\LaravelCurrency\Facade\Currency as CurrencyConvertor;
use App\Models\Currency;
use DB;

class AjaxController extends Controller
{
    public function exchange_rate (Request $request) {
        
        $dataArr = $request->all();
        $CurrencyCode = $dataArr['CurrencyCode'];
        $Currencyamount = $dataArr['Currencyamount'];
        $toCurrencyArr = $dataArr['CurrencyArr'];

        $dbCurrencyAmount = DB::table('currency')->orWhere('code', '=', $CurrencyCode)->pluck('exchange_rate_to_USD');

        $finalcurrenciesArr = [];

        $dbCurrencyCode = [];
        $tempcurrenciesArr = [];
        $dbCurrencies = Currency::all()->toArray();
        if(count($dbCurrencies) > 0){
            foreach ($dbCurrencies as $key => $value) {
                $dbCurrencyCode[] = $value['code'];
            }
        }
        $resultArr = array_intersect($dbCurrencyCode, $toCurrencyArr);
        if(count($resultArr) > 0){
            if(isset($dbCurrencyAmount[0]) && $dbCurrencyAmount[0] > 0){
                $usdValue = $dbCurrencyAmount[0];
            }else{
                $usdValue = CurrencyConvertor::convert()->from($CurrencyCode)->to('USD')->amount($Currencyamount)->round(3)->get();
            }
            foreach ($resultArr as $key => $value) {
                if (($key = array_search($value, $toCurrencyArr)) !== false) {
                    unset($toCurrencyArr[$key]);
                }
                $converteddbcurrencyValue = 0;
                $dbCurrencyAmountt = DB::table('currency')->orWhere('code', '=', $value)->pluck('exchange_rate_to_USD');
                if(isset($dbCurrencyAmountt[0]) && $dbCurrencyAmountt[0] > 0){
                    $converteddbcurrencyValue = $usdValue / $dbCurrencyAmountt[0];
                }
                $tempcurrenciesArr[$value] = number_format($converteddbcurrencyValue, 3);
            }
        }

        
        if(isset($dbCurrencyAmount[0]) && $dbCurrencyAmount[0] > 0){
            $Amount = $Currencyamount * $dbCurrencyAmount[0];
            $currenciesArr = CurrencyConvertor::rates()
            ->latest()
            ->symbols($toCurrencyArr)
            ->base('USD')
            ->amount($Amount)
            ->round(3)
            ->get();

            $finalcurrenciesArr = $currenciesArr;
        }else{
            $currenciesArr = CurrencyConvertor::rates()
            ->latest()
            ->symbols($toCurrencyArr)
            ->base($CurrencyCode)
            ->amount($Currencyamount)
            ->round(3)
            ->get();
        }
        $finalcurrenciesArr = array_merge($currenciesArr, $tempcurrenciesArr);
        return $finalcurrenciesArr;
    }
    public function update_usd_value (Request $request) {
        $dataArr                = $request->all();
        $predefined_usd_value   = $dataArr['USDValue'];
        $update                 = DB::table('default_usd_value')->update(['predefined_usd_value' => $predefined_usd_value]);

        $currency = Currency::all()->toArray();
        for ($i=0; $i < count($currency); $i++) { 
            $exchange_rate  = $currency[$i]['exchange_rate'];
            $id             = $currency[$i]['id'];
            if($exchange_rate >= 0.01){
                $temp_currency_val = 1 / $exchange_rate;
                $exchange_rate_to_USD = $temp_currency_val * $predefined_usd_value;
                $update_currency       = DB::table('currency')->where("id", $id)->update(['exchange_rate_to_USD' => $exchange_rate_to_USD]);
            }
        }

        $flag = 0;
        if($update !== false){
            $flag = 1;
        }
        return $flag;
    }
}
