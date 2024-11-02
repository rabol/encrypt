<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class GenerateRsaKeysSeeder extends Seeder
{
    public function run(): void
    {
        // This seeder creates the needed keys locally
        // Define paths for storing the keys
        $path = 'keys/';
        $serverPrivateKeyPath = $path.'server_private_key.pem';
        $serverPublicKeyPath = $path.'server_public_key.pem';
        $clientPrivateKeyPath = $path.'client_private_key.pem';
        $clientPublicKeyPath = $path.'client_public_key.pem';

        // Create the directory if it doesn't exist
        if (! Storage::exists($path)) {
            Storage::makeDirectory($path);
        }

        // Generate the server keys
        $serverKeys = $this->generateRsaKeyPair();
        Storage::put($serverPrivateKeyPath, $serverKeys['private_key']);
        Storage::put($serverPublicKeyPath, $serverKeys['public_key']);

        // Generate the client keys
        $clientKeys = $this->generateRsaKeyPair();
        Storage::put($clientPrivateKeyPath, $clientKeys['private_key']);
        Storage::put($clientPublicKeyPath, $clientKeys['public_key']);

        $this->command->info('RSA keys generated and saved to storage/app/keys/');
    }

    /**
     * Generate an RSA key pair.
     *
     * @return array An array with 'private_key' and 'public_key' entries.
     */
    private function generateRsaKeyPair(): array
    {
        // Generate private key
        $keyConfig = [
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];
        $privateKeyResource = openssl_pkey_new($keyConfig);

        // Extract the private key
        openssl_pkey_export($privateKeyResource, $privateKey);

        // Extract the public key
        $publicKeyDetails = openssl_pkey_get_details($privateKeyResource);
        $publicKey = $publicKeyDetails['key'];

        // No need to explicitly free the key resource in PHP 8+

        return [
            'private_key' => $privateKey,
            'public_key' => $publicKey,
        ];
    }
}
