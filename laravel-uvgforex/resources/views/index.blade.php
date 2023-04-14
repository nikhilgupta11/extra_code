<?php
    use PragmaRX\Countries\Package\Countries;
    use App\Models\Currency;
    // $currency_from_db = DB::table('currency')->orWhere('status', '=', 1)->pluck('code', 'name')->toArray();
    $currency_from_db = Currency::all()->Where('status', '=', 1)->toArray();
    $countries = new Countries();
    $worldwide_currencies = $countries->all()->pluck('currencies', 'name.common')->toArray();
    $countries_name_flags = $countries->all()->pluck('flag.emoji', 'name.common')->toArray();
    use Carbon\Carbon;
    $timezone = env('APP_TIMEZONE');
    $date = date('d/m/Y h:s:i');
    $currentDate = Carbon::now()->format('d/m/Y H:i');
    $currencies = [];
    foreach ($countries_name_flags as $key => $value) {
        if(isset($worldwide_currencies[$key][0]) && strlen($worldwide_currencies[$key][0]) == 3){
            if($worldwide_currencies[$key][0] == 'USD'){
                if($key == 'United States'){
                    $currencies['USD'] = $worldwide_currencies[$key][0].' - '.$key;
                }
            } 
            else if($worldwide_currencies[$key][0] == 'GBP'){
                if($key == 'United Kingdom'){
                    $currencies['GBP'] = $worldwide_currencies[$key][0].' - '.$key;
                }
            }
            else if($worldwide_currencies[$key][0] == 'ILS'){
                if($key == 'Israel'){
                    $currencies['ILS'] = $worldwide_currencies[$key][0].' - '.$key;
                }
            }
            else if($worldwide_currencies[$key][0] == 'AUD'){
                if($key == 'Australia'){
                    $currencies['AUD'] = $worldwide_currencies[$key][0].' - '.$key;
                }
            }
            else if($worldwide_currencies[$key][0] == 'CHF'){
                if($key == 'Switzerland'){
                    $currencies['CHF'] = $worldwide_currencies[$key][0].' - '.$key;
                }
            }
            else if($worldwide_currencies[$key][0] == 'NZD'){
                if($key == 'New Zealand'){
                    $currencies['NZD'] = $worldwide_currencies[$key][0].' - '.$key;
                }
            }
            else if($worldwide_currencies[$key][0] == 'DKK'){
                if($key == 'Denmark'){
                    $currencies['DKK'] = $worldwide_currencies[$key][0].' - '.$key;
                }
            }
            else if($worldwide_currencies[$key][0] == 'XPF'){
                if($key == 'French Polynesia'){
                    $currencies['XPF'] = $worldwide_currencies[$key][0].' - '.$key;
                }
            }
            else if($worldwide_currencies[$key][0] == 'XAF'){
                if($key == 'Central African Republic'){
                    $currencies['XAF'] = $worldwide_currencies[$key][0].' - '.$key;
                }
            }
            else if($worldwide_currencies[$key][0] == 'XCD'){
                $currencies['XCD'] = 'XCD - East Carribbean Dollar';
            }
            else if($worldwide_currencies[$key][0] == 'XOF'){
                $currencies['XOF'] = 'XOF - CFA Franc';
            }
            else if($worldwide_currencies[$key][0] == 'ANG'){
                $currencies[''] = 'ANG - Dutch Guilder';
            }
            else if($worldwide_currencies[$key][0] == 'EUR'){
                if($key == 'Kosovo'){
                    $currencies['EUR'] = $worldwide_currencies[$key][0].' - Euro';
                }
            }
            else{
                $currencies[$worldwide_currencies[$key][0]] = $worldwide_currencies[$key][0].' - '.$key;
            }
        }
    }
    foreach ($currency_from_db as $key => $value) {
        if(isset($value['code']) && strlen($value['code']) == 3){
            $currencies[$value['code']] = $value['code'].' - '.$value['name'];
            // $currencies['db|'.$value['code']] = $value['code'].' - '.$value['name'];
        }
    }
    //echo "<pre>";print_r($currency_from_db);exit;
?>
<!doctype html>
<html lang="en">
    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <title>UVGForex</title>
        <!-- Favicons -->
        <link rel="icon" type="image/x-icon" href="/assets/Favicon2.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" />
        <link rel="stylesheet" href="{{ ('css/index.css')}}">
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-52FMW9M');</script>
            <!-- End Google Tag Manager -->
    </head>
    <body>
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-52FMW9M"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
        <header class="header-wrapper">
            <div class="header-section container pb-5">
                <div class="row header-section-row  align-items-center pt-3">
                    <div class="brand-logo col-md-5">
                        <a href="/" style="cursor:pointer">
                            <img src="{{asset('/assets/RA-logo.png')}}" alt="Royal Assebly Logo" class="img-fluid" style="cursor:pointer"/>
                        </a>
                    </div>
                    <div class="brand-title text-end col-md-7">
                        <h5 class="header-info">Know your currency flawlessly!</h5>
                    </div>
                </div>
                <div class="header-content text-center pt-4 pb-md-5">
                    <h1>Select From Currency to Convert Into Multiple Currencies</h1>
                    <h4 class="">UVG Currency Converter</h4>
                </div>
            </div>
        </header>
        <main>
            <div class="loading-img-wrapper" id="loading" style="display:none">
                <img src="/assets/loading.gif" style="width:80px; height:80px;">
            </div>
            <section class="main-content-wrapper">
                <div class="container">
                    <div class="col-md-12 p0">
                        <div class="row content-row">
                            <div class="content-heading col-lg-7 col-md-6">
                                <h3>Convert And Check Out Currencies Here!</h3>
                            </div>
                            <div class="content-info d-flex justify-content-md-end col-lg-5 col-md-6">
                                <p class="me-2"><span>Region: </span>{{ $timezone }}</p>
                                <p><span id="changeDate">{{$date}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-9"></div>
                            <div class="col-md-3 add-currency-wrap pe-2 my-1">
                                <button class="d-flex justify-content-center align-items-center mb-0" style="cursor:pointer" id="add_more_currency" ><img src="{{asset('/assets/add_currency.svg')}}" class="img-fluid me-2" />Add Currency</button>
                            </div>
                        </div> 
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>From</th>
                                        <th></th>
                                        <th class="d-flex align-items-center">To 
                                            <span class="convert-btn ms-2">
                                                <img src="{{asset('/assets/warning.svg')}}" alt="Warning" class="img-fluid me-1" />(To perform the inverse, click on the "To" currency icon)
                                            </span>
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="add_more_rows">
                                    <tr id="rows0">
                                        <td class="from-section pb-md-4" id="from-section-select0">
                                            <div class="card" id="cardClass0">
                                                <div class="card-content  p-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-2" id="image0"><img src="{{asset('/assets/flags/NoImage.png')}}" alt="flag" id="img0"/></div> 
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <p class="currecy-select d-flex justify-content-center align-items-center  mb-0">
                                                                <select name="currency_code" id="currency_code0" onchange="changeFlag(this.value, 0)">
                                                                    <option value=""> Select Currency</option>
                                                                    @foreach ($currencies as $key => $value)
                                                                        <option value="{{ $key }}">{{ $value }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </p>
                                                        </div>
                                                        </div>
                                                    </div>
                                                <div class="card amount-section text-start p-2">
                                                        <input type="text" name="currency_amount" id="currency_amount0" class="amount form-control" placeholder="Enter Amount Here" onkeyup="getAmountOnKeyUp(this.value, 0)" onchange="getAmountOnChange(this.value, 0)"/> 
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="reverse-btn-wrap my-4">
                                                <span class="reverse-btn" style="cursor:pointer">
                                                    <button class="btn reverseBtn text-white" onclick="convertToCurrencies(0)">Convert</button>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="to-section pb-md-4">
                                            <div class="card py-3 px-2">
                                                <div class="to-content">
                                                    <div>
                                                        <button type="button" class="d-flex justify-content-center align-items-center mx-auto mb-1" id="usd_currency_val0" style="cursor:pointer" value="USD" onclick="exchangeCurrency('usd_currency_val0', 0, 'usd_img_id0', 'usd_img_span0')">
                                                            <img src="{{asset('/assets/flags/USD.png')}}" id="usd_img_id0" alt="Flag" class="img-fluid me-1"/> <span id="usd_img_span0">USD</span>
                                                        </button>
                                                        <span id="usd_amount_id0">0.00</span>
                                                    </div>
                                                    <div>
                                                        <button type="button" class="d-flex justify-content-center align-items-center mx-auto mb-1" id="aed_currency_val0" style="cursor:pointer" value="AED" onclick="exchangeCurrency('aed_currency_val0', 0, 'aed_img_id0', 'aed_img_span0')">
                                                            <img src="{{asset('/assets/flags/AED.png')}}" id="aed_img_id0" alt="Flag" class="img-fluid me-1"/><span id="aed_img_span0">AED</span>
                                                        </button>
                                                        <span id="aed_amount_id0">0.00</span>
                                                    </div>
                                                    <div>
                                                        <button type="button" class="d-flex justify-content-center align-items-center mx-auto  mb-1" id="eur_currency_val0" style="cursor:pointer" value="EUR" onclick="exchangeCurrency('eur_currency_val0', 0, 'eur_img_id0', 'eur_img_span0')">
                                                            <img src="{{asset('/assets/flags/EUR.png')}}" id="eur_img_id0" alt="Flag" class="img-fluid me-1"/><span id="eur_img_span0">EUR</span>
                                                        </button>
                                                        <span id="eur_amount_id0">0.00</span>
                                                    </div>
                                                    <div>
                                                        <button type="button" class="d-flex justify-content-center align-items-center mx-auto  mb-1" id="sar_currency_val0" style="cursor:pointer" value="SAR" onclick="exchangeCurrency('sar_currency_val0', 0, 'sar_img_id0', 'sar_img_span0')">
                                                            <img src="{{asset('/assets/flags/SAR.png')}}" id="sar_img_id0" alt="Flag" class="img-fluid me-1"/><span id="sar_img_span0">SAR</span>
                                                        </button>
                                                        <span id="sar_amount_id0">0.00</span>
                                                    </div>
                                                    <div>
                                                        <button type="button" class="d-flex justify-content-center align-items-center mx-auto  mb-1" id="gbp_currency_val0" style="cursor:pointer" value="GBP" onclick="exchangeCurrency('gbp_currency_val0', 0, 'gbp_img_id0', 'gbp_img_span0')">
                                                            <img src="{{asset('/assets/flags/GBP.png')}}" id="gbp_img_id0" alt="Flag" class="img-fluid me-1"/><span id="gbp_img_span0">GBP</span>
                                                        </button>
                                                        <span id="gbp_amount_id0">0.00</span>
                                                    </div>
                                                    <div>
                                                        <button type="button" class="d-flex justify-content-center align-items-center mx-auto mb-1" id="qar_currency_val0" style="cursor:pointer" value="QAR" onclick="exchangeCurrency('qar_currency_val0', 0, 'qar_img_id0', 'qar_img_span0')">
                                                            <img src="{{asset('/assets/flags/QAR.png')}}" id="qar_img_id0" alt="Flag" class="img-fluid me-1"/><span id="qar_img_span0">QAR</span>
                                                        </button>
                                                        <span id="qar_amount_id0">0.00</span>
                                                    </div>
                                                </div>  
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <section class="warning-info">
                <div class="warning-alert-wrap d-flex  align-items-md-center my-3 ">
                    <p><img src="{{asset('/assets/warning.svg')}}" alt="Warning" class="img-fluid me-1" />The System Has Its Own World Reserve Currency, <span style="margin-left:3px;font-weight:600"> UVG</span>.</p>
                </div>
            </section>
        </main>
        <footer class="footer-wrapper">
            <div class="footer-section container">
                <div class="row pt-md-4">
                    <div class="col-md-4">
                        <ul class="footer-content d-flex  flex-column  ps-0">
                        <li><a class="d-flex align-items-center" href="mailto:contact@uvgforex.com" target="_black"><img src="{{asset('/assets/email.svg')}}" alt="Email" class="img-fluid"/>
                            <p>contact@uvgforex.com</p></a></li>
                        <li><a class="d-flex align-items-center mt-2" href="tel:+675 9730021"><img src="{{asset('/assets/contact.svg')}}" alt="Contact-us" class="img-fluid"/> +675 9730021</a></li>

                        </ul>
                    </div>
                    <div class="col-md-4">
                        <ul class="footer-links ps-0 d-flex justify-content-md-center ">
                            <li><a href="https://www.facebook.com/uvgforex/" target="_blank "><img src="{{asset('/assets/facebook.svg')}}" alt="Facebook" class="img-fluid me-2"/></a></li>
                            <li><a href="https://twitter.com/uvgforex" target="_blank "><img src="{{asset('/assets/twitter.svg')}}" alt="Twitter" class="img-fluid me-2"/></a></li>        
                            <li><a href="https://www.linkedin.com/company/uvg-forex/" target="_blank "><img src="{{asset('/assets/linkedin.svg')}}" alt="LinkedIn" class="img-fluid me-2"/></a></li>
                            <li><a href="https://www.instagram.com/uvgforex/" target="_blank "><img src="{{asset('/assets/instagram.svg')}}" alt="Instagram" class="img-fluid"/></a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <ul class="footer-content footer-address-info d-flex float-end justify-content-md-end mb-0">
                        <li><a class="address-link d-flex " href="#"><img src="{{asset('/assets/location.svg')}}" alt="Address Location" class="img-fluid"/>
                            <p class="mb-0">Crowns Admin Block #1, Kings Square1, Kutana Pintai Plateau, Tonnu City, ICD, Twin Kingdom</p></a></li>
                        </ul>
                    </div>
                </div>
                <div class="text-center mt-4 pb-md-3">
                    <p class="footer text-white"><span> © 2023 UVGForex </span><span>Made with<img src="{{asset('/assets/footer_heart.gif')}}" alt="heart" width="22">by<a href="https://www.addwebsolution.com" class="text-white" target="_blank"> AddWeb Solution</a></span></p>
                </div>
            </div>
        </footer>
        <script src="js/script.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    </body>
</html>
<link href="{{ asset('packages/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('packages/select2/dist/js/select2.min.js') }}"></script>
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
<link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet"/>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    $(document).ready(function () {
        $('#currency_code0').on("select2:open", function(e) {
            $('.table-responsive').addClass('active-dropdown');
            $('#rows0').addClass('active-row-dropdown');
        });
        $('#currency_code0').on("select2:close", function(e) {
            $('.table-responsive').removeClass('active-dropdown');
            $('#rows0').removeClass('active-row-dropdown');
        });
        $('#currency_code0').select2({
            dropdownParent: $('#from-section-select0')
        });
        $('#currency_amount0').attr('maxlength', 7);
        $('#currency_amount0').on("cut paste",function(e) {
            e.preventDefault();
        });
    });
    function doDate()
    {
        var str = "";
        var days = new Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
        var months = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        // var now = new Date();
        var now = new Date().toLocaleString('en-GB', { timeZone: '{{$timezone}}' })
        // str +=  now.getDate()+'/'+now.getMonth()+'/'+now.getFullYear() +' '+ now.getHours() +":" + now.getMinutes();
        document.getElementById("changeDate").innerHTML = now;
    }
    setInterval(doDate, 1000);
    let toCurrencyArr = [];
    toCurrencyArr[0] = ['AED', 'USD', 'EUR', 'SAR', 'GBP', 'QAR'];
    let rowCount = 0;
    $('#add_more_currency').on('click', function(){
        rowCount++;
        let htmlData = addMoreRows(rowCount);
        $('#add_more_rows').prepend(htmlData);
        $('#currency_code'+rowCount).select2({
            dropdownParent: $('#from-section-select'+rowCount)
        });
        $('#currency_amount'+rowCount).attr('maxlength', 7);
        $('#currency_amount'+rowCount).on("cut paste",function(e) {
            e.preventDefault();
        });
        toCurrencyArr[rowCount] = ['AED', 'USD', 'EUR', 'SAR', 'GBP', 'QAR'];
    });
    function changeFlag(value, index){
        $('#theImg'+index).remove();
        $('#img'+index).remove();
        var flagName;
        flagName = $('#currency_code'+index).val()+'.png';
        if(urlExists("/assets/flags/"+flagName) == 200) {
            $('#image'+index).prepend('<img id="theImg'+index+'" alt="flag" src="{{asset("/assets/flags")}}'+'/'+flagName+'" />');
        } else {
            flagName = 'NoImage.png';
            $('#image'+index).prepend('<img id="theImg'+index+'" alt="flag" src="{{asset("/assets/flags")}}'+'/'+flagName+'" />');
        }
    }
    function getAmountOnKeyUp(value, index){
        var rgx = /^[0-9]*\.?[0-9]*$/;
        if(!value.match(rgx)){
            $('#currency_amount'+index).val('');
            return false;
        }
    }
    function getAmountOnChange(value, index){
        if($('#currency_amount'+index).val() <= 0){
            $('#currency_amount'+index).val('');
        }else{
            var val =$('#currency_amount'+index).val();
            const dec = val.split('.');
            if(dec[1]){
              const len = dec && dec.length > 2 ? 3 : dec.length;
            $('#currency_amount'+index).val(Number(val).toFixed(len));
            }
            $('#currency_amount'+index).val(Number(val));
        }
    }
    function convertToCurrencies(index){
        if($('#currency_code'+index).val() == ''){
            toastr.clear();
            toastr.error("Please select currency.");
            $('#cardClass'+index).addClass('border-danger');
            $('#currency_code'+index).focus();
            return false;
        }
        if($('#currency_amount'+index).val() == ''){
            toastr.clear();
            toastr.error("Please enter an amount.");
            $('#cardClass'+index).addClass('border-danger');
            $('#currency_amount'+index).focus();
            return false;
        }
        if($('#currency_amount'+index).val() <= 0){
            toastr.clear();
            toastr.error("Please enter a valid amount.");
            $('#cardClass'+index).addClass('border-danger');
            $('#currency_amount'+index).focus();
            return false;
        }

        if($('#currency_code'+index).val() != '' && $('#currency_amount'+index).val() > 0){
            $('#cardClass'+index).removeClass('border-danger');
            getExchangeRate(index);
        }
    }

    function urlExists(imgUrl) {
        var http = jQuery.ajax({
            type:"HEAD",
            url: imgUrl,
            async: false
        })
        return http.status;
    }
    function exchangeCurrency(fieldvalue, index, fieldsrc, fieldtext){
        if($('#currency_code'+index).val() != ''){
            let CurrencyCodeVal = $('#currency_code'+index).val();
            $('#currency_code'+index).val($('#'+fieldvalue).val()).trigger('change');
            const arrfromindex = toCurrencyArr[index].indexOf(CurrencyCodeVal);
            if(arrfromindex == '-1'){
                let imgSrc = "/assets/flags/NoImage.png";
                if(urlExists("/assets/flags/"+CurrencyCodeVal+".png") == 200) {
                    imgSrc = "/assets/flags/"+CurrencyCodeVal+".png"
                }
                $("#"+fieldsrc).attr("src", imgSrc);
                const arrindex = toCurrencyArr[index].indexOf($('#'+fieldvalue).val());
                toCurrencyArr[index].splice(arrindex, 1);
                toCurrencyArr[index].push(CurrencyCodeVal);

                $('#'+fieldtext).text(CurrencyCodeVal);
                $('#'+fieldvalue).val(CurrencyCodeVal);
            }
        }else{
            $('#currency_code'+index).val($('#'+fieldvalue).val()).trigger('change');
        }
        //return false;
        if($('#currency_code'+index).val() != '' && $('#currency_amount'+index).val() > 0){
            getExchangeRate(index);
        }else{
            if($('#currency_amount'+index).val() == ''){
                toastr.clear();
                toastr.error("Please enter an amount.");
                $('#cardClass'+index).addClass('border-danger');
                $('#currency_amount'+index).focus();
                return false;
            }
            if($('#currency_amount'+index).val() <= 0){
                toastr.clear();
                toastr.error("Please enter a valid amount.");
                $('#cardClass'+index).addClass('border-danger');
                $('#currency_amount'+index).focus();
                return false;
            }
        }
    }
    function addCommas(nStr)
    {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
    function getExchangeRate(index){
        $('#loading').show();
        let CurrencyArr = toCurrencyArr[index];
        $.ajax({
            type:'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url:"{{ route('exchange_rate') }}",
            data: { CurrencyCode: $('#currency_code'+index).val(), Currencyamount: $('#currency_amount'+index).val(), CurrencyArr },
            success:function(data){
                if(data != ''){
                    $('#aed_amount_id'+index).text(addCommas(data[$('#aed_currency_val'+index).val()]));
                    $('#eur_amount_id'+index).text(addCommas(data[$('#eur_currency_val'+index).val()]));
                    $('#gbp_amount_id'+index).text(addCommas(data[$('#gbp_currency_val'+index).val()]));
                    $('#qar_amount_id'+index).text(addCommas(data[$('#qar_currency_val'+index).val()]));
                    $('#sar_amount_id'+index).text(addCommas(data[$('#sar_currency_val'+index).val()]));
                    $('#usd_amount_id'+index).text(addCommas(data[$('#usd_currency_val'+index).val()]));
                }else{
                    $('#usd_amount_id'+index).text('0.00');
                    $('#aed_amount_id'+index).text('0.00');
                    $('#eur_amount_id'+index).text('0.00');
                    $('#sar_amount_id'+index).text('0.00');
                    $('#gbp_amount_id'+index).text('0.00');
                    $('#qar_amount_id'+index).text('0.00');
                    toastr.error("Something went wrong. Please try again.");
                }
                $('#loading').hide();
            }, error: function() {
                $('#loading').hide();
                toastr.error("Something went wrong. Please try again.");
            }
        });
    }
    function addMoreRows(index){
        let elements = `<tr id="rows`+index+`">
            <td class="from-section pb-md-4" id="from-section-select`+index+`">
                <div class="card" id="cardClass`+index+`">
                    <div class="card-content  p-2">
                        <div class="d-flex align-items-center">
                        <div class="me-2" id="image`+index+`"><img src="{{asset('/assets/flags/NoImage.png')}}" alt="flag"/ id="img`+index+`"></div> 
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="currecy-select d-flex justify-content-center align-items-center  mb-0">
                                    <select name="currency_code" id="currency_code`+index+`" onchange="changeFlag(this.value, `+index+`)">
                                        <option value=""> Select Currency</option>
                                        @foreach ($currencies as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </p>
                            </div>
                        </div>
                        </div>
                    <div class="card amount-section text-start p-2">
                            <input type="text" name="currency_amount" id="currency_amount`+index+`" class="amount form-control" placeholder="Enter Amount Here" onkeyup="getAmountOnKeyUp(this.value, `+index+`)" onchange="getAmountOnChange(this.value, `+index+`)"/>
                        </div>
                    </div>
                </div>
            </td>
            <td>
                <div class="reverse-btn-wrap my-4">
                    <span class="reverse-btn" style="cursor:pointer">
                        <button class="btn reverseBtn text-white" onclick="convertToCurrencies(`+index+`)">Convert</button>
                    </span>
                </div>
            </td>
            <td class="to-section pb-md-4">
                <div class="card py-3 px-2">
                    <div class="to-content">
                        <div>
                            <button type="button" class="d-flex justify-content-center align-items-center mx-auto mb-1" id="usd_currency_val`+index+`" style="cursor:pointer" value="USD" onclick="exchangeCurrency('usd_currency_val`+index+`', `+index+`, 'usd_img_id`+index+`', 'usd_img_span`+index+`')">
                                <img src="{{asset('/assets/flags/USD.png')}}" id="usd_img_id`+index+`" alt="Flag" class="img-fluid me-1"/> <span id="usd_img_span`+index+`">USD</span>
                            </button>
                            <span id="usd_amount_id`+index+`">0.00</span>
                        </div>
                        <div>
                            <button type="button" class="d-flex justify-content-center align-items-center mx-auto mb-1" id="aed_currency_val`+index+`" style="cursor:pointer" value="AED" onclick="exchangeCurrency('aed_currency_val`+index+`', `+index+`, 'aed_img_id`+index+`', 'aed_img_span`+index+`')">
                                <img src="{{asset('/assets/flags/AED.png')}}" id="aed_img_id`+index+`" alt="Flag" class="img-fluid me-1"/><span id="aed_img_span`+index+`">AED</span>
                            </button>
                            <span id="aed_amount_id`+index+`">0.00</span>
                        </div>
                        <div>
                            <button type="button" class="d-flex justify-content-center align-items-center mx-auto  mb-1" id="eur_currency_val`+index+`" style="cursor:pointer" value="EUR" onclick="exchangeCurrency('eur_currency_val`+index+`', `+index+`, 'eur_img_id`+index+`', 'eur_img_span`+index+`')">
                                <img src="{{asset('/assets/flags/EUR.png')}}" id="eur_img_id`+index+`" alt="Flag" class="img-fluid me-1"/><span id="eur_img_span`+index+`">EUR</span>
                            </button>
                            <span id="eur_amount_id`+index+`">0.00</span>
                        </div>
                        <div>
                            <button type="button" class="d-flex justify-content-center align-items-center mx-auto  mb-1" id="sar_currency_val`+index+`" style="cursor:pointer" value="SAR" onclick="exchangeCurrency('sar_currency_val`+index+`', `+index+`, 'sar_img_id`+index+`', 'sar_img_span`+index+`')">
                                <img src="{{asset('/assets/flags/SAR.png')}}" id="sar_img_id`+index+`" alt="Flag" class="img-fluid me-1"/><span id="sar_img_span`+index+`">SAR</span>
                            </button>
                            <span id="sar_amount_id`+index+`">0.00</span>
                        </div>
                        <div>
                            <button type="button" class="d-flex justify-content-center align-items-center mx-auto  mb-1" id="gbp_currency_val`+index+`" style="cursor:pointer" value="GBP" onclick="exchangeCurrency('gbp_currency_val`+index+`', `+index+`, 'gbp_img_id`+index+`', 'gbp_img_span`+index+`')">
                                <img src="{{asset('/assets/flags/GBP.png')}}" id="gbp_img_id`+index+`" alt="Flag" class="img-fluid me-1"/><span id="gbp_img_span`+index+`">GBP</span>
                            </button>
                            <span id="gbp_amount_id`+index+`">0.00</span>
                        </div>
                        <div>
                            <button type="button" class="d-flex justify-content-center align-items-center mx-auto mb-1" id="qar_currency_val`+index+`" style="cursor:pointer" value="QAR" onclick="exchangeCurrency('qar_currency_val`+index+`', `+index+`, 'qar_img_id`+index+`', 'qar_img_span`+index+`')">
                                <img src="{{asset('/assets/flags/QAR.png')}}" id="qar_img_id`+index+`" alt="Flag" class="img-fluid me-1"/><span id="qar_img_span`+index+`">QAR</span>
                            </button>
                            <span id="qar_amount_id`+index+`">0.00</span>
                        </div>
                    </div>  
                </div>
            </td>
            <td>
                <div class="delete-action  my-4">
                <a ><i class="fa fa-trash" style="cursor:pointer" onclick="deleteRow(`+index+`)"></i></a>
                </div>
            </td>
        </tr>`;
        return elements;
    }
    function deleteRow(index){
        if(index > 0){
            $('#rows'+index).remove();
        }
    }
</script>
