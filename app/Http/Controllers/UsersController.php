<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\User;

class UsersController extends Controller
{
    public function destroy($id) {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('voyager.users.index')->with(['message' => 'Usuario eliminado correctamente.', 'alert-type' => 'success']);
    }

    public function restore($id) {
        User::where('id', $id)->withTrashed()->update([
            'deleted_at' => NULL
        ]);
        return redirect()->route('voyager.users.index')->with(['message' => 'Usuario restaurado correctamente.', 'alert-type' => 'success']);
    }
}
