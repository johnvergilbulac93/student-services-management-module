<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['filename', 'user_id', 'summary_json'])]
class ImportLog extends Model
{
    //
}
