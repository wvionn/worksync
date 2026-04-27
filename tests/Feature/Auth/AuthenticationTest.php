<?php

use App\Models\User;
use Livewire\Volt\Volt;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response
        ->assertOk()
        ->assertSeeVolt('pages.auth.login');
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password');

    $component->call('login');

    $component
        ->assertHasNoErrors()
        ->assertRedirect(route('member.dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'wrong-password');

    $component->call('login');

    $component
        ->assertHasErrors()
        ->assertNoRedirect();

    $this->assertGuest();
});

test('users can authenticate using post login endpoint', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('member.dashboard', absolute: false));
    $this->assertAuthenticatedAs($user);
});

test('post login endpoint rejects invalid credentials', function () {
    $user = User::factory()->create();

    $response = $this->from('/login')->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response
        ->assertRedirect('/login')
        ->assertSessionHasErrors('email')
        ->assertSessionHas('error_message');

    $this->assertGuest();
});

test('admin users are redirected to admin dashboard after login', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $admin->email)
        ->set('form.password', 'password');

    $component->call('login');

    $component
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('member users cannot access admin dashboard', function () {
    $member = User::factory()->create(['role' => 'member']);

    $response = $this->actingAs($member)->get('/admin/dashboard');

    $response
        ->assertRedirect(route('dashboard', absolute: false))
        ->assertSessionHas('error_message');
});

test('login route strips sensitive query parameters', function () {
    $response = $this->get('/login?email=admin@worksync.com&password=admin123');

    $response->assertRedirect(route('login', absolute: false));
});

test('navigation menu can be rendered', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/dashboard');

    $response
        ->assertOk()
        ->assertSeeVolt('layout.navigation');
});

test('users can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Volt::test('layout.navigation');

    $component->call('logout');

    $component
        ->assertHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
});
