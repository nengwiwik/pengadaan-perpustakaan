<?php

it('redirect homepage to login', function () {
    $response = $this->get('/');
    $response->assertStatus(302);
    $response->assertSee('login');
});

it('displays login page', function () {
    $response = $this->get('/login');
    $response->assertStatus(200);
});

it('forbid wrong username', function () {
    $response = $this->post('/login', [
        'email' => 'aaezha@gmail.com',
        'password' => 'pasti salah'
    ]);
    $response->assertSessionHasErrors('email');
    $response->assertRedirect('/');
    $this->assertGuest();
});
