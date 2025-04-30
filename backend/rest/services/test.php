<?php

require_once 'ParfumeService.php';
require_once 'UserService.php';
require_once 'AuthService.php';

$parfumeService = new ParfumeService();
$userService = new UserService();
$authService = new AuthService();



//PARFUME SERVICE TEST

/*echo "Adding a new fragrance:\n";

$newFragrance = [
    'name' => 'Carlisle',
    'brand_id' => 11,
    'description' => 'Parfums de Marly Carlisle is a fragrance of contrasts — a spicy, woody scent balanced with warm, sweet notes. It opens with bright green apple and nutmeg, unfolding into a luxurious heart of rose and tonka bean. The rich base of vanilla, patchouli, and opoponax creates a powerful yet refined finish, embodying timeless elegance and strength.',
    'seasons' => 'Autumn, Winter',
    'notes_id' => '2' // (adjust notes later)
];
$addedFragrance = $parfumeService->addFragrance($newFragrance);
print_r($addedFragrance); */


/*echo "Deleting the fragrance:\n";

$fragranceIdToDelete = 1;
$parfumeService->deleteFragrance($fragranceIdToDelete);
echo "Fragrance deleted succesfully.";*/


/*echo "Updating an existing fragrance:\n";

$fragranceId = 2;  // Example ID of the fragrance to be updated

$updatedFragrance = [
    'name' => 'Naxos',
    'brand_id' => 4,
    'description' => 'Updated description for Naxos...',
    'seasons' => 'Winter, Autumn'
];

$parfumeService->update($fragranceId, $updatedFragrance);  // Update fragrance details
echo "Fragrance updated successfully.\n"; */


/*echo "Fetching a fragrance by ID:\n";

$fragranceId = 1;

$fragrance = $parfumeService->getById($fragranceId);

if ($fragrance) {
    print_r($fragrance);
} else {
    echo "Fragrance not found.\n";
}*/


/*echo "Fetching all fragrances:\n";

$fragrances = $parfumeService->getAll();

print_r($fragrances);*/


//USER SERVICE TEST


/*echo "Viewing user:\n";

$userId = 6;
$user = $userService->getById($userId);

if ($user) {
    print_r($user);
} else {
    echo "Fragrance not found.\n";
}*/



/*echo "Deleting the user:\n";

$userIdToDelete = 6;
$userService->deleteUser($userIdToDelete);
echo "User deleted succesfully.";*/


/*echo "Editing user profile:\n";

$userId = 8;

$updatedUser = [
    'username' => 'Armin Buza',
    'email' => 'buzabuza@hotmail.com',
    'about' => 'Volim Moštre više od Visokog!',
    'image_url' => 'new_image.url'
];

$userService->update($userId, $updatedUser);  // Update fragrance details
echo "User updated successfully.\n";*/


/*echo "Registering:\n";

try {
    // Sample test data for registration
    $testUser = [
        'username' => 'DinoBiscanin',
        'email' => 'dino@gmail.com',
        'about' => 'Ja sam Dino, volim svoje skolske kolege',
        'image_url' => 'some_url',
        'password' => 'biscankoIzDzenetaDjevojko!',
        'gender' => 'male'
    ];

    // Try registering the user
    $result = $authService->register($testUser);

    echo "Registration successful:\n";
    print_r($result);
} catch (Exception $e) {
    echo "Registration failed: " . $e->getMessage();
}*/


/*echo "Loging in:\n";

try {
    $authService = new AuthService();

    $loginData = [
        'email' => 'dino@gmail.com',
        'password' => 'biscankoIzDzenetaDjevojko!'
    ];

    $result = $authService->login($loginData);

    if ($result['success']) {
        echo "Login successful!\n";
        print_r($result['data']);
    } else {
        echo "Login failed: " . $result['error'] . "\n";
    }
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}*/
