<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        $this->crud->setModel('App\Models\Product');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/product');
        $this->crud->setEntityNameStrings('product', 'products');
    }

    protected function setupListOperation()
    {
        // TODO: remove setFromDb() and manually define Columns, maybe Filters
        // $this->crud->setFromDb();
        $this->crud->addColumn(['name' => 'product_code', 'label' => "Product Code", 'type' => 'Text']);
        $this->crud->addColumn(['name' => 'name', 'label' => "Name", 'type' => 'Text']);
        $this->crud->addColumn([
            'name'      => 'image', // The db column name
            'label'     => 'Image', // Table column heading
            'type'      => 'image',
            'prefix'    => asset(''),
            'height'    => '30px',
            'width'     => '30px',
        ]);
        $this->crud->addColumn([
            'type'      => 'select',
            'name'      => 'category_id', 
            'entity'    => 'category',  
            'attribute' => 'name',
            'model'     => 'App\Models\Category'
        ]);
        $this->crud->addColumn([
            'type'      => 'select',
            'name'      => 'unit_id', 
            'entity'    => 'unit',  
            'attribute' => 'name',
            'model'     => 'App\Models\Unit'
        ]);
        $this->crud->addColumn(['name' => 'unit_price', 'label' => "Unit Price", 'type' => 'Text']);
        $this->crud->addColumn(['name' => 'qty', 'label' => "QTY", 'type' => 'Text']);
        $this->crud->addColumn(['name' => 'discount', 'label' => "Discount", 'type' => 'Text']);
    }

    protected function setupCreateOperation()
    {
        $this->crud->setValidation(ProductRequest::class);

        // TODO: remove setFromDb() and manually define Fields
        // $this->crud->setFromDb();
        $this->crud->addField(['name' => 'product_code', 'type' => 'text', 'label' => 'Product Code']);
        $this->crud->addField(['name' => 'name', 'type' => 'text', 'label' => 'Name']);
        $this->crud->addField([  // Select2
            'label'     => "Category",
            'type'      => 'select2',
            'name'      => 'category_id', // the db column for the foreign key
            'entity'    => 'category', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            // optional
            'model'     => "App\Models\Category", 
            'default'   => null, // set the default value of the select2
            'options'   => (function ($query) {
                 return $query->orderBy('name', 'ASC')->get();
            }),
        ]);
        $this->crud->addField([  // Select2
            'label'     => "Unit",
            'type'      => 'select2',
            'name'      => 'unit_id', // the db column for the foreign key
            'entity'    => 'unit', // the method that defines the relationship in your Model
            'attribute' => 'name', // foreign key attribute that is shown to user
            // optional
            'model'     => "App\Models\Unit", 
            'default'   => null, // set the default value of the select2
            'options'   => (function ($query) {
                 return $query->orderBy('name', 'ASC')->get();
            })
        ]);
        $this->crud->addField([   // Number
            'name'      => 'unit_price',
            'label'     => 'Unit Price',
            'type'      => 'number',
            // optionals
            'attributes'=> ["step" => "any"],
            'prefix'    => "$",
            // 'suffix' => ".00",
        ]);
        $this->crud->addField([   // Number
            'name'          => 'qty',
            'label'         => 'QTY',
            'type'          => 'number',
            // optionals
            // 'attributes'    => ["step" => "any"],
            // 'prefix'        => "$",
            // 'suffix' => ".00",
        ]);

        $this->crud->addField([   // Number
            'name' => 'discount',
            'label' => 'Discount',
            'type' => 'number',
            // optionals
            'attributes' => ["step" => "any"],
            'prefix' => "%",
            // 'suffix' => ".00",
        ]);

        $this->crud->addField([
            'label' => "Profile Image",
            'name' => "image",
            'type' => 'image',
            'upload' => true,
            'crop' => true, // set to true to allow cropping, false to disable
            'aspect_ratio' => 1, // ommit or set to 0 to allow any aspect ratio
            // 'disk' => 's3_bucket', // in case you need to show images from a different disk
            // 'prefix' => 'uploads/images/profile_pictures/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
        ]);

        $this->crud->addField([   // Summernote
            'name' => 'description',
            'label' => 'Description',
            'type' => 'summernote',
            // 'options' => [], // easily pass parameters to the summernote JS initialization 
        ]);
        
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
