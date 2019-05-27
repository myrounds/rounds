<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Account;
use App\Models\Member;
use App\Models\Task;
use App\Models\Item;
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

$factory->define(Member::class, function (Faker $faker) {
    $accounts = Account::all()->pluck('id')->toArray();

    return [
        'account_id' => $faker->randomElement($accounts),
        'name' => $faker->name,
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

$factory->define(Task::class, function (Faker $faker) {
    $accounts = Account::all()->pluck('id')->toArray();
    $accountId = $faker->randomElement($accounts);
    $members = [];
    foreach(Member::select('account_id','id')->get()->toArray() as $member) {
        if ($member['account_id'] == $accountId) {
            $members[] = $member['id'];
        }
    }

    return [
        'account_id' => $accountId,
        'member_id' => $faker->randomElement($members),
        'name' => $faker->randomElement(['Pickup from', 'Deliver to', 'Go to']) . ' ' . $faker->company,
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


$factory->define(Item::class, function (Faker $faker) {
    $tasks = Task::all()->pluck('id')->toArray();

    return [
        'task_id' => $faker->randomElement($tasks),
        'name' => $faker->randomElement(['t-shirts', 'jackets', 'pants', 'coats', 'bags']),
        'quantity' => $faker->numberBetween(1, 6),
        'notes' => $faker->text,
        'completed_at' => $faker->dateTime,
    ];
});
