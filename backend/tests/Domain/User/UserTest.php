<?php

declare(strict_types=1);

namespace Tests\Domain\User;

use App\Domain\User\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function userProvider(): array
    {
        return [
            [1, 'bill.gates', 'Bill Gates'],
            [2, 'steve.jobs', 'Steve Jobs'],
            [3, 'mark.zuckerberg', 'Mark Zuckerberg'],
            [4, 'evan.spiegel', 'Evan Spiegel'],
            [5, 'jack.dorsey', 'Jack Dorsey'],
        ];
    }

    /**
     * @dataProvider userProvider
     * @param int    $id
     * @param string $username
     * @param string $name
     */
    public function testGetters(int $id, string $username, string $name)
    {
        $user = new User($id, $username, $name);

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($name, $user->getName());
    }

    /**
     * @dataProvider userProvider
     * @param int    $id
     * @param string $username
     * @param string $name
     */
    public function testJsonSerialize(int $id, string $username, string $name)
    {
        $user = new User($id, $username, $name);

        $expectedPayload = json_encode([
            'id' => $id,
            'username' => $username,
            'name' => $name,
        ]);

        $this->assertEquals($expectedPayload, json_encode($user));
    }
}
