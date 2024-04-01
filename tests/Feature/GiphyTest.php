<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;

class GiphyTest extends TestCase{

    use DatabaseTransactions;

    protected $user;
    protected $accessToken;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = $this->testRegisterUser([
            'name' => 'Alvaro Test',
            'email' => 'alvaroTest@example.com',
            'password' => '12345678',
        ]);

        $this->accessToken = $this->testLoginUser([
            'email' => 'alvaroTest@example.com',
            'password' => '12345678',
        ]);
    }

    protected function testRegisterUser(array $data)
    {
        $response = $this->post('/register', $data);

        return User::where('email', $data['email'])->first();
    }

    public function testRegisterEndpoint()
    {
        $data = [
            'name' => 'Alvaro',
            'email' => 'alvaro@example.com',
            'password' => '12345678',
        ];

        $response = $this->json('POST', '/register', $data);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'accessToken',
                     'token_type',
                     'user' => [
                         'id',
                         'name',
                         'email',
                         'created_at',
                         'updated_at',
                     ],
                 ]);
    }

    protected function testLoginUser(array $credentials)
    {
        $response = $this->post('/login', $credentials);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'accessToken',
                     'token_type',
                     'user' => [
                         'id',
                         'name',
                         'email',
                         'created_at',
                         'updated_at',
                     ],
                 ]);

        return $response['accessToken'];
    }

    public function testLoginEndpoint()
    {
        $credentials = [
            'email' => 'alvaroTest@example.com',
            'password' => '12345678',
        ];

        $response = $this->json('POST', '/login', $credentials);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'message',
                     'accessToken',
                     'token_type',
                     'user' => [
                         'id',
                         'name',
                         'email',
                         'created_at',
                         'updated_at',
                     ],
                 ]);
    }

    public function testSearchEndpoint()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken,
        ])->json('GET', '/giphy/search', ['query' => 'messi','limit' => 5,'offset' => 1]);

        $response->assertStatus(200);
    }

    public function testShowEndpoint()
    {
        $gifId = 'YS5sSs8AJ3Qrj9XuAM';
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken,
        ])->json('GET', "/giphy/{$gifId}");

        $response->assertStatus(200);
    }

    public function testStoreFavoriteEndpoint()
    {
        $data = [
            'gif_id' => 'YS5sSs8AJ3Qrj9XuAM',
            'alias' => 'messi',
            'user_id' => $this->user->id
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '.$this->accessToken,
        ])->json('POST', '/giphy/favorites', $data);

        $response->assertStatus(200);
    }
}
