<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Database\Seeders\ContactSeeder;
use Database\Seeders\SearchSeeder;
use Database\Seeders\UserSeeder;
use Tests\TestCase;

class ContactTest extends TestCase
{
    public function testCreateSuccess()
    {
        $this->seed(UserSeeder::class);

        $this->post('/api/contacts', [
            'first_name' => 'Ridwan',
            'last_name' => 'Hidayat',
            'email' => 'ridwan.nurulhidayat@gmail.com',
            'phone' => '+6283141418173'
        ], [
            'Authorization' => '12345'
        ])
            ->assertStatus(201)
            ->assertJson([
                'data' => [
                    'first_name' => 'Ridwan',
                    'last_name' => 'Hidayat',
                    'email' => 'ridwan.nurulhidayat@gmail.com',
                    'phone' => '+6283141418173'
                ]
            ]);
    }

    public function testCreateFailed()
    {
        $this->seed(UserSeeder::class);

        $this->post('/api/contacts', [
            'first_name' => '',
            'last_name' => 'Hidayat',
            'email' => 'ridwan.nurulhidayat',
            'phone' => '+6283141418173'
        ], [
            'Authorization' => '12345'
        ])
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'first_name' => ['The first name field is required.'],
                    'email' => ['The email field must be a valid email address.'],
                ]
            ]);
    }

    public function testCreateUnauthorized()
    {
        $this->seed(UserSeeder::class);

        $this->post('/api/contacts', [
            'first_name' => '',
            'last_name' => 'Hidayat',
            'email' => 'ridwan.nurulhidayat',
            'phone' => '+6283141418173'
        ], [
            'Authorization' => 'wrong'
        ])
            ->assertStatus(401)
            ->assertJson([
                'errors' => [
                    'message' => ['Unauthorized'],
                ]
            ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id, [
            'Authorization' => '12345'
        ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'first_name' => 'Ridwan',
                    'last_name' => 'Hidayat',
                    'email' => 'ridwan.nurulhidayat@gmail.com',
                    'phone' => '+6283141418173'
                ]
            ]);
    }

    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . ($contact->id + 1), [
            'Authorization' => '12345'
        ])
            ->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['Not found.']
                ]
            ]);
    }

    public function testGetOtherUserContact()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->get('/api/contacts/' . $contact->id, [
            'Authorization' => '123452'
        ])
            ->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['Not found.']
                ]
            ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->put('/api/contacts/' . $contact->id, [
            'first_name' => 'Ridwan2',
            'last_name' => 'Hidayat2',
            'email' => 'ridwan.nurulhidayat@gmail.com2',
            'phone' => '+62831414181732'
        ], [
            'Authorization' => '12345'
        ])
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'first_name' => 'Ridwan2',
                    'last_name' => 'Hidayat2',
                    'email' => 'ridwan.nurulhidayat@gmail.com2',
                    'phone' => '+62831414181732'
                ]
            ]);
    }

    public function testUpdateValidationError()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->put('/api/contacts/' . $contact->id, [
            'first_name' => '',
            'last_name' => 'Hidayat2',
            'email' => 'ridwan',
            'phone' => '+62831414181732'
        ], [
            'Authorization' => '12345'
        ])
            ->assertStatus(400)
            ->assertJson([
                'errors' => [
                    'first_name' => ['The first name field is required.'],
                    'email' => ['The email field must be a valid email address.'],
                ]
            ]);
    }

    public function testDeleteSuccess()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->delete(uri: '/api/contacts/' . $contact->id, headers: [
            'Authorization' => '12345'
        ])
            ->assertStatus(200)
            ->assertJson([
                'data' => true
            ]);
    }

    public function testDeleteNotFound()
    {
        $this->seed([UserSeeder::class, ContactSeeder::class]);

        $contact = Contact::query()->limit(1)->first();

        $this->delete(uri: '/api/contacts/' . ($contact->id + 1), headers: [
            'Authorization' => '12345'
        ])
            ->assertStatus(404)
            ->assertJson([
                'errors' => [
                    'message' => ['Not found.']
                ]
            ]);
    }

    public function testSearchByName()
    {
        $this->seed([UserSeeder::class, SearchSeeder::class]);

        $this->get('/api/contacts');
    }

    public function testSearchByEmail()
    {
    }

    public function testSearchByPhone()
    {
    }

    public function testSearchWithPage()
    {
    }

    public function testSearchByName()
    {
    }
}
