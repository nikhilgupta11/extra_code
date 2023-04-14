@extends(backpack_view('blank'))

@php	
	$userCount = App\Models\User::count();
	$currencyCategoryCount = App\Models\CurrencyCategory::count();
	$currencyCount = App\Models\Currency::count();
	
 
 	// notice we use Widget::add() to add widgets to a certain group
	Widget::add()->to('before_content')->type('div')->class('row')->content([
		// notice we use Widget::make() to add widgets as content (not in a group)
		Widget::make()
			->type('progress')
			->class('card border-0 text-white bg-primary')
			->progressClass('progress-bar')
			->value($userCount)
			->description('Registered users.')
			// ->progress(100)
			->hint('Total Registred User'),
		Widget::make()
			->group('hidden')
		    ->type('progress')
		    ->class('card border-0 text-white bg-primary')
		    ->value($currencyCount)
		    ->progressClass('progress-bar')
		    ->description('Currency')
		    // ->progress(30)
		    ->hint('Total Currency'),
	    Widget::make([
			'type' => 'progress',
			'class'=> 'card border-0 text-white bg-primary',
			'progressClass' => 'progress-bar',
			'value' => $currencyCategoryCount,
			'description' => 'Currency Category',
			// 'progress' => 12,
			'hint' => 'Total Currency Category',
		]),
	]);


    

    
@endphp

@section('content')
	{{-- In case widgets have been added to a 'content' group, show those widgets. --}}
	@include(backpack_view('inc.widgets'), [ 'widgets' => app('widgets')->where('group', 'content')->toArray() ])
@endsection
