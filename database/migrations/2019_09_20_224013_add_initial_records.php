<?php

use App\Role;
use App\User;
use App\Post;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;

class AddInitialRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = Role::where('key', 'admin')->first();
        $user = User::query()->create([
            'name' => 'admin',
            'email' => 'admin@blog.com',
            'password' => '$2y$12$qWd4AbP/YVp2czLtMywphu527FoU6.UjZpANA.Id915ZNYJnGyIRa',
            'role_id' => $role->id
        ]);

        Post::query()->insert([
            [
                'user_id' => $user->id, 'title' => 'Where can I get some?',
                'description' => 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using \'Content here, content here\', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for \'lorem ipsum\' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).',
                'image' => '/images/people.jpg', 'active' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ],
            [
                'user_id' => $user->id, 'title' => 'Microsoft',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'image' => '/images/microsoft.jpg', 'active' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ],
            [
                'user_id' => $user->id, 'title' => 'Why do we use it?',
                'description' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
                'image' => '/images/dices.jpg', 'active' => 1, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        User::query()->where('email', 'admin@blog.com')->delete();
    }
}
