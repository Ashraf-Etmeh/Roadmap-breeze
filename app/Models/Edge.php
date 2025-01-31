<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Edge extends Model
{
    use HasFactory;

    public function fromNode(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'from_node_id');
    }
    public function toNode(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'to_node_id');
    }
}
