<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting DocumentTypesSeeder...');
        
        // Check current count
        $currentCount = DB::table('document_types')->count();
        $this->command->info("Current document types in database: {$currentCount}");
        
        // Disable foreign key checks temporarily
        $this->command->info('Disabling foreign key checks...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // First, delete all documents that reference document types
        $this->command->info('Deleting documents...');
        $documentsDeleted = DB::table('documents')->delete();
        $this->command->info("Deleted {$documentsDeleted} documents");
        
        // Then delete all document types
        $this->command->info('Deleting document types...');
        $typesDeleted = DB::table('document_types')->delete();
        $this->command->info("Deleted {$typesDeleted} document types");
        
        // Reset auto-increment counters
        $this->command->info('Resetting auto-increment counters...');
        DB::statement('ALTER TABLE documents AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE document_types AUTO_INCREMENT = 1');
        
        // Re-enable foreign key checks
        $this->command->info('Re-enabling foreign key checks...');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Create Document Types with Retention Policies (ONLY WITH PILLAR IDs)
        $this->command->info('Creating new document types with pillar IDs...');
        
        $documentTypes = [
            // Recruitment, Selection and Placement (Pillar 1)
            [
                'name' => 'Annual Summary Reports for Replacement Program for Non-Eligibles',
                'pillar_id' => 1,
                'retention_years' => 5,
                'description' => 'Retention: 5 years',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Applications -> Employment',
                'pillar_id' => 1,
                'retention_years' => 1,
                'description' => 'Retention: 1 year',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Applications -> Leave of Absence and Supporting Documents',
                'pillar_id' => 1,
                'retention_years' => 1,
                'description' => 'Retention: 1 year after recorded in leave cards',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Authorities/Requests to Create or Fill Vacant Positions',
                'pillar_id' => 1,
                'retention_years' => 2,
                'description' => 'Retention: 2 years after vacant positions had been filled up',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Certifications -> Employment',
                'pillar_id' => 1,
                'retention_years' => 1,
                'description' => 'Retention: 1 year',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Comparative Data Matrix of Employees',
                'pillar_id' => 1,
                'retention_years' => 2,
                'description' => 'Retention: 2 years',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Employee Interview Records',
                'pillar_id' => 1,
                'retention_years' => 1,
                'description' => 'Retention: 1 year',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lists of Eligibles/Non-Eligibles',
                'pillar_id' => 1,
                'retention_years' => 1,
                'description' => 'Retention: 1 year after updated',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Merit Promotion Plans',
                'pillar_id' => 1,
                'retention_years' => 1,
                'description' => 'Retention: 1 year after superseded',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Personal Data Sheets (Curriculum Vitae/Resume)',
                'pillar_id' => 1,
                'retention_years' => 1,
                'description' => 'Retention: 1 year after superseded',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Plantilla of Personnel',
                'pillar_id' => 1,
                'retention_years' => 0, // PERMANENT
                'description' => 'Retention: PERMANENT while other copies dispose after 3 years',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Position Allocation Lists',
                'pillar_id' => 1,
                'retention_years' => 3,
                'description' => 'Retention: 3 years',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Position Classifications and Pay Plans',
                'pillar_id' => 1,
                'retention_years' => 5,
                'description' => 'Retention: 5 years after superseded',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Recommendations/Referrals',
                'pillar_id' => 1,
                'retention_years' => 1,
                'description' => 'Retention: 1 year after acted upon',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Reports -> Personnel Actions',
                'pillar_id' => 1,
                'retention_years' => 0, // PERMANENT
                'description' => 'Retention: PERMANENT',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Requests -> Approval on Promotions',
                'pillar_id' => 1,
                'retention_years' => 1,
                'description' => 'Retention: 1 year after acted upon/cleared',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Requests -> Changes of Status',
                'pillar_id' => 1,
                'retention_years' => 1,
                'description' => 'Retention: 1 year after acted upon/cleared',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Requests -> Reinstatements',
                'pillar_id' => 1,
                'retention_years' => 1,
                'description' => 'Retention: 1 year after acted upon/cleared',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Requests -> Transfers',
                'pillar_id' => 1,
                'retention_years' => 1,
                'description' => 'Retention: 1 year after acted upon/cleared',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Staffing Patterns',
                'pillar_id' => 1,
                'retention_years' => 0, // PERMANENT
                'description' => 'Retention: PERMANENT',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Performance Management System (Pillar 2)
            [
                'name' => 'Performance Files -> Appraisal',
                'pillar_id' => 2,
                'retention_years' => 1,
                'description' => 'Retention: 1 year',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Performance Files -> Evaluation',
                'pillar_id' => 2,
                'retention_years' => 1,
                'description' => 'Retention: 1 year',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Performance Files -> Rating Cards',
                'pillar_id' => 2,
                'retention_years' => 5,
                'description' => 'Retention: 5 years',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Performance Files -> Target Worksheets',
                'pillar_id' => 2,
                'retention_years' => 1,
                'description' => 'Retention: 1 year',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Reports -> Examinations',
                'pillar_id' => 2,
                'retention_years' => 2,
                'description' => 'Retention: 2 years',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Learning and Development (Pillar 3)
            // Note: Only one document with pillar_id = 3 from our list
            [
                'name' => 'Training/Seminar Certificates',
                'pillar_id' => 3,
                'retention_years' => 15,
                'description' => 'Retention: 15 years after separated/retired',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Rewards and Recognition (Pillar 4)
            [
                'name' => 'Awards and Recognition',
                'pillar_id' => 4,
                'retention_years' => 15,
                'description' => 'Retention: 15 years after separated/retired',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Service Cards',
                'pillar_id' => 4,
                'retention_years' => 0, // PERMANENT
                'description' => 'Retention: PERMANENT',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $inserted = DB::table('document_types')->insert($documentTypes);
        
        $newCount = DB::table('document_types')->count();
        $this->command->info("Successfully inserted {$newCount} document types!");
        
        // Show summary by pillar
        $this->command->info("\nSummary by pillar:");
        $pillarCounts = DB::table('document_types')
            ->select('pillar_id', DB::raw('count(*) as count'))
            ->groupBy('pillar_id')
            ->get();
            
        foreach ($pillarCounts as $pillarCount) {
            $this->command->info("Pillar {$pillarCount->pillar_id}: {$pillarCount->count} documents");
        }
    }
}