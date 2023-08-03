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

    /**
     * Get the user associated with the AsCustomer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Models\User::class, 'id', 'user_id');
    }
}