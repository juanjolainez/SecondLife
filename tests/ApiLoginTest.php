<?php

use \App\Models\User as User;
use \App\Models\UserAccount as UserAccount;



class ApiLoginTest extends TestCase
{
    public function setUp()
    {
        @session_start();
        parent::setUp();
    }

    private $facebook_token = "CAAGPSOtjf40BAK8xYV28TEdej569sKqGBnetFXDoyU7hbEhjwjuFZC2xZCL3PZBoRkiimULTDZAZCqhqQcvjBXNH48nZArPfLtDukm6OjyfzjKmh3hRnShS81TB4PgiVKRLJvE4iZBPLgdMGAERtYluR7BrxZCiwRzhYDhbuZB5l7U16dQj4QHMG3EPtt9exRH8Dc8pLEV6FOZAS8sev8Q4iEh";
    private $twitter_token = "368444829-eVcZKRyeUSQauKpfsy8bnszMWGgewfd4vYQu4MF4 BUhQ0aZ1waMxSgB3xhxFYqtMrp5FDaCsrR6S29rYaltwg";


    protected function parseJson(Illuminate\Http\JsonResponse $response)
    {
        return json_decode($response->getContent());
    }

    protected function assertIsJson($data)
    {
        $this->assertEquals(0, json_last_error());
    }

    private function checkSameUser($userDatabase, $userResponse)
    {
        $this->assertEquals($userDatabase->email, $userResponse->email);
        $this->assertEquals($userDatabase->avatar, $userResponse->avatar);
    }

    public function testSocialLoginFacebook()
    {
        $token = $this->facebook_token;
        $content = json_encode(['provider' => 'facebook', 'token' => $token]);
        $response = $this->call(
            'POST',
            'http://secondlife.com/api/login',
            array(),
            array(),
            array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'Accept' => 'application/json',
                'HTTP_X-App-Platform' => '1.3.4'),
            $content
        );

        $this->assertResponseOk();

        $data = $this->parseJson($response);

        $this->assertIsJson($data);

        $dataArray = (array) $data;

        $users = User::all();

        $this->assertEquals(count($users), 1);

        $userDatabase = $users[0];
        $userResponse = $data->user;
        $this->checkSameUser($userDatabase, $userResponse);
    }


    public function testSocialLoginTwitter()
    {
        $token = $this->twitter_token;
        $content = json_encode(['provider' => 'twitter', 'token' => $token]);
        $response = $this->call(
            'POST',
            'http://secondlife.com/api/login',
            array(),
            array(),
            array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'Accept' => 'application/json',
                'HTTP_X-App-Platform' => '1.3.4'),
            $content
        );

        $this->assertResponseOk();

        $data = $this->parseJson($response);

        $this->assertIsJson($data);

        $dataArray = (array) $data;

        $users = User::all();

        $this->assertEquals(count($users), 1);

        $userDatabase = $users[0];
        $userResponse = $data->user;

        $this->checkSameUser($userDatabase, $userResponse);
    }

    public function testTraditionalSignupOk()
    {
        $content = json_encode(['email' => 'juanjolainez@gmail.com', 'password' => 'whatever']);
        $this->call(
            'POST',
            'http://secondlife.com/api/email-signup',
            array(),
            array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'Accept' => 'application/json',
                'HTTP_X-App-Platform' => '1.3.4'),
            $content
        );

        $response = $this->client->getResponse();

        $this->assertResponseOk();

        $data = $this->parseJson($response);

        $this->assertIsJson($data);

        $dataArray = (array) $data;


        $users = User::all();

        $this->assertEquals(count($users), 1);

        $userDatabase = $users[0];
        $userResponse = $data->user;

        $this->checkSameUser($userDatabase, $userResponse);
        $this->checkSameAccounts($userDatabase->accounts, $userResponse->accounts);
        $this->assertEquals($userDatabase->type, 'commerce');
        $this->assertEquals(count($userDatabase->sessions), 1);
        $session = $userDatabase->sessions()->first();
        $this->assertFalse($session->token == null);
        $this->assertEquals($session->token, $userResponse->token);
    }

    public function testTraditionalSignupNoMail()
    {
        $content = json_encode(['password' => 'whatever']);
        $this->call(
            'POST',
            'http://secondlife.com/api/email-signup',
            array(),
            array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'Accept' => 'application/json',
                'HTTP_X-App-Platform' => '1.3.4'),
            $content
        );

        $response = $this->client->getResponse();

        $data = $this->parseJson($response);

        $this->assertIsJson($data);

        $dataArray = (array) $data;

        $this->assertEquals($this->client->getResponse()->getStatusCode(), 400);

        $this->assertTrue(array_key_exists('error', $dataArray));
    }

    public function testTraditionalSignupNoMailValid()
    {
        $content = json_encode(['email' => 'invalid', 'password' => 'whatever']);
        $this->call(
            'POST',
            'http://secondlife.com/api/email-signup',
            array(),
            array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'Accept' => 'application/json',
                'HTTP_X-App-Platform' => '1.3.4'),
            $content
        );

        $response = $this->client->getResponse();

        $data = $this->parseJson($response);

        $this->assertIsJson($data);

        $dataArray = (array) $data;

        $this->assertEquals($this->client->getResponse()->getStatusCode(), 400);

        $this->assertTrue(array_key_exists('error', $dataArray));
    }

    public function testTraditionalSignupNoPassword()
    {
        $content = json_encode(['email' => 'whatever@gmail.com']);
        $this->call(
            'POST',
            'http://secondlife.com/api/email-signup',
            array(),
            array(),
            array(
                'CONTENT_TYPE' => 'application/json',
                'Accept' => 'application/json',
                'HTTP_X-App-Platform' => '1.3.4'),
            $content
        );

        $response = $this->client->getResponse();

        $data = $this->parseJson($response);

        $this->assertIsJson($data);

        $dataArray = (array) $data;

        $this->assertEquals($this->client->getResponse()->getStatusCode(), 400);

        $this->assertTrue(array_key_exists('error', $dataArray));
    }

    

    public function tearDown()
    {
        User::orderBy('id', 'desc')->delete();
        UserAccount::orderBy('id', 'desc')->delete();
    }
}
