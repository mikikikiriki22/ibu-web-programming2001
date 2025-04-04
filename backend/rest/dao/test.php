<?php
require_once 'UserDao.php';

$userDao = new UserDao();

// $userDao->insert([
//     'Username' => 'Armin',
//     'email' => 'Armin@example.com',
//     'about' => 'hello, my name is Armin, i hate fragrances and my friends',
//     'image_url' => 'some image.jpg'
// ]);

$users = $userDao->update(8, [
    'about' => 'ola, moi bien Armin, odio los perfumes.'
]);
print_r($users);
