<?php
use PHPUnit\Framework\TestCase;
require __DIR__ . '/../../../vendor/autoload.php';
require_once 'ParfumeService.php';
require_once 'UserService.php';
require_once 'AuthService.php';
require_once 'ReviewService.php';

$parfumeService = new ParfumeService();
$userService = new UserService();
$authService = new AuthService();
$reviewService = new ReviewService();



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

/*echo "Fetching all users:\n";

$users = $userService->getAll();

print_r($users);*/


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

/*echo "getting review";

try {
    $reviewId = 1; // Change this to an existing review ID in your DB
    $review = $reviewService->getReviewById($reviewId);
    echo "Review found:\n";
    print_r($review);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}*/


/*echo "creating review"

$reviewData = [
    'user_id' => 8,  // Example valid user ID
    'parfume_id' => 3,  // Example valid fragrance ID
    'rating' => 5,  // Rating from 1 to 5
    'comment' => 'Amazing fragrance!',  // Optional comment
];

// Try to create the review
try {
    echo $reviewService->createReview($reviewData);
    echo "\nReview created successfully!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}*/



/*echo "updating parfume";

$reviewData = [
    'user_id' => 8,  // Example valid user ID
    'parfume_id' => 2,  // Example valid fragrance ID
    'rating' => 4,  // New rating from 1 to 5
    'comment' => 'Great fragrance, but needs more lasting power.',  // Updated comment
];

// Review ID to update
$reviewId = 1;  // Assuming review ID 1 exists

// Try to update the review
try {
    $result = $reviewService->updateReview($reviewId, $reviewData);
    echo "Review updated successfully!";
    print_r($result);  // Optionally print the updated review details
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}*/


/*echo "Deleting the parfume:\n";

$reviewIdToDelete = 2;
$reviewService->deleteReview($reviewIdToDelete);
echo "review deleted succesfully.";*/


/*echo "Fetching all reviews:\n";

$reviews = $reviewService->getAll();

print_r($reviews);*/

class ProjectFunctionalitiesTest extends TestCase {
    private $userService;
    private $authService;
    private $reviewService;
    private $parfumeService;

    protected function setUp(): void {
        $this->userService = new UserService();
        $this->authService = new AuthService();
        $this->reviewService = new ReviewService();
        $this->parfumeService = new ParfumeService();
    }

    public function testRegister() {
        $email = 'testuser_' . uniqid() . '@example.com';
        $userData = [
            'username' => 'TestUser_' . uniqid(),
            'email' => $email,
            'password' => 'testpassword',
            'role' => 'user'
        ];
        $result = $this->authService->register($userData);
        $this->assertTrue(is_array($result) && (isset($result['id']) || isset($result['user']) || isset($result['success'])), 'User registration should succeed');
    }

    public function testLogin() {
        $email = 'loginuser_' . uniqid() . '@example.com';
        $password = 'testpassword';
        $userData = [
            'username' => 'LoginUser_' . uniqid(),
            'email' => $email,
            'password' => $password,
            'role' => 'user'
        ];
        $this->authService->register($userData);
        $loginResult = $this->authService->login(['email' => $email, 'password' => $password]);
        $this->assertTrue(is_array($loginResult) && (isset($loginResult['success']) ? $loginResult['success'] : false), 'Login should succeed');
    }

    public function testLeaveReview() {
        $email = 'reviewuser_' . uniqid() . '@example.com';
        $userData = [
            'username' => 'ReviewUser_' . uniqid(),
            'email' => $email,
            'password' => 'testpassword',
            'role' => 'user'
        ];
        $user = $this->authService->register($userData);
        $userId = isset($user['id']) ? $user['id'] : (isset($user['user']['id']) ? $user['user']['id'] : null);
        $this->assertTrue(!empty($userId), 'User should be created and have an ID');
        $fragrances = $this->parfumeService->getAllFragrances();
        $this->assertTrue(is_array($fragrances) && count($fragrances) > 0, 'At least one fragrance must exist');
        $parfume_id = $fragrances[0]['id'];
        $reviewData = [
            'user_id' => $userId,
            'parfume_id' => $parfume_id,
            'rating' => 5,
            'comment' => 'Great fragrance!'
        ];
        $result = $this->reviewService->dao->insert($reviewData);
        $this->assertTrue($result === true || $result === 1, 'Review should be left successfully');
    }
}