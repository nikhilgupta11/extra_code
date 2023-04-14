<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CurrencyCategoryStoreRequest as StoreRequest;
use App\Http\Requests\CurrencyCategoryUpdateRequest as UpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CurrencyCategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CurrencyCategoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\CurrencyCategory::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings('currency category', 'currency categories');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name');
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
        CRUD::addColumn([
            'name'     => 'created_at',
            'label'    => 'Created At',
            'type'     => 'closure',
            'function' => function($entry) {
                return 'Created on '.$entry->created_at;
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

        $this->addCategoryFeild();
        

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

        $this->addCategoryFeild();

    }
    protected function addCategoryFeild(){
        CRUD::field('name');
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
    public function setupShowOperation()
    {
        CRUD::column('name');
        CRUD::addColumn([
            'name'        => 'status',
            'label'       => 'Activate',
            'type'        => 'boolean'
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
    }
}
