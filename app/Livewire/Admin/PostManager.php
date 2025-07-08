<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class PostManager extends Component
{
    use WithPagination;

    public $search = '';
    public $filter = 'all';
    public $showForm = false;
    public $editingPost = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filter' => ['except' => 'all'],
    ];

    public function mount()
    {
        if (!auth()->user()->is_admin) {
            abort(403);
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function createPost()
    {
        $this->editingPost = null;
        $this->showForm = true;
    }

    public function editPost(Post $post)
    {
        $this->editingPost = $post;
        $this->showForm = true;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->editingPost = null;
    }

    public function deletePost(Post $post)
    {
        $post->delete();
        session()->flash('success', 'Post deleted successfully.');
    }

    public function togglePublished(Post $post)
    {
        $post->update([
            'is_published' => !$post->is_published,
            'published_at' => !$post->is_published ? now() : null,
        ]);

        $status = $post->is_published ? 'published' : 'unpublished';
        session()->flash('success', "Post {$status} successfully.");
    }

    public function render()
    {
        $query = Post::with('user');

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
        }

        switch ($this->filter) {
            case 'published':
                $query->where('is_published', true);
                break;
            case 'draft':
                $query->where('is_published', false);
                break;
            case 'mine':
                $query->where('user_id', auth()->id());
                break;
        }

        $posts = $query->latest()->paginate(10);

        return view('livewire.admin.post-manager', compact('posts'));
    }
}