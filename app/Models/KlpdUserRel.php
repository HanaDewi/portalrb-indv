<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KlpdUserRel extends Model
{
    use HasFactory;
    protected $table = 'res_klpd_users_rel';
    public $timestamps = false;
    protected $guarded = [
        'id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, "user_id");
    }

    public function instansi()
    {
        return $this->belongsTo(KlpdInstansi::class, "instansi_id");
    }
}
