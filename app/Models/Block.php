<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $fillable = ['data', 'hash', 'previous_hash'];

    public static function createBlock($data)
    {
        $lastBlock = Block::orderBy('id', 'desc')->first();
        $prev = $lastBlock ? $lastBlock->hash : "0";

        $hash = hash("sha256", $data . $prev . now());

        return Block::create([
            'data' => $data,
            'hash' => $hash,
            'previous_hash' => $prev
        ]);
    }
}
