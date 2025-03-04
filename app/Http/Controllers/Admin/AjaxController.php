<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    public function user(Request $request, UserRepository $userRepository): \Illuminate\Http\JsonResponse
    {
        try {
            $users   = $userRepository->findUsers([
                'q'       => $request->q,
                'take'    => 20,
                'role_id' => $request->role_id,
            ]);
            $options = [];
            foreach ($users as $item) {
                $options[] = [
                    'text' => $item->name,
                    'id'   => $item->id,
                ];
            }

            return response()->json($options);
        } catch (\Exception $e) {
            return response()->json(['error' => __('something_went_wrong_please_try_again')]);
        }
    }

}
