<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Promocode;
use App\Models\TransportForm;
use App\Models\Trip;
use App\Models\TripCalculation;
use App\Models\User;
use App\Traits\CalculationServiceClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\Article\Entities\Post;
use Modules\Page\Models\Page;
use Modules\Project\Models\Project;
use Modules\Transportation\Models\Transportation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use stdClass;

class FrontController extends Controller
{
    use CalculationServiceClass;
    public function createTrip(Request $request)
    {
        $rules = [
            'from' => 'required|string',
            'to' => 'required|string',
            'round_trip' => 'integer',
            'start_date' => 'required|date',
            'trip_days' => 'required|integer',
            'peoples' => 'required|integer'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse('Validation Error!', 422, $validator->errors());
        }
        $user = $request->user();
        $data = $request->all();
        $data['user_id'] = $user->id;
        $trip = Trip::create($data);
        return $this->successResponse(['trip' => $trip], 'Trip Created!', 200);
    }

    public function transports()
    {
        $transports = Transportation::whereHas('form', function ($query) {
            $query->where('status', '=', 1);
        })->where('status', 1)->select('id', 'transport_type as name', 'image')->get();
        return $this->successResponse(['transports' => $transports], 'Transports Show!', 200);
    }

    public function transportForm()
    {
        $transportForm = TransportForm::where('transportation_id', request()->transport)->where('status', 1)->select('id', 'transportation_id', 'form_data')->latest()->first();
        return $this->successResponse(['transportForm' => $transportForm], 'Transport Form Show!', 200);
    }

    public function transportFormSave(Request $request, $trip_id)
    {
        $rules = [
            'transport_id' => 'required|integer',
            'form_data' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse('Validation Error!', 422, $validator->errors());
        }
        $trip = Trip::findOrFail($trip_id);
        $transport = Transportation::findOrFail($request->transport_id);
        $transportCO2 = round($this->calculateCO2($transport, $request->form_data));
        $requestData = [
            'transportation_id' => $transport->id,
            'trip_id' => $trip->id,
            'user_data' => json_encode($request->form_data),
            'total_co2' => $transportCO2,
            'updated_at' => now()->toDateTimeString(),
        ];

        $column = (config('transports.column') == 'transport_type') ? strtolower($transport[config('transports.column')]) : $transport[config('transports.column')];
        if ($column != config('transports.hotel')) {
            $requestData['total_co2'] = $requestData['total_co2'] * $trip->peoples;
        }
        if ($request->has('id')) {
            DB::table('trip_transportation')->where('id', $request->id)->where('trip_id', $trip_id)->update($requestData);
        } else {
            $requestData['created_at'] = now()->toDateTimeString();
            DB::table('trip_transportation')->insert($requestData);
        }

        $hotel_id = Transportation::where(config('transports.column'), config('transports.hotel'))->select('id')->first()->id;
        $transport_emission_total = DB::table('trip_transportation')->where('transportation_id', '!=', $hotel_id)->where('trip_id', $trip_id)->sum('total_co2');
        $accommodation_emission_total = DB::table('trip_transportation')->where('transportation_id', $hotel_id)->where('trip_id', $trip_id)->sum('total_co2');
        $total = $transport_emission_total + $accommodation_emission_total;
        $totalPerPersonPerDay = ($total / $trip->peoples) / $trip->trip_days;
        $trip->calculation()->updateOrCreate(['trip_id' =>  $trip->id], [
            'transport_emission_total' => $transport_emission_total,
            'accommodation_emission_total' => $accommodation_emission_total,
            'total_co2_per_person' => $totalPerPersonPerDay,
            'total_emission' => $total,
            'updated_at' => now()->toDateTimeString(),
            'created_at' =>  now()->toDateTimeString()
        ]);

        return $this->successResponse(['total_co2' => $total], 'Transport Form Saved!', 200);
    }

    public function tripCalculation(Request $request, $trip_id, $history = false)
    {
        $globalAverageEmissions = 0;
        $myTotalEmissions = 0;
        $transportsData = [];
        $trip = Trip::with('calculation', 'frontPayment.project:id,name,banner')->findOrFail($trip_id);
        $transports = Transportation::whereHas('form', function ($query) {
            $query->where('status', '=', 1);
        })->where('status', 1)->select('transport_type', 'id')->get();
        $transportsData['trip'] = $trip->toArray();

        $myTotalEmissions = (float)TripCalculation::whereHas('trip', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        })->sum('total_co2_per_person');
        $countsForAverage = (int)TripCalculation::count();
        $totalForAverage = round(floatval(TripCalculation::sum('total_co2_per_person')), 2);
        $globalAverageEmissions = round(floatval($totalForAverage / $countsForAverage), 2);
        $transportsData['barData'] = [
            'my_co2_emissions' => $myTotalEmissions,
            'average_emissions' => $globalAverageEmissions
        ];

        if ($history == true) {
            $transportsData['trip']['via_1'] = "";
            $transportsData['trip']['via_2'] = "";
            $transportsData['trip']['via_3'] = "";
            $airplane = Transportation::where('status', 1)->where(config('transports.column'), 'like', "%" . config('transports.airplane') . "%")->select('id')->first()->id;
            $hotel = Transportation::where('status', 1)->where(config('transports.column'), 'like', "%" . config('transports.hotel') . "%")->select('id')->first()->id;
            $userData = $trip->transports()->where('transportation_id', $airplane)->select('user_data')->first();
            $userData2 = $trip->transports()->where('transportation_id', $hotel)->select('user_data')->first();
            if ($userData != null) {
                $collect = collect(json_decode($userData->user_data));
                $transportsData['trip']['via_1'] = $collect->where('name', 'via_1')->first()->value;
                $transportsData['trip']['via_2'] = $collect->where('name', 'via_2')->first()->value;
            }
            if ($userData2 != null) {
                $collect2 = collect(json_decode($userData2->user_data));
                $transportsData['trip']['via_3'] = $collect2->where('name', 'country')->first()->value;
            }
        }

        $transportsData['transports'] = [];
        $selectedTransports = $trip->transportsPivot()->select('transport_type')->get();

        foreach ($transports as $key => $transport) {
            $transportType = $transport->transport_type;
            $kgs = 0;
            $percent = 0;
            if ($selectedTransports->count() > 0 && $selectedTransports->where('pivot.transportation_id', $transport->id)->count() > 0) {
                $transportType = $transport->transport_type;
                $kgs = $selectedTransports->where('pivot.transportation_id', $transport->id)->sum('pivot.total_co2');
                $percent = round($kgs / $trip->calculation->total_emission * 100, 2);
            }
            $transportsData['transports'][] = [
                'transport_type' => $transportType,
                'kgs' => $kgs,
                'percentage' => $percent
            ];
        }
        return $this->successResponse($transportsData, 'Trip Calculation!', 200);
    }

    public function transportList($trip, $transport_id)
    {
        $rows = DB::table('trip_transportation')->where('trip_id', $trip)->where('transportation_id', $transport_id)->get();
        return $this->successResponse(['rows' => $rows], 'Transport Form List!', 200);
    }

    public function transportListDelete($trip_id, $userData_id)
    {
        DB::table('trip_transportation')->where('id', $userData_id)->where('trip_id', $trip_id)->delete();
        $total = DB::table('trip_transportation')->where('trip_id', $trip_id)->sum('total_co2');
        return $this->successResponse(['total_co2' => $total], 'Transport Form Data Deleted!', 200);
    }

    public function tripSaveToHistory(Request $request, $trip_id)
    {
        $trip = Trip::findOrFail($trip_id);
        $trip->trip_history = 1;
        $trip->save();
        return $this->successResponse(null, 'Trip Saved to History!', 200);
    }

    public function projects()
    {
        $projects = Project::where('status', 1)->select('id', 'name', 'banner', 'price', 'sale_price', 'description')->latest()->get();
        return $this->successResponse(['projects' => $projects], 'Projects Show!', 200);
    }

    public function projectDetails()
    {
        $project = Project::where('id', request()->project)->where('status', 1)->select('id', 'name', 'banner', 'slug', 'category_name', 'sku', 'price', 'sale_price', 'description')->first();
        return $this->successResponse(['project' => $project], 'Project Details Show!', 200);
    }

    public function pages(Request $request)
    {
        $pages = Page::where('status', 1)->select('id', 'name', 'image')->where('page_type', 'page')->get();
        return $this->successResponse(['pages' => $pages], 'Pages Show!', 200);
    }

    public function pageDetails()
    {
        $page = Page::where('id', request()->page)->where('status', 1)->where('page_type', 'page')->select('id', 'name', 'slug', 'content', 'image', 'imgs_videos')->first();
        return $this->successResponse(['page' => $page], 'Page Details Show!', 200);
    }

    public function faq()
    {
        $page = Page::where('status', 1)->where('page_type', 'faq')->select('id', 'name', 'slug', 'content', 'image')->get();
        return $this->successResponse(['faq' => $page], 'FAQ Data Show!', 200);
    }

    public function contactStore(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:50|min:2',
            'email' => 'required|email',
            'phone' => 'required|integer',
            'description' => 'required|min:10|max:250',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse('Validation Error!', 422, $validator->errors());
        }
        $data = Enquiry::create($request->all());
        return $this->successResponse(['data' => $data], 'Query Saved!', 200);
    }

    public function blogs()
    {
        $blogs = Post::where('status', 1)->select('id', 'name', 'intro', 'category_name', 'banner', 'created_at')->latest()->get();
        return $this->successResponse(['blogs' => $blogs], 'Tips & Suggestions Show!', 200);
    }

    public function blogDetails()
    {
        $blog = Post::where('id', request()->blog)->where('status', 1)->select('id', 'name', 'intro', 'category_name', 'banner', 'content', 'category_name', 'category_id', 'imgs_videos', 'created_at')->first();
        return $this->successResponse(['blog' => $blog], 'Tip & Suggestion Details Show!', 200);
    }

    public function paymentMethod(Request $request)
    {
        $rules = [
            'card_holder' => 'required|string|max:50|min:2',
            'card_number' => 'required|max:16',
            'expiry' => 'required',
            'card_code' => 'required|max:4',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse('Validation Error!', 422, $validator->errors());
        }
        $user = User::findOrFail($request->user()->id);
        $data = $request->all();
        $data['expiry'] = date('Y-m-d', strtotime($data['expiry']));
        $user->paymentMethod()->updateOrCreate(['user_id' => $user->id], $data);
        return $this->successResponse(['paymentMethod' => $user->paymentMethod], 'Payment Method Saved!', 200);
    }

    public function paymentMethodShow(Request $request)
    {
        $user = PaymentMethod::where('user_id', $request->user()->id)->first();
        return $this->successResponse(['paymentMethod' => $user], 'Payment Method Show!', 200);
    }

    public function storeBillingDetails(Request $request)
    {
        if ($request->has('id') && $request->has('show')) {
            $payment = Payment::findOrFail($request->id);
            $payment->expiry = Carbon::createFromFormat('Y-m-d', $payment->expiry)->format('m/y');
            return $this->successResponse(['billingDetails' => $payment], 'Payment Details Show!', 200);
        }
        $rules = [
            'trip_id' => 'required|integer',
            'project_id' => 'required|integer',
            'first_name' => 'required|string|max:50|min:2',
            'last_name' => 'required|string|max:50|min:2',
            'country' => 'required|string',
            'state' => 'string',
            'city' => 'required|string',
            'street' => 'required|string|max:100,min:3',
            'zip' => 'required|integer|min:111111|max:999999',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse('Validation Error!', 422, $validator->errors());
        }
        $project = Project::findOrFail($request->project_id);
        $payment = new Payment();
        if ($request->has('id')) {
            $payment = Payment::findOrFail($request->id);
        }
        $payment->trip_id = $request->trip_id;
        $payment->user_id = $request->user()->id;
        $payment->project_id = $project->id;
        $payment->first_name = $request->first_name;
        $payment->last_name = $request->last_name;
        $payment->country = $request->country;
        $payment->state = $request->state;
        $payment->city = $request->city;
        $payment->street = $request->street;
        $payment->zip = $request->zip;
        $payment->card_holder = $request->card_holder;
        $payment->card_number = $request->card_number;
        $payment->expiry = Carbon::createFromFormat('d/m/y', "01/" . $request->expiry)->format('Y-m-d');
        $payment->card_code = $request->card_code;
        if ($request->payment_status == 'success' && $payment->certificate == null) {
            $payment->certificate = generateCertificateNumber($project);
        }
        if (!$request->has('id')) {
            $payment->order_id = Str::random();
        }
        if ($request->has('payment_id')) {
            $payment->payment_id = $request->payment_id;
        }
        if ($request->has('discount_coupon') && $request->discount_coupon != "") {
            Promocode::where('coupon', $request->discount_coupon)->where('status', 1)->first()->users()->attach([$request->user()->id]);
            $payment->discount_coupon = $request->discount_coupon;
        }
        if ($request->has('discount')) {
            $payment->discount = $request->discount;
        }
        $payment->amount = $request->amount;
        $payment->payment_status = $request->payment_status;
        $payment->save();
        $payment->expiry = Carbon::createFromFormat('Y-m-d', $payment->expiry)->format('m/y');
        return $this->successResponse(['billingDetails' => $payment], 'Payment Details Saved!', 200);
    }

    public function downloadPDF(Request $request, $certificate_num)
    {
        $pdf = Pdf::loadView('certificate-template')->setPaper('letter', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('certificate.pdf');
        $certificate_name = Payment::where('certificate', $certificate_num)->where('user_id', $request->user()->id)->select('certificate')->first();
        if ($certificate_name != null) {
            $pdf = Pdf::loadView('certificate-template')->setPaper('letter', 'landscape')->setOptions(['defaultFont' => 'sans-serif']);
            return $pdf->download($certificate_name->certificate . '.pdf');
        }
        return $this->errorResponse('Certificate Not Found', 404);
    }

    public function calculationHistory(Request $request)
    {
        $trips = Trip::with('calculation:trip_id,total_emission')->where('user_id', $request->user()->id)->select('id', 'from', 'to', 'round_trip', 'start_date', 'trip_days', 'peoples', 'trip_history')->latest()->get();
        return $this->successResponse(['trips' => $trips], 'Trips List', 200);
    }

    public function calculationDetails(Request $request, $trip_id)
    {
        return $this->tripCalculation($request, $trip_id, true);
    }

    public function CalculationHistoryDelete($trip_id)
    {
        $trip = Trip::findOrFail($trip_id);
        $trip->trip_history = 0;
        $trip->save();
        return $this->successResponse(null, 'Trip Deleted!', 200);
    }

    public function dashboard(Request $request)
    {
        $totalEmissions = 0;
        $totalPersons = 0;
        $totalEmissionsPerPerson = 0;
        $carbonOffset = 0;
        $carbonOffsetPercent = 0;
        $transports = [];
        $globalAverageEmissions = 0;

        $transportsTable = Transportation::where('status', 1)->select('transport_type', 'id')->get();

        $userTrips = Trip::where('user_id', $request->user()->id)->select('id')->pluck('id');
        $allTripsTransports = DB::table('trip_transportation')->whereIn('trip_id', $userTrips)->select('transportation_id', 'trip_id', 'total_co2')->get();

        if (Trip::where('user_id', $request->user()->id)->count() > 0) {
            $totalEmissions = TripCalculation::whereHas('trip', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })->sum('total_emission');
            $totalPersons = (int)Trip::where('user_id', $request->user()->id)->sum('peoples');
            $totalEmissionsPerPerson = (float)TripCalculation::whereHas('trip', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })->sum('total_co2_per_person');
            $countsForAverage = (int)TripCalculation::count();
            $totalForAverage = round(floatval(TripCalculation::sum('total_co2_per_person')), 2);
            $globalAverageEmissions = round(floatval($totalForAverage / $countsForAverage), 2);

            $carbonOffset = TripCalculation::whereHas('trip', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id)->whereHas('payment', function ($sub) {
                    $sub->where('payment_status', 'success');
                });
            })->sum('total_emission');
            $carbonOffsetPercent = round($carbonOffset / $totalEmissions * 100, 2);
        }

        foreach ($transportsTable as $key => $transport) {
            $transportType = $transport->transport_type;
            $kgs = 0;
            $percent = 0;
            if ($allTripsTransports->where('transportation_id', $transport->id)->count() > 0) {
                $kgs = $allTripsTransports->where('transportation_id', $transport->id)->sum('total_co2');
                $percent = round($kgs / $totalEmissions * 100, 2);
            }
            $transports[] = [
                'transport_type' => $transportType,
                'kgs' => $kgs,
                'percentage' => $percent
            ];
        }
        return $this->successResponse(
            compact(
                'totalEmissions',
                'totalPersons',
                'totalEmissionsPerPerson',
                'carbonOffset',
                'carbonOffsetPercent',
                'globalAverageEmissions',
                'transports'
            ),
            'Dashboard!',
            200
        );
    }

    public function calculateDiscount(Request $request)
    {
        $promo = Promocode::where('coupon', $request->code)->where('status', 1)->first();
        $price = Project::findOrFail($request->project_id)->price ?? 0;
        $discount = 0;
        $message = 'Coupon Code Not Available!';
        if ($promo != '') {
            if ($promo->type == 'price') {
                $discount = $promo->value;
                $price = $price - $discount;
            } elseif ($promo->type == 'percentage') {
                $discount = ($promo->value / 100) * $price;
                $price = $price - $discount;
            }
            $message = 'Coupon Code Applied!';
        }
        $data = new stdClass();
        $data->promo = $request->code;
        $data->price = number_format((float)$price, 2, '.', '');
        $data->discount = number_format((float)$discount, 2, '.', '');
        return $this->successResponse(
            compact(
                'data',
            ),
            $message,
            200
        );
    }
}
