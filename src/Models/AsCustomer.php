<?php

namespace Ernandesrs\Lapipay\Models;

use Illuminate\Database\Eloquent\Model;

class AsCustomer extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'as_customers';

    /**
     * Fillables
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'gateway',
        'customer_id'
    ];
}