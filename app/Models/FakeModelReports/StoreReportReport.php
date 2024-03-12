<?php

namespace App\FakeModelReports\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreReportReport extends Model
{
    use \Sushi\Sushi;
    public $incrementing = false;

    protected $schema = [
        'none' => 'string',
    ];

    public function getRows()
    {
        return [];
    }
}
