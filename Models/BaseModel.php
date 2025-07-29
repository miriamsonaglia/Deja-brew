<?php
    namespace App\Models;
    require_once 'vendor/autoload.php';
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Factories\HasFactory;

    class BaseModel extends Model
    {
        use HasFactory;
        public $timestamps = false;
    }