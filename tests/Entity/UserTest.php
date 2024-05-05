<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\User;

class UserTest extends TestCase
{
    public function testTheAutomaticApiTokenSettingWhenAnUserIsCreated(): void
    {
        $user = new User();
        $this->assertNotNull($user->getApiToken());
    }

    public function testThanAnUserHasAtLeastOneRoleUser(): void
    {
        $user = new User();
        $this->assertContains("ROLE_USER", $user->getRoles());
    }
}