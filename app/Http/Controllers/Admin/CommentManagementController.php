<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::query()->with(['user', 'video'])->latest();

        if ($search = trim((string) $request->input('search'))) {
            $query->where('body', 'like', '%' . $search . '%');
        }

        if ($request->input('moderation') === 'recent') {
            $query->whereDate('created_at', '>=', now()->subDays(2));
        }

        $comments = $query->paginate(20)->withQueryString();

        return view('admin.comments.index', compact('comments'));
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('status', 'Comment deleted successfully.');
    }
}
