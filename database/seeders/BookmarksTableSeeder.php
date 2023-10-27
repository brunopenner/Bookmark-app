<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BookmarksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookmarks = [
            [
                'name' => 'Bookmark 1',
                'slug' => 'bookmark-1',
                'url' => 'https://example.com/bookmark-1',
                'description' => 'Description for Bookmark 1',
                'thumbnail' => 'thumbnail-1.jpg',
                'user_id' => 1,
            ],
            [
                'name' => 'Bookmark 2',
                'slug' => 'bookmark-2',
                'url' => 'https://example.com/bookmark-2',
                'description' => 'Description for Bookmark 2',
                'thumbnail' => 'thumbnail-1.jpg',
                'user_id' => 1,
            ],
            //Add more bookmarks as needed
        ];

        DB::table('bookmarks')->insert($bookmarks);

        $users = [
            [
                'name' => 'Jane Smith',
                'email' => 'test@test.com',
                'password' => Hash::make('password')
            ],
        ];

        DB::table('users')->insert($users);
    }
}
