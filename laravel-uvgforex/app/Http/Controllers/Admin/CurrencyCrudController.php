<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CurrencyStoreRequest as StoreRequest;
use App\Http\Requests\CurrencyUpdateRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use DB;

/**
 * Class CurrencyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CurrencyCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Currency::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/currency');
        CRUD::setEntityNameStrings('currency', 'currencies');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name'        => 'name',
            'label'       => 'Name'
        ]);
        CRUD::column('Category Name')
                ->type('select')
                ->entity('categoryid')
                ->attribute('name')
                ->model("App\Models\CurrencyCategory")
                ->searchLogic(function($query, $column, $searchTerm){
                    $catgoryId = DB::table('currency_category')->orWhere('name', 'like', '%'.$searchTerm.'%')->pluck('id');
                    $query->orWhereIn('currency_category_id',$catgoryId);
                })
                ->wrapper([
                    'href' => function ($crud, $column, $entry, $related_key) {
                        return backpack_url('category/'.$related_key.'/show');
                    },
                ]);
        CRUD::addColumn([
            'name'        => 'exchange_rate',
            'label'       => 'UVG Value'
        ]);
        CRUD::addColumn([
            'name'        => 'exchange_rate_to_USD',
            'label'       => 'Converted USD Value',
        ]);
        CRUD::addColumn([
            'name'        => 'status',
            'label'       => 'Activate',
            'type'        => 'boolean',
            'searchLogic' => function ($query, $column, $searchTerm) {
                $searchBoolean = null;
                if (strpos('yes', strtolower($searchTerm)) !== false) {
                    $searchBoolean = 1;
                    $query->orWhere('status', 'like', '%'.$searchBoolean.'%');
                }
                if (strpos('no', strtolower($searchTerm)) !== false) {
                    $searchBoolean = 0;
                    $query->orWhere('status', 'like', '%'.$searchBoolean.'%');
                }
            }
        ]);
        CRUD::enableExportButtons();
        $this->addCustomCrudFilters();
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(StoreRequest::class);

        $this->addCurrenyFeild();
        
        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(UpdateRequest::class);
     
        $this->editCurrenyFeild();

    }
    public function setupShowOperation()
    {
        CRUD::column('name');
        CRUD::column('Category Name')
                ->type('select')
                ->entity('categoryid')
                ->attribute('name')
                ->model("App\Models\CurrencyCategory")
                ->wrapper([
                    'href' => function ($crud, $column, $entry, $related_key) {
                        return backpack_url('category/'.$related_key.'/show');
                    },
                ]);
        CRUD::addColumn([
            'name'        => 'status',
            'label'       => 'Activated',
            'type'        => 'boolean'
        ]);
        CRUD::column('code');
        CRUD::addcolumn([
            'label'       => 'Assign UVG value',
            'name'        => 'exchange_rate',
        ]);
        CRUD::addcolumn([
            'label'       => 'Converted USD value',
            'name'        => 'exchange_rate_to_USD',
        ]);
        CRUD::addColumn([
            'name'     => 'created_at',
            'label'    => 'Created At',
            'type'     => 'closure',
            'function' => function($entry) {
                return 'Created on '.$entry->created_at;
            }
        ]);
        CRUD::addColumn([
            'name'     => 'updated_at',
            'label'    => 'Updated At',
            'type'     => 'closure',
            'function' => function($entry) {
                return 'Updated on '.$entry->updated_at;
            }
        ]);
    }
    public function addCurrenyFeild(){
        CRUD::field('name');
        CRUD::addField([  // Select
            'label'     => "Category",
            'type'      => 'select2',
            'name'      => 'currency_category_id', // the db column for the foreign key
         
            // optional
            // 'entity' should point to the method that defines the relationship in your Model
            // defining entity will make Backpack guess 'model' and 'attribute'
            'entity'    => 'categoryid',
         
            // optional - manually specify the related model and attribute
            'model'     => "App\Models\CurrencyCategory", // related model
            'attribute' => 'name', // foreign key attribute that is shown to user
         
            // optional - force the related options to be a custom query, instead of all();
            'options'   => (function ($query) {
                 return $query->orderBy('created_at', 'DESC')->where('status', '1')->get();
            }), 
            //  you can use this to filter the results show in the select
        ]); 
        CRUD::field('code');
        CRUD::addfield([
            'label'       => 'Assign UVG value (Please enter values between 0.01 to 9999)',
            'name'        => 'exchange_rate',
            'attributes'  => [
               'id' => 'exchange_rate',
            ]
        ]);
        CRUD::addfield([
            'label'       => 'Converted USD value',
            'name'        => 'exchange_rate_to_USD',
            //'type'        => 'hidden',
            'attributes'     => [ 
                'readonly' => "readonly",
                'id' => 'exchange_rate_to_USD'
            ],
        ]);
        CRUD::addField([
            'name'        => 'status',
            'label'       => 'Status',
            'type'        => 'radio',
            'options'     => [ 
                                '1' => "Activate",
                                '0' => "Deactivate"
            ],
            //'default'     => '1',
        ]);
    }
    public function editCurrenyFeild(){
        CRUD::field('name');
        CRUD::addField([  // Select
            'label'     => "Category",
            'type'      => 'select2',
            'name'      => 'currency_category_id', // the db column for the foreign key
         
            // optional
            // 'entity' should point to the method that defines the relationship in your Model
            // defining entity will make Backpack guess 'model' and 'attribute'
            'entity'    => 'categoryid',
         
            // optional - manually specify the related model and attribute
            'model'     => "App\Models\CurrencyCategory", // related model
            'attribute' => 'name', // foreign key attribute that is shown to user
         
            // optional - force the related options to be a custom query, instead of all();
            'options'   => (function ($query) {
                 return $query->orderBy('created_at', 'DESC')->get();
            }), 
            //  you can use this to filter the results show in the select
        ]); 
        CRUD::addfield([
            'label'       => 'Code',
            'name'        => 'code',
            'attributes'     => [ 
                'readonly' => "readonly"
            ],
        ]);
        CRUD::addfield([
            'label'       => 'Assign UVG value (Please enter values between 0.01 to 9999)',
            'name'        => 'exchange_rate',
            'attributes'  => [
               'id' => 'exchange_rate'
            ]
        ]);
        CRUD::addfield([
            'label'       => 'Converted USD value',
            'name'        => 'exchange_rate_to_USD',
            //'type'        => 'hidden',
            'attributes'     => [ 
                'readonly' => "readonly",
                'id' => 'exchange_rate_to_USD'
            ],
        ]);
        CRUD::addField([
            'name'        => 'status',
            'label'       => 'Status',
            'type'        => 'radio',
            'options'     => [ 
                                '1' => "Activate",
                                '0' => "Deactivate"
            ],
            //'default'     => '1',
        ]);
    }
    protected function addCustomCrudFilters()
    {
        CRUD::filter('select2')
            ->type('select2')
            ->label('Status')
            ->values(function () {
            return [
                '1' => 'Activate',
                '0' => 'Deactivate'
            ];
        })->whenActive(function ($value) {
            CRUD::addClause('where', 'status', $value);
        });
        CRUD::addFilter([ // select2 filter
            'name' => 'currency',
            'type' => 'select2',
            'label'=> 'Currency Category',
        ], function () {
            return \App\Models\CurrencyCategory::where('status',1)->orderBy('created_at', 'DESC')->pluck('name', 'id')->toArray();
        }, function ($value) { // if the filter is active
            CRUD::addClause('where', 'currency_category_id', $value);
        });
    }
}
