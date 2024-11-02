<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Make sure the keys directory is clean before each test
    Storage::deleteDirectory('keys');
    Storage::makeDirectory('keys');
});

afterEach(function () {
    // Clean up after each test
    Storage::deleteDirectory('keys');
});

it('generates the RSA key files', function () {
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\GenerateRsaKeysSeeder']);

    // Check that the expected files were created
    expect(Storage::exists('keys/server_private_key.pem'))->toBeTrue()
        ->and(Storage::exists('keys/server_public_key.pem'))->toBeTrue()
        ->and(Storage::exists('keys/client_private_key.pem'))->toBeTrue()
        ->and(Storage::exists('keys/client_public_key.pem'))->toBeTrue();
});

it('generates valid RSA key pairs', function () {
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\GenerateRsaKeysSeeder']);

    // Load the generated keys
    $serverPrivateKey = Storage::get('keys/server_private_key.pem');
    $serverPublicKey = Storage::get('keys/server_public_key.pem');
    $clientPrivateKey = Storage::get('keys/client_private_key.pem');
    $clientPublicKey = Storage::get('keys/client_public_key.pem');

    // Test server key pair for encryption and decryption
    $data = 'Test message for server key';
    openssl_public_encrypt($data, $encryptedData, $serverPublicKey);
    openssl_private_decrypt($encryptedData, $decryptedData, $serverPrivateKey);
    expect($decryptedData)->toBe($data);

    // Test client key pair for encryption and decryption
    $data = 'Test message for client key';
    openssl_public_encrypt($data, $encryptedData, $clientPublicKey);
    openssl_private_decrypt($encryptedData, $decryptedData, $clientPrivateKey);
    expect($decryptedData)->toBe($data);
});
it('ensures server and client keys do not cross-decrypt', function () {
    Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\GenerateRsaKeysSeeder']);

    // Load the generated keys
    $serverPrivateKey = Storage::get('keys/server_private_key.pem');
    $clientPublicKey = Storage::get('keys/client_public_key.pem');

    // Encrypt with client public key
    $data = 'Message for mismatched decryption';
    openssl_public_encrypt($data, $encryptedData, $clientPublicKey);

    // Attempt to decrypt with server private key, expecting it to fail or return incorrect data
    openssl_private_decrypt($encryptedData, $decryptedData, $serverPrivateKey);

    // Check that the decrypted data does not match the original data
    expect($decryptedData)->not()->toBe($data);
});
