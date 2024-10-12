<?php

/**
 * TDD - Test Driven Design means to write test before writing code itself
 * * p.s. write algorithm of user journey map - if else and etc.
 * * write all possible scenarios
 *
 * Cycle: red, green, refactor
 * * red - early test to be failed
 * * green - write code so test would be successful
 * * refactor - code so it would be well written
 */

test('unauthenticated user cannot access products page', function () {
    $this->get('/tdd/products')
        ->assertRedirect('/login');
});
