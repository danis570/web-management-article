<?php

namespace app\Service;

use app\Domain\Session;
use app\Repository\SessionRepository;
use Exception;

class SessionService
{
    private SessionRepository $sessionRepository;

    private const COOKIE_NAME = 'X-IN-D';

    // Session berlaku selama 30 hari
    private const SESSION_LIFETIME = 60 * 60 * 24 * 30;

    public function __construct(SessionRepository $sessionRepository)
    {
        $this->sessionRepository = $sessionRepository;
    }

    /**
     * Membuat session baru untuk user.
     */
    public function create(int $userId): Session
    {
        if ($userId <= 0) {
            throw new Exception('User ID is invalid.');
        }

        $session = new Session();

        // Buat ID random 64 karakter
        $session->id = bin2hex(random_bytes(32));

        $session->userId = $userId;

        $session->ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;

        $session->userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        $session->lastActivity = date('Y-m-d H:i:s');

        $session->expiresAt = date(
            'Y-m-d H:i:s',
            time() + self::SESSION_LIFETIME
        );

        $this->sessionRepository->save($session);

        return $session;
    }

    /**
     * Menyimpan session ID ke cookie browser.
     */
    public function setCookie(string $sessionId): void
    {
        setcookie(
            self::COOKIE_NAME,
            $sessionId,
            [
                'expires' => time() + self::SESSION_LIFETIME,
                'path' => '/',
                'httponly' => true,
                'secure' => !empty($_SERVER['HTTPS'])
                    && $_SERVER['HTTPS'] !== 'off',
                'samesite' => 'Lax'
            ]
        );
    }

    /**
     * Mengambil session ID dari cookie.
     */
    public function getSessionIdFromCookie(): ?string
    {
        return $_COOKIE[self::COOKIE_NAME] ?? null;
    }

    /**
     * Mengambil session berdasarkan cookie.
     */
    public function getCurrentSession(): ?Session
    {
        $sessionId = $this->getSessionIdFromCookie();

        if (!$sessionId) {
            return null;
        }

        $session = $this->sessionRepository->findById($sessionId);

        if (!$session) {
            return null;
        }

        // Session sudah expired
        if (strtotime($session->expiresAt) <= time()) {
            $this->sessionRepository->deleteById($session->id);

            $this->deleteCookie();

            return null;
        }

        return $session;
    }

    /**
     * Mengecek apakah user sedang login.
     */
    public function isLoggedIn(): bool
    {
        return $this->getCurrentSession() !== null;
    }

    /**
     * Mengambil user ID dari session yang sedang aktif.
     */
    public function getCurrentUserId(): ?int
    {
        $session = $this->getCurrentSession();

        if (!$session) {
            return null;
        }

        return $session->userId;
    }

    /**
     * Memperbarui waktu aktivitas session.
     */
    public function refreshActivity(): void
    {
        $session = $this->getCurrentSession();

        if (!$session) {
            return;
        }

        $this->sessionRepository->updateLastActivity(
            $session->id,
            date('Y-m-d H:i:s')
        );
    }

    /**
     * Menghapus semua session milik user.
     *
     * Digunakan misalnya setelah user mengganti password,
     * sehingga semua device harus login kembali.
     */

    public function revokeAllByUserId(int $userId): void
    {
        if ($userId <= 0) {
            throw new Exception('User ID is invalid.');
        }

        // Hanya hapus semua session milik user tersebut
        $this->sessionRepository->deleteByUserId($userId);
    }



    /**
     * Logout session yang sedang digunakan.
     */
    public function logout(): void
    {
        $sessionId = $this->getSessionIdFromCookie();

        if ($sessionId) {
            $this->sessionRepository->deleteById($sessionId);
        }

        $this->deleteCookie();
    }

    /**
     * Menghapus cookie session dari browser.
     */
    private function deleteCookie(): void
    {
        setcookie(
            self::COOKIE_NAME,
            '',
            [
                'expires' => time() - 3600,
                'path' => '/',
                'httponly' => true,
                'secure' => !empty($_SERVER['HTTPS'])
                    && $_SERVER['HTTPS'] !== 'off',
                'samesite' => 'Lax'
            ]
        );
    }

    /**
     * Membersihkan session yang sudah expired.
     */
    public function cleanupExpired(): void
    {
        $this->sessionRepository->deleteExpired();
    }
}