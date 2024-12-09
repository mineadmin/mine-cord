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
use Mine\Support\Filesystem;
use Nette\Utils\FileSystem as BaseFileSystem;

describe('copy', function () {
    beforeEach(function () {
        $this->sourceDir = sys_get_temp_dir() . '/source_' . uniqid();
        $this->targetDir = sys_get_temp_dir() . '/target_' . uniqid();

        BaseFileSystem::createDir($this->sourceDir);
        BaseFileSystem::createDir($this->targetDir);
    });

    afterEach(function () {
        BaseFileSystem::delete($this->sourceDir);
        BaseFileSystem::delete($this->targetDir);
    });

    it('can copy nested directories while preserving existing files', function () {
        BaseFileSystem::createDir($this->sourceDir . '/subDir');
        file_put_contents($this->sourceDir . '/file1.txt', 'content1');
        file_put_contents($this->sourceDir . '/subDir/file2.txt', 'content2');

        BaseFileSystem::createDir($this->targetDir . '/subDir');
        file_put_contents($this->targetDir . '/subDir/existing.txt', 'existing content');

        Filesystem::copy($this->sourceDir, $this->targetDir);

        expect($this->targetDir . '/subDir')->toBeDirectory()
            ->and($this->targetDir . '/file1.txt')->toBeFile()
            ->and($this->targetDir . '/subDir/file2.txt')->toBeFile()
            ->and($this->targetDir . '/subDir/existing.txt')->toBeFile()
            ->and(file_get_contents($this->targetDir . '/file1.txt'))->toBe('content1')
            ->and(file_get_contents($this->targetDir . '/subDir/file2.txt'))->toBe('content2')
            ->and(file_get_contents($this->targetDir . '/subDir/existing.txt'))->toBe('existing content')
            ->and($this->sourceDir)->not->toBeDirectory();
    });

    it('can copy without deleting source directory', function () {
        file_put_contents($this->sourceDir . '/test.txt', 'test content');

        Filesystem::copy($this->sourceDir, $this->targetDir, false);

        expect($this->sourceDir)->toBeDirectory()
            ->and($this->sourceDir . '/test.txt')->toBeFile()
            ->and($this->targetDir . '/test.txt')->toBeFile()
            ->and(file_get_contents($this->targetDir . '/test.txt'))->toBe('test content');
    });
});
