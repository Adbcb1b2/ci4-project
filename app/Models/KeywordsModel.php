<?php

namespace App\Models;

use CodeIgniter\Model;

class KeywordsModel extends Model
{
    protected $table = 'keywords';
    protected $allowedFields = ['keyword'];

    /**
     * Get keyword suggestions based on a partial term
     *
     * @param string $term
     * @return array
     */
    public function getSuggestions($term)
    {
        return $this->like('keyword', $term)
        ->limit(5)
        ->findAll();
    }
}