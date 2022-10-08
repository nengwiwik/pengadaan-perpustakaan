<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GroceryCrud\Core\GroceryCrud;

class GroceryCrudController extends Controller
{
    public function _getGroceryCrudEnterprise()
    {
        $database = $this->_getDatabaseConnection();
        $config = config('grocerycrud');

        $crud = new GroceryCrud($config, $database);

        return $crud;
    }

    public function _showOutput($output, $title = "Page", $view = 'grocery')
    {
        if ($output->isJSONResponse) {
            return response($output->output, 200)
                ->header('Content-Type', 'application/json')
                ->header('charset', 'utf-8');
        }

        $css_files = $output->css_files;
        $js_files = $output->js_files;
        $output = $output->output;

        return view($view, [
            'output' => $output,
            'css_files' => $css_files,
            'js_files' => $js_files,
            'title' => $title,
        ]);
    }

    public function _getDatabaseConnection()
    {
        return [
            'adapter' => [
                'driver' => 'Pdo_Mysql',
                'database' => env('DB_DATABASE'),
                'username' => env('DB_USERNAME'),
                'password' => env('DB_PASSWORD'),
                'charset' => 'utf8'
            ]
        ];
    }
}
