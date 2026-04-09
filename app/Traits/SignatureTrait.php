<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

trait SignatureTrait
{
    /**
     * Verifies the electronic signature and logs it.
     * 
     * @param string $password The password to verify.
     * @param string $reason The reason for the signature.
     * @throws \Exception If signature is invalid.
     */
    public function verifyElectronicSignature(string $password, string $reason)
    {
        $user = Auth::user();

        if (!Hash::check($password, $user->password)) {
            // Log failed attempt if needed
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'FALLO FIRMA ELECTRÓNICA',
                'model_type' => get_class($this),
                'model_id' => $this->id ?? null,
                'reason' => "Contraseña incorrecta para: $reason",
                'ip_address' => Request::ip(),
            ]);

            throw new \Exception('La firma electrónica ha fallado: contraseña incorrecta.');
        }

        // Log successful signature
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'FIRMA ELECTRÓNICA APLICADA',
            'model_type' => get_class($this),
            'model_id' => $this->id ?? null,
            'reason' => $reason,
            'ip_address' => Request::ip(),
        ]);

        return true;
    }
}
