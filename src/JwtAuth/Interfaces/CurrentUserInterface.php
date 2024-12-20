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

interface CurrentUserInterface
{
    public function user(): ?UserInterface;

    public function refresh(): array;

    public function id(): int;

    public function isSuperAdmin(): bool;
}
