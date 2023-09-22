<?php

namespace App\Models;

use App\Enum\UserActions;
use App\Events\UserAction;
use App\Models\Application\Application;
use App\Models\Application\PaymentDetail;
use App\Services\ApplicationService;
use App\Services\ConfigOptionService;
use App\Services\UserActionService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CurrencyValue extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'currency_values';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public $fillable = [
        'currency_id',
        'date',
        'value',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        //
    ];

    protected static function booted()
    {
        parent::boot();

        static::updating(function (CurrencyValue $currencyValue) {
            if($currencyValue->isDirty('value'))
            {
                $key = $currencyValue->currency->char_code . '_' . $currencyValue->date;
                cache()->forget($key);
            }
        });

        static::deleting(function (CurrencyValue $currencyValue) {
            $key = $currencyValue->currency->char_code . '_' . $currencyValue->date;
            cache()->forget($key);
        });

    }

    /**
     * @return HasOne
     */
    public function currency(): HasOne
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }
}
