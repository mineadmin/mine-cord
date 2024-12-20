<?php

declare(strict_types=1);
/**
 * This file is part of MineAdmin.
 *
 * @link     https://www.mineadmin.com
 * @document https://doc.mineadmin.com
 * @contact  root@imoi.cn
 * @license  https://github.com/mineadmin/MineAdmin/blob/master/LICENSE
 */

namespace Mine\JwtAuth\Interfaces;

use Hyperf\Collection\Collection;
use Hyperf\Database\Model\Events\Creating;
use Hyperf\Database\Model\Events\Deleted;
use Hyperf\Database\Model\Relations\BelongsToMany;

interface UserInterface
{
    public function roles(): BelongsToMany;

    public function verifyPassword(string $password): bool;

    public function resetPassword(): void;

    public function isSuperAdmin(): bool;
}
