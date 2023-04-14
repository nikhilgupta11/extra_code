<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Promocode;
use Illuminate\Support\Str;

class PromocodeController extends Controller
{
    public $softDeleteAction = false;
    public $module_title;
    public $module_name;
    public $module_path;
    public $module_icon;
    public $module_model;
    public function __construct()
    {
        // Page Title
        $this->module_title = 'Coupons';

        // module name
        $this->module_name = 'coupons';

        // directory path of the module
        $this->module_path = 'coupons';

        // module icon
        $this->module_icon = 'fa fa-tag';

        // module model name, path
        $this->module_model = "App\Models\Promocode";
    }

    public function coupons($id = '')
    {
        $module_title = $this->module_title;
        $module_name = $this->module_name;
        $module_path = $this->module_path;
        $module_icon = $this->module_icon;
        $module_model = $this->module_model;
        $module_name_singular = Str::singular($module_name);
        $module_action = 'List';

        $page_heading = ucfirst($module_title);
        $title = $page_heading . ' ' . ucfirst($module_action);

        $edit = '';
        if ($id != '') {
            $edit = Promocode::find($id);
        }
        $promos = Promocode::withCount('users')->latest()->get();
        return view(
            'backend.' . $this->module_name . '.index',
            compact('promos', 'edit', 'module_title', 'module_name', 'module_path', 'module_icon', 'module_action', 'module_name_singular', 'page_heading', 'title')
        );
    }

    public function coupon_delete($id)
    {
        try {
            Promocode::find($id)->delete();
            flash("<i class='fas fa-check'></i> Promo Code Deleted!")->success()->important();
        } catch (\Exception $e) {
            flash("<i class='fas fa-check'></i> ".$e->getMessage())->danger()->important();
        }
        return redirect()->back();
    }

    public function coupon_create(Request $request)
    {
        try {
            if ($request->has('id')) {
                $promo = Promocode::find($request->id);
                $message = 'PromoCode Updated';
            } else {
                $promo = new Promocode();
                $message = 'PromoCode Generated';
            }
            $promo->coupon = strtoupper(str_replace(' ', '', $request->coupon));
            $promo->type = $request->type;
            $promo->value = $request->value;
            $promo->status = $request->status;
            $promo->save();
            flash("<i class='fas fa-check'></i> $message")->success()->important();
        } catch (\Exception $e) {
            flash("<i class='fas fa-check'></i> ".$e->getMessage())->danger()->important();
        }
        return redirect()->route('backend.coupons');
    }
}
