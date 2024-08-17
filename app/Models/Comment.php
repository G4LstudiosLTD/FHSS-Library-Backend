<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Define the table name if it does not follow the Laravel naming convention
    protected $table = 'comments';

    // Specify the fields that are mass assignable
    protected $fillable = [
        'school_id',
        'topic_id',
        'user_id',
        'comment',
        'type',
        'comment_id',
    ];

    /**
     * Get the user that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the topic that the comment belongs to.
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Get the parent comment if this comment is a reply.
     */
    public function parentComment()
    {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    /**
     * Get the replies for the comment.
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'comment_id');
    }
    
    public function views()
    {
        return $this->hasMany(CommentView::class);
    }
    
}
