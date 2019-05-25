<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Account;
use App\Models\Assignee;
use App\Models\Group;
use App\Models\Task;
use App\Models\Category;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Account::class, function (Faker $faker) {
    $categories = Category::all()->pluck('id')->toArray();

    return [
        'category_id' => $faker->randomElement($categories),
        'name' => $faker->name,
        'phone' => $faker->unique()->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => \Illuminate\Support\Facades\Hash::make('1234'),
        'remember_token' => Str::random(10),
    ];
});

$factory->define(Assignee::class, function (Faker $faker) {
    $accounts = Account::all()->pluck('id')->toArray();

    return [
        'account_id' => $faker->randomElement($accounts),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->unique()->phoneNumber,
        'password' => \Illuminate\Support\Facades\Hash::make('1234'),
        'license_plate' => Str::random(10),
        'notes' => $faker->text,
        'lat' => $faker->latitude(-34.279168, -33.946386),
        'lon' => $faker->longitude(18.379994, 18.705236),
        'remember_token' => Str::random(10),
    ];
});

$factory->define(Group::class, function (Faker $faker) {
    $accounts = Account::all()->pluck('id')->toArray();
    $accountId = $faker->randomElement($accounts);
    $assignees = [];
    foreach(Assignee::select('account_id','id')->get()->toArray() as $assignee) {
        if ($assignee['account_id'] == $accountId) {
            $assignees[] = $assignee['id'];
        }
    }

    return [
        'account_id' => $accountId,
        'assignee_id' => $faker->randomElement($assignees),
        'name' => $faker->randomElement(['pickup', 'delivery']),
        'day' => $faker->dayOfWeek,
        'time' => $faker->time('H:i'),
        'address' => $faker->address,
        'lat' => $faker->latitude(-34.279168, -33.946386),
        'lon' => $faker->longitude(18.379994, 18.705236),
        'email' =>$faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'notes' => $faker->text,
    ];
});


$factory->define(Task::class, function (Faker $faker) {
    $groups = Group::all()->pluck('id')->toArray();

    return [
        'group_id' => $faker->randomElement($groups),
        'name' => $faker->randomElement(['t-shirts', 'jackets', 'pants', 'coats', 'bags']),
        'quantity' => $faker->numberBetween(1, 6),
        'notes' => $faker->text,
        'completed_at' => $faker->dateTime,
    ];
});
