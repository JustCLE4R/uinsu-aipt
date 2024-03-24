<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dokumen extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function search($term, $kriteria = null, $tipe = null)
    {
        $query = $this->where(function($query) use ($term) {
                    $query  ->where('nama', 'like', '%'.$term.'%')
                            ->orWhere('sub_kriteria', 'like', '%'.$term.'%')
                            ->orWhere('catatan', 'like', '%'.$term.'%');
                });
    
        if ($kriteria) {
            $query->where('kriteria', $kriteria);
        }
    
        if ($tipe) {
            $query->where('tipe', $tipe);
        }
    
        $query->orderBy('created_at', 'desc');
    
        $results = $query->paginate(6)->withQueryString();

        // $results->appends(['result' => $term, 'kriteria' => $kriteria, 'tipe' => $tipe]);
        // $results->appends(request()->input());

        return $results;
    }
    
    
    
}
