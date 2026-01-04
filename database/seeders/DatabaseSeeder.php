<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create default users
        DB::table('users')->insert([
            [
                'name' => 'System Administrator',
                'email' => 'admin@school.edu',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HR Manager',
                'email' => 'hr.manager@school.edu',
                'password' => Hash::make('hr123'),
                'role' => 'hr_manager',
                'is_active' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HR Staff',
                'email' => 'hr.staff@school.edu',
                'password' => Hash::make('staff123'),
                'role' => 'hr_staff',
                'is_active' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Create HR Pillars
        $pillars = [
            [
                'name' => 'Recruitment, Selection and Placement',
                'slug' => 'recruitment-selection-placement',
                'description' => 'Documents related to hiring and placement processes',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Performance Management System',
                'slug' => 'performance-management',
                'description' => 'Performance evaluation and management documents',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Learning and Development',
                'slug' => 'learning-development',
                'description' => 'Training and professional development records',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rewards and Recognition',
                'slug' => 'rewards-recognition',
                'description' => 'Awards, incentives, and recognition documents',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('hr_pillars')->insert($pillars);

        // Create Document Types with Retention Policies
        $documentTypes = [
            // Recruitment, Selection and Placement
            [
                'name' => 'Employment Applications',
                'pillar_id' => 1,
                'retention_years' => 1,
                'description' => 'Job applications and supporting documents',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Interview Records',
                'pillar_id' => 1,
                'retention_years' => 1,
                'description' => 'Interview notes and evaluation records',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Employment Contracts',
                'pillar_id' => 1,
                'retention_years' => 5,
                'description' => 'Job order and employment contracts',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Performance Management
            [
                'name' => 'Performance Appraisals',
                'pillar_id' => 2,
                'retention_years' => 2,
                'description' => 'Performance evaluation records',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Attendance Records',
                'pillar_id' => 2,
                'retention_years' => 1,
                'description' => 'Daily time records and attendance',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Learning and Development
            [
                'name' => 'Training Certificates',
                'pillar_id' => 3,
                'retention_years' => 5,
                'description' => 'Training and seminar certificates',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Development Plans',
                'pillar_id' => 3,
                'retention_years' => 3,
                'description' => 'Individual development plans',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Rewards and Recognition
            [
                'name' => 'Award Documentation',
                'pillar_id' => 4,
                'retention_years' => 10,
                'description' => 'Awards and recognition records',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Service Records',
                'pillar_id' => 4,
                'retention_years' => 15,
                'description' => 'Employee service records',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('document_types')->insert($documentTypes);

       

        $this->command->info('HR Document Management System seeded successfully!');
        $this->command->info('Default login: admin@school.edu / admin123');
        $this->command->info('HR Manager: hr.manager@school.edu / hr123');
        $this->command->info('HR Staff: hr.staff@school.edu / staff123');
    }
}