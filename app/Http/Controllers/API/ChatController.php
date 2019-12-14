<?php

namespace App\Http\Controllers\API;

use App\Consts;
use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\EmployeeConversation;
use App\Models\User;
use App\Models\UserConversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function getAllUserActive(Request $request)
    {
        $limit = $request->input('limit', Consts::DEFAULT_PER_PAGE);

        return Conversation::with('user')
            ->where('active', true)
            ->whereNull('pairing_with')
            ->paginate($limit);
    }

    public function getPreviewMessages(Request $request)
    {
        $id = $request->input('id');

        return UserConversation::where('user_id', $id)->paginate(Consts::DEFAULT_PER_PAGE);
    }

    public function getMessages(Request $request)
    {
        $limit = $request->input('limit', Consts::DEFAULT_PER_PAGE);
        $id = $request->input('id');
        $userId = Conversation::find($id)->user_id;

        $builder = UserConversation::with('user')->where('user_id', $userId)
            ->union(
                EmployeeConversation::with('user')->where('conversation_id', $id)
                    ->selectRaw("message, employee_id, 'employee' as type, created_at")
            )
            ->selectRaw("message, user_id, 'user' as type, created_at");

        $sqlQuery = $builder->toSql();
        return DB::table(DB::raw("($sqlQuery) as temp"))->mergeBindings($builder->getQuery())
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }

    public function sendMessage(Request $request)
    {
        $message = $request->input('message');
        $conversation = $request->input('id');
        $user = new EmployeeConversation([
            'conversation_id' => $conversation,
            'employee_id' => Auth::id(),
            'message' => $message
        ]);

        $user->save();

        MessageSent::dispatch($conversation, $user);
        return 'Message sent';
    }

    public function getAllUserPairing()
    {
        return User::with('conversation')->whereHas('conversation', function ($query) {
            $query->where('pairing_with', Auth::id())
                ->where('active', true);
        })->get();
    }

    public function pairWithUser(Request $request) {
        $id = $request->input('id');
        $conversation = Conversation::find($id);
        $conversation->pairing_with = Auth::id();
        $conversation->save();
        return 'Set Pair Success';
    }

    public function unPairWithUser(Request $request)
    {
        $id = $request->input('id');
        $conversation = Conversation::find($id);
        $conversation->pairing_with = null;
        $conversation->save();
        return 'Un Pair Success';
    }
}
