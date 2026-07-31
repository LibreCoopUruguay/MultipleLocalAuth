<?php

namespace MultipleLocalAuth\Tests;

use MultipleLocalAuth\GovBrAccountService;
use PHPUnit\Framework\TestCase;

/**
 * Testes unitários do fluxo Gov.br (CPF como identidade + e-mail único).
 * Rodar a partir deste plugin:
 *   composer install
 *   vendor/bin/phpunit
 */
class GovBrAccountServiceTest extends TestCase
{
    private function sampleResponse(string $cpf, ?string $email, string $provider = 'GovBr'): array
    {
        return [
            'auth' => [
                'provider' => $provider,
                'uid' => $cpf,
                'raw' => [
                    'sub' => preg_replace('/\D+/', '', $cpf),
                    'cpf' => preg_replace('/\D+/', '', $cpf),
                    'email' => $email,
                ],
                'info' => [
                    'cpf' => preg_replace('/\D+/', '', $cpf),
                    'email' => $email,
                    'name' => 'Marcia',
                    'full_name' => 'Marcia Barbosa',
                ],
            ],
        ];
    }

    public function testMaskCpf(): void
    {
        $this->assertSame('867.427.604-00', GovBrAccountService::maskCpf('86742760400'));
        $this->assertSame('867.427.604-00', GovBrAccountService::maskCpf('867.427.604-00'));
        $this->assertNull(GovBrAccountService::maskCpf('123'));
    }

    public function testNormalizeAndValidateEmail(): void
    {
        $this->assertSame('a@b.com', GovBrAccountService::normalizeEmail('  A@B.com '));
        $this->assertTrue(GovBrAccountService::isValidEmail('a@b.com'));
        $this->assertFalse(GovBrAccountService::isValidEmail('nao-email'));
        $this->assertFalse(GovBrAccountService::isValidEmail(''));
    }

    public function testExtractCpfAndEmailFromResponse(): void
    {
        $response = $this->sampleResponse('867.427.604-00', 'idealfotostudio@hotmail.com');
        $this->assertSame('867.427.604-00', GovBrAccountService::extractCpfFromResponse($response));
        $this->assertSame('idealfotostudio@hotmail.com', GovBrAccountService::extractEmailFromResponse($response));
    }

    public function testHasEmailConflictWhenEmailAlreadyExists(): void
    {
        $existing = ['idealfotostudio@hotmail.com'];
        $checker = function (string $email) use ($existing) {
            return in_array($email, $existing, true);
        };

        $response = $this->sampleResponse('867.427.604-00', 'idealfotostudio@hotmail.com');
        $this->assertTrue(GovBrAccountService::hasEmailConflictOnCreate($response, $checker));

        $free = $this->sampleResponse('867.427.604-00', 'marcia.unica@example.com');
        $this->assertFalse(GovBrAccountService::hasEmailConflictOnCreate($free, $checker));
    }

    public function testHasEmailConflictWhenGovBrEmailMissing(): void
    {
        $response = $this->sampleResponse('867.427.604-00', null);
        $this->assertTrue(GovBrAccountService::hasEmailConflictOnCreate($response, fn () => false));
    }

    public function testNoEmailConflictForNonGovBrProvider(): void
    {
        $response = $this->sampleResponse('867.427.604-00', 'idealfotostudio@hotmail.com', 'Google');
        $this->assertFalse(GovBrAccountService::hasEmailConflictOnCreate($response, fn () => true));
    }

    public function testValidateAlternateEmailRejectsExisting(): void
    {
        $checker = fn (string $email) => $email === 'ja.existe@example.com';

        $errors = GovBrAccountService::validateAlternateEmail('ja.existe@example.com', $checker);
        $this->assertNotEmpty($errors);
        $this->assertStringContainsString('já está em uso', $errors[0]);

        $ok = GovBrAccountService::validateAlternateEmail('novo@example.com', $checker);
        $this->assertSame([], $ok);
    }

    public function testValidateAlternateEmailRejectsInvalid(): void
    {
        $errors = GovBrAccountService::validateAlternateEmail('xyz', fn () => false);
        $this->assertNotEmpty($errors);
    }

    public function testApplyEmailToResponse(): void
    {
        $response = $this->sampleResponse('867.427.604-00', 'idealfotostudio@hotmail.com');
        $updated = GovBrAccountService::applyEmailToResponse($response, '  Novo@Example.com ');
        $this->assertSame('novo@example.com', $updated['auth']['info']['email']);
    }

    public function testPendingRegistrationSessionRoundtrip(): void
    {
        $_SESSION = [];
        $response = $this->sampleResponse('867.427.604-00', 'idealfotostudio@hotmail.com');

        GovBrAccountService::storePendingRegistration($response);
        $this->assertSame($response, GovBrAccountService::getPendingRegistration());

        GovBrAccountService::clearPendingRegistration();
        $this->assertNull(GovBrAccountService::getPendingRegistration());
    }

    public function testIsGovBrProvider(): void
    {
        $this->assertTrue(GovBrAccountService::isGovBrProvider('GovBr'));
        $this->assertTrue(GovBrAccountService::isGovBrProvider('govbr'));
        $this->assertFalse(GovBrAccountService::isGovBrProvider('Google'));
    }
}
