<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller {
    public function store(Request $request) {
        $errors = Utils::validateRequest($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        if (count($errors) > 0) {
            return response()->json(['error' => true, 'errors' => $errors], 422);
        }

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();

        return response()->json($user);
    }

    public function update(Request $request, $id) {
        $errors = Utils::validateRequest($request, [
            'name' => 'required|string|max:255',
            'password' => 'nullable|min:6',
        ]);

        if (count($errors) > 0) {
            return response()->json(['error' => true, 'errors' => $errors], 422);
        }

        $user = User::where('id', $id)->where('deleted_at')->first();

        if (!$user) {
            return $this->returnNotFound();
        }

        $user->name = $request->name;

        if (!empty($request->password)) {
            if (strlen($request->password) < 6) {
                return response()->json(['error' => true, 'errors' => ["O comprimento mínimo do campo de senha é de 6 caracteres."]], 422);
            }

            $user->password = bcrypt($request->password);
        }

        $user->save();

        return response()->json($user);
    }

    public function get(Request $request, $id) {
        $user = User::where('id', $id)->whereNull('deleted_at')->first();

        if (!$user) {
            return $this->returnNotFound();
        }

        return response()->json($user);
    }

    public function destroy(Request $request, $id) {
        $user = User::where('id', $id)->whereNull('deleted_at')->first();

        if (!$user) {
            return $this->returnNotFound();
        }

        $user->deleted_at = Carbon::now();

        $user->save();

        return response(null, 204);
    }

    public function list(Request $request) {
        $columnsToFilter = ['name'];

        $wheres = [
            'deleted_at' => null
        ];

        return response()->json(Utils::createPaginatedResult($request, User::class, $wheres, $columnsToFilter));
    }

    private function returnNotFound() {
        return response()->json(['error' => true, 'errors' => ['Usuário não encontrado.']], 404);
    }
}
