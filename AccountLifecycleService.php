<?php

namespace MultipleLocalAuth;

/**
 * Regras de ciclo de vida de conta isoladas do Provider (testáveis sem App):
 * - troca de senha forçada por admin;
 * - login barrado para conta na lixeira + confirmação de restauração via e-mail.
 */
class AccountLifecycleService
{
    public const FORCE_PASSWORD_CHANGE_METADATA = 'forcePasswordChange';
    public const PENDING_TRASH_RESTORE_CONFIRM_METADATA = 'pendingTrashRestoreConfirm';
    public const PENDING_TRASH_RESTORE_SESSION_KEY = 'pendingTrashRestoreUserId';

    /** Espelha MapasCulturais\Entity::STATUS_TRASH */
    public const STATUS_TRASH = -10;

    /** Entidades que User::delete() manda para a lixeira junto com o usuário. */
    public const RELATED_ENTITY_TYPES = ['agents', 'spaces', 'projects', 'opportunities', 'events'];

    public static function mustChangePassword(?string $forcePasswordChangeMetadata): bool
    {
        return $forcePasswordChangeMetadata === '1';
    }

    /**
     * Pré-condições da ação admin forcePasswordChange().
     * Retorna código de erro ou null se ok: 'permission' | 'not_found' | null
     */
    public static function forcePasswordChangeError(bool $isAdmin, bool $userExists): ?string
    {
        if (!$isAdmin) {
            return 'permission';
        }

        if (!$userExists) {
            return 'not_found';
        }

        return null;
    }

    /**
     * Pré-condição de doForcedPasswordChange(): precisa haver flag pendente.
     */
    public static function canDoForcedPasswordChange(bool $mustChangePassword): bool
    {
        return $mustChangePassword;
    }

    /**
     * Login com senha correta + conta na lixeira: não autentica; pede confirmação de restore.
     */
    public static function shouldOfferTrashRestore(bool $hasOtherErrors, int $userStatus, int $trashStatus = self::STATUS_TRASH): bool
    {
        return !$hasOtherErrors && $userStatus === $trashStatus;
    }

    /**
     * @return array{success: false, accountInTrash: true, profileName: string, errors: array}
     */
    public static function buildAccountInTrashLoginResult(string $profileName, array $errors = []): array
    {
        return [
            'success' => false,
            'accountInTrash' => true,
            'profileName' => $profileName,
            'errors' => $errors,
        ];
    }

    /**
     * Pré-condições de confirmRestoreAccount().
     * Retorna 'expired' | 'not_trash' | null (ok).
     *
     * @param object|null $user objeto com propriedade status (User ou stub)
     */
    public static function confirmRestoreError(?int $sessionUserId, $user, int $trashStatus = self::STATUS_TRASH): ?string
    {
        if (!$sessionUserId) {
            return 'expired';
        }

        if (!$user || (int) $user->status !== $trashStatus) {
            return 'not_trash';
        }

        return null;
    }

    public static function shouldRestoreOnEmailConfirm(?string $pendingTrashRestoreConfirmMetadata): bool
    {
        return $pendingTrashRestoreConfirmMetadata === '1';
    }

    public static function relatedEntityTypesToRestore(): array
    {
        return self::RELATED_ENTITY_TYPES;
    }

    public static function entityShouldBeUndeleted(int $entityStatus, int $trashStatus = self::STATUS_TRASH): bool
    {
        return $entityStatus === $trashStatus;
    }

    public static function storePendingTrashRestore(int $userId): void
    {
        $_SESSION[self::PENDING_TRASH_RESTORE_SESSION_KEY] = $userId;
    }

    public static function getPendingTrashRestoreUserId(): ?int
    {
        $userId = $_SESSION[self::PENDING_TRASH_RESTORE_SESSION_KEY] ?? null;
        if ($userId === null || $userId === '') {
            return null;
        }

        return (int) $userId;
    }

    public static function clearPendingTrashRestore(): void
    {
        unset($_SESSION[self::PENDING_TRASH_RESTORE_SESSION_KEY]);
    }
}
