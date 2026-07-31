<?php

namespace MultipleLocalAuth;

use MapasCulturais\App;
use MapasCulturais\Entities\User;

/**
 * Regras de conta Gov.br isoladas do core:
 * - matching de identidade por CPF;
 * - e-mail único em usr.email (requisito do core User::getValidations);
 * - conflito de e-mail na criação → coleta de e-mail alternativo na UI.
 */
class GovBrAccountService
{
    public const SESSION_PENDING_KEY = 'govbr.pendingRegistration';

    public static function isGovBrProvider(?string $provider): bool
    {
        return strtolower((string) $provider) === 'govbr';
    }

    public static function normalizeEmail(?string $email): ?string
    {
        if ($email === null) {
            return null;
        }

        $email = trim(mb_strtolower($email));
        return $email === '' ? null : $email;
    }

    public static function isValidEmail(?string $email): bool
    {
        $email = self::normalizeEmail($email);
        if ($email === null) {
            return false;
        }

        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function maskCpf(?string $cpf): ?string
    {
        if ($cpf === null || $cpf === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $cpf);
        if (strlen($digits) !== 11) {
            return null;
        }

        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $digits);
    }

    public static function extractCpfFromResponse(array $response): ?string
    {
        $raw = $response['auth']['raw'] ?? [];
        if (is_object($raw)) {
            $raw = (array) $raw;
        }

        $cpf = $raw['cpf'] ?? $raw['sub'] ?? ($response['auth']['info']['cpf'] ?? null);
        return self::maskCpf(is_string($cpf) ? $cpf : null);
    }

    public static function extractEmailFromResponse(array $response): ?string
    {
        return self::normalizeEmail($response['auth']['info']['email'] ?? null);
    }

    /**
     * @param callable|null $emailExistsChecker fn(string $email): bool
     */
    public static function emailExists(string $email, ?callable $emailExistsChecker = null): bool
    {
        $email = self::normalizeEmail($email);
        if ($email === null) {
            return false;
        }

        if ($emailExistsChecker) {
            return (bool) $emailExistsChecker($email);
        }

        $app = App::i();
        $query = $app->em->createQuery(
            'SELECT u.id FROM MapasCulturais\Entities\User u WHERE LOWER(u.email) = :email'
        );
        $query->setParameter('email', $email);
        $query->setMaxResults(1);

        return (bool) $query->getOneOrNullResult();
    }

    /**
     * Conta Gov.br nova com e-mail do GOV já usado por outro usr.
     *
     * @param callable|null $emailExistsChecker fn(string $email): bool
     */
    public static function hasEmailConflictOnCreate(array $response, ?callable $emailExistsChecker = null): bool
    {
        if (!self::isGovBrProvider($response['auth']['provider'] ?? null)) {
            return false;
        }

        $email = self::extractEmailFromResponse($response);
        if ($email === null) {
            // Sem e-mail verificado no GOV: também exige coleta na UI.
            return true;
        }

        return self::emailExists($email, $emailExistsChecker);
    }

    public static function applyEmailToResponse(array $response, string $email): array
    {
        $email = self::normalizeEmail($email);
        if ($email === null) {
            throw new \InvalidArgumentException('E-mail inválido.');
        }

        $response['auth']['info']['email'] = $email;
        return $response;
    }

    public static function storePendingRegistration(array $response): void
    {
        $_SESSION[self::SESSION_PENDING_KEY] = $response;
    }

    public static function getPendingRegistration(): ?array
    {
        $pending = $_SESSION[self::SESSION_PENDING_KEY] ?? null;
        return is_array($pending) ? $pending : null;
    }

    public static function clearPendingRegistration(): void
    {
        unset($_SESSION[self::SESSION_PENDING_KEY]);
    }

    /**
     * Valida e-mail alternativo informado na UI.
     * Retorna lista de erros (vazia = ok).
     *
     * @param callable|null $emailExistsChecker fn(string $email): bool
     * @return string[]
     */
    public static function validateAlternateEmail(?string $email, ?callable $emailExistsChecker = null): array
    {
        $errors = [];
        $email = self::normalizeEmail($email);

        if ($email === null || !self::isValidEmail($email)) {
            $errors[] = 'Informe um e-mail válido.';
            return $errors;
        }

        if (self::emailExists($email, $emailExistsChecker)) {
            $errors[] = 'Este e-mail já está em uso. Informe outro e-mail.';
        }

        return $errors;
    }

    public static function profileCpfMatchesGovBr(User $user, array $response, string $metadataFieldCpf): bool
    {
        $govCpf = self::extractCpfFromResponse($response);
        if ($govCpf === null || !$user->profile) {
            return false;
        }

        $profileCpf = self::maskCpf((string) ($user->profile->$metadataFieldCpf ?? ''));
        if ($profileCpf === null) {
            $profileCpf = self::maskCpf((string) ($user->profile->cpf ?? ''));
        }

        return $profileCpf !== null && $profileCpf === $govCpf;
    }
}
