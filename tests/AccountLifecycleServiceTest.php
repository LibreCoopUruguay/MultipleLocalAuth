<?php

namespace MultipleLocalAuth\Tests;

use MultipleLocalAuth\AccountLifecycleService;
use PHPUnit\Framework\TestCase;

/**
 * Testes unitários das regras de troca de senha forçada e recuperação de conta na lixeira.
 * Rodar a partir deste plugin:
 *   composer install
 *   vendor/bin/phpunit
 */
class AccountLifecycleServiceTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
    }

    // --- Force password change ---

    public function testMustChangePasswordOnlyWhenFlagIsOne(): void
    {
        $this->assertTrue(AccountLifecycleService::mustChangePassword('1'));
        $this->assertFalse(AccountLifecycleService::mustChangePassword('0'));
        $this->assertFalse(AccountLifecycleService::mustChangePassword(null));
        $this->assertFalse(AccountLifecycleService::mustChangePassword(''));
        $this->assertFalse(AccountLifecycleService::mustChangePassword('true'));
    }

    public function testForcePasswordChangeRequiresAdmin(): void
    {
        $this->assertSame('permission', AccountLifecycleService::forcePasswordChangeError(false, true));
        $this->assertSame('permission', AccountLifecycleService::forcePasswordChangeError(false, false));
    }

    public function testForcePasswordChangeRequiresExistingUser(): void
    {
        $this->assertSame('not_found', AccountLifecycleService::forcePasswordChangeError(true, false));
    }

    public function testForcePasswordChangeOkForAdminWithUser(): void
    {
        $this->assertNull(AccountLifecycleService::forcePasswordChangeError(true, true));
    }

    public function testCanDoForcedPasswordChangeRequiresPendingFlag(): void
    {
        $this->assertTrue(AccountLifecycleService::canDoForcedPasswordChange(true));
        $this->assertFalse(AccountLifecycleService::canDoForcedPasswordChange(false));
    }

    public function testMetadataConstantsMatchRegisteredKeys(): void
    {
        $this->assertSame('forcePasswordChange', AccountLifecycleService::FORCE_PASSWORD_CHANGE_METADATA);
        $this->assertSame('pendingTrashRestoreConfirm', AccountLifecycleService::PENDING_TRASH_RESTORE_CONFIRM_METADATA);
        $this->assertSame('pendingTrashRestoreUserId', AccountLifecycleService::PENDING_TRASH_RESTORE_SESSION_KEY);
    }

    // --- Trash restore on login ---

    public function testShouldOfferTrashRestoreWhenPasswordOkAndStatusTrash(): void
    {
        $this->assertTrue(AccountLifecycleService::shouldOfferTrashRestore(
            false,
            AccountLifecycleService::STATUS_TRASH
        ));
    }

    public function testShouldNotOfferTrashRestoreWhenOtherErrorsExist(): void
    {
        $this->assertFalse(AccountLifecycleService::shouldOfferTrashRestore(
            true,
            AccountLifecycleService::STATUS_TRASH
        ));
    }

    public function testShouldNotOfferTrashRestoreWhenAccountIsActive(): void
    {
        $this->assertFalse(AccountLifecycleService::shouldOfferTrashRestore(false, 1));
        $this->assertFalse(AccountLifecycleService::shouldOfferTrashRestore(false, 0));
    }

    public function testBuildAccountInTrashLoginResultShape(): void
    {
        $errors = ['login' => []];
        $result = AccountLifecycleService::buildAccountInTrashLoginResult('Maria Silva', $errors);

        $this->assertFalse($result['success']);
        $this->assertTrue($result['accountInTrash']);
        $this->assertSame('Maria Silva', $result['profileName']);
        $this->assertSame($errors, $result['errors']);
    }

    public function testBuildAccountInTrashLoginResultWithEmptyProfileName(): void
    {
        $result = AccountLifecycleService::buildAccountInTrashLoginResult('');
        $this->assertSame('', $result['profileName']);
        $this->assertSame([], $result['errors']);
    }

    // --- confirmRestoreAccount preconditions ---

    public function testConfirmRestoreExpiredWithoutSession(): void
    {
        $user = (object) ['status' => AccountLifecycleService::STATUS_TRASH];
        $this->assertSame('expired', AccountLifecycleService::confirmRestoreError(null, $user));
        $this->assertSame('expired', AccountLifecycleService::confirmRestoreError(0, $user));
    }

    public function testConfirmRestoreNotTrashWhenUserMissingOrActive(): void
    {
        $this->assertSame(
            'not_trash',
            AccountLifecycleService::confirmRestoreError(42, null)
        );

        $active = (object) ['status' => 1];
        $this->assertSame(
            'not_trash',
            AccountLifecycleService::confirmRestoreError(42, $active)
        );
    }

    public function testConfirmRestoreOkWhenUserInTrash(): void
    {
        $trashed = (object) ['status' => AccountLifecycleService::STATUS_TRASH];
        $this->assertNull(AccountLifecycleService::confirmRestoreError(42, $trashed));
    }

    // --- Email confirm restores trash ---

    public function testShouldRestoreOnEmailConfirmOnlyWhenPendingFlagIsOne(): void
    {
        $this->assertTrue(AccountLifecycleService::shouldRestoreOnEmailConfirm('1'));
        $this->assertFalse(AccountLifecycleService::shouldRestoreOnEmailConfirm('0'));
        $this->assertFalse(AccountLifecycleService::shouldRestoreOnEmailConfirm(null));
        $this->assertFalse(AccountLifecycleService::shouldRestoreOnEmailConfirm(''));
    }

    // --- restoreUserFromTrash helpers ---

    public function testRelatedEntityTypesToRestore(): void
    {
        $types = AccountLifecycleService::relatedEntityTypesToRestore();
        $this->assertSame(
            ['agents', 'spaces', 'projects', 'opportunities', 'events'],
            $types
        );
    }

    public function testEntityShouldBeUndeletedOnlyWhenInTrash(): void
    {
        $this->assertTrue(AccountLifecycleService::entityShouldBeUndeleted(AccountLifecycleService::STATUS_TRASH));
        $this->assertFalse(AccountLifecycleService::entityShouldBeUndeleted(1));
        $this->assertFalse(AccountLifecycleService::entityShouldBeUndeleted(0));
    }

    // --- Session pending restore ---

    public function testPendingTrashRestoreSessionRoundtrip(): void
    {
        $this->assertNull(AccountLifecycleService::getPendingTrashRestoreUserId());

        AccountLifecycleService::storePendingTrashRestore(99);
        $this->assertSame(99, AccountLifecycleService::getPendingTrashRestoreUserId());
        $this->assertSame(
            99,
            $_SESSION[AccountLifecycleService::PENDING_TRASH_RESTORE_SESSION_KEY]
        );

        AccountLifecycleService::clearPendingTrashRestore();
        $this->assertNull(AccountLifecycleService::getPendingTrashRestoreUserId());
        $this->assertArrayNotHasKey(
            AccountLifecycleService::PENDING_TRASH_RESTORE_SESSION_KEY,
            $_SESSION
        );
    }
}
