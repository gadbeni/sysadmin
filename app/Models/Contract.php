<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'person_id',
        'program_id',
        'cargo_id',
        'unidad_adminstrativa_id',
        'user_id',
        'salary',
        'start',
        'finish',
        'date_invitation',
        'date_limit_invitation',
        'date_response',
        'date_statement',
        'date_memo',
        'workers_memo',
        'date_memo_res',
        'date_note',
        'date_report',
        'table_report',
        'details_report',
        'date_autorization',
        'certification_poa',
        'certification_pac',
        'status',
    ];

    /**
     * Get the user that owns the Contract
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that owns the Contract
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person()
    {
        return $this->belongsTo(Person::class);
    }

    /**
     * Get the user that owns the Contract
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}
