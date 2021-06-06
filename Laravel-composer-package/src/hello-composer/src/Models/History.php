<?php

namespace maximuse\HelloWorld\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
  use HasFactory;

  // Mendisable proteksi untuk mengassign dari laravel
  protected $guarded = [];
}