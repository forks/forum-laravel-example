<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Events\CommentPosted;

class Comment extends Component
{
    public $post;
    public $replies;
    public $comment;

    public $reply;

    protected $rules = [
        'comment.body' => '',
        'reply' => 'required|min:5',
    ];

    public function mount($comment)
    {
        $this->comment = $comment;
        $this->post = $this->comment->post;
        $this->replies = $this->comment->replies()
            ->with('user')
            ->orderBy('created_at')
            ->withTrashed()
            ->get();
    }

    public function addComment()
    {
        $comment = $this->post->comments()->save(
            auth()->user()->comments()->make(
                $this->comment->replies()->make([
                    'body' => $this->validate()['reply']
                ])->attributesToArray()
            )
        );

        CommentPosted::dispatch($comment);

        $this->replies = $this->comment->replies()
            ->with('user')
            ->orderBy('created_at')
            ->withTrashed()
            ->get();

        $this->reset(['reply']);
    }

    public function editComment()
    {
        $this->comment->save();
    }

    public function deleteComment()
    {
        $this->comment->delete();
    }

    public function upvote()
    {
        $this->comment->upvote(auth()->user());
    }

    public function downvote()
    {
        $this->comment->downvote(auth()->user());
    }

    public function render()
    {
        return view('livewire.comment');
    }
}
