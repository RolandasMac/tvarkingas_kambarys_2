<?php

// app/Http/Responses/CustomLoginResponse.php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class CustomLoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        // Nukreipimas po prisijungimo
        return redirect()->intended('/dashboard');
    }
}
