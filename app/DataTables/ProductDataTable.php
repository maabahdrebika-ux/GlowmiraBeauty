<?php

namespace App\DataTables;

use App\Models\Product;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'products.action')
            ->addColumn('grade', function ($product) {
                $grades = $product->grades;
                if ($grades->isNotEmpty()) {
                    return $grades->map(function ($grade) {
                        // Store the "namee" value once to avoid repetition.
                        $gradeNamee = isset($grade->namee) ? $grade->namee : '#666';
                        $color = $gradeNamee;
                        return '<span style="background-color: '.$color.'; color: #fff; padding: 5px; border-radius: 3px; margin-right:3px;">'.$grade->name.' ('.$gradeNamee.')</span>';
                    })->implode(' ');
                } else {
                    return 'لايوجد';
                }
            });
    }

    public function query(Product $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->addAction(['width' => '80px'])
                    ->parameters([
                        'dom' => 'Bfrtip',
                        'buttons' => ['excel', 'csv', 'pdf', 'print', 'reset', 'reload'],
                    ]);
    }

    protected function getColumns()
    {
        return [
            'id',
            'name',
            'price',
            'created_at',
            'updated_at',
        ];
    }

    protected function filename()
    {
        return 'Products_' . date('YmdHis');
    }
}