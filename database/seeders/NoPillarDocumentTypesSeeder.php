<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NoPillarDocumentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting NoPillarDocumentTypesSeeder...');
        
        // Check if we already have some of these document types
        $existingCount = DB::table('document_types')
            ->whereIn('name', [
                'Applications -> Relief of Accountability',
                'Applications -> Retirement/Resignation',
                'Attendance Monitoring Sheets',
                'Certifications -> Residency',
                'Certifications -> Service',
                'Certifications -> Others',
                'Daily Time Records',
                'Handwriting Specimens/Signature',
                'Job Order Employment Contracts',
                'Leave Credit Cards',
                'Logbooks -> Arrival & Departure of Employees Attendance',
                'Logbooks -> Clearances Issued',
                'Medical Certificates in Support of Absence on Account of Illness/Maternity',
                'Membership Files -> GSIS, Pag-ibig, PhilHealth',
                'Permissions to Engage in Business/Private Practice/Teach',
                'Personnel Folders (201 Files) -> All Contents',
                'Requests -> Accumulated Leave Credits',
                'Requests -> Bonding Officials/Employees',
                'Salary Standardization Records',
                'Statements of Assets and Liabilities',
            ])
            ->count();
            
        if ($existingCount > 0) {
            $this->command->warn("Found {$existingCount} existing document types with these names. Skipping to avoid duplicates.");
            return;
        }
        
        // Create Document Types without pillar IDs (set to 0)
        $documentTypes = [
            [
                'name' => 'Applications -> Relief of Accountability',
                'pillar_id' => 0,
                'retention_years' => 5,
                'description' => 'Retention: 5 years after separated/retired',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Applications -> Retirement/Resignation',
                'pillar_id' => 0,
                'retention_years' => 1,
                'description' => 'Retention: 1 year',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Attendance Monitoring Sheets',
                'pillar_id' => 0,
                'retention_years' => 1,
                'description' => 'Retention: 1 year',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Certifications -> Residency',
                'pillar_id' => 0,
                'retention_years' => 1,
                'description' => 'Retention: 1 year',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Certifications -> Service',
                'pillar_id' => 0,
                'retention_years' => 1,
                'description' => 'Retention: 1 year',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Certifications -> Others',
                'pillar_id' => 0,
                'retention_years' => 1,
                'description' => 'Retention: 1 year',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Daily Time Records',
                'pillar_id' => 0,
                'retention_years' => 1,
                'description' => 'Retention: 1 year after data had been posted in leave cards and post-audited',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Handwriting Specimens/Signature',
                'pillar_id' => 0,
                'retention_years' => 0,
                'description' => 'Retention: PERMANENT',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Job Order Employment Contracts',
                'pillar_id' => 0,
                'retention_years' => 5,
                'description' => 'Retention: 5 years after terminated',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Leave Credit Cards',
                'pillar_id' => 0,
                'retention_years' => 15,
                'description' => 'Retention: 15 years after separated/retired',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Logbooks -> Arrival & Departure of Employees Attendance',
                'pillar_id' => 0,
                'retention_years' => 2,
                'description' => 'Retention: 2 years after date of last entry',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Logbooks -> Clearances Issued',
                'pillar_id' => 0,
                'retention_years' => 2,
                'description' => 'Retention: 2 years after date of last entry',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Medical Certificates in Support of Absence on Account of Illness/Maternity',
                'pillar_id' => 0,
                'retention_years' => 3,
                'description' => 'Retention: 3 years after absences had been recorded in leave cards',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Membership Files -> GSIS, Pag-ibig, PhilHealth',
                'pillar_id' => 0,
                'retention_years' => 15,
                'description' => 'Retention: 15 years after separated/retired',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Permissions to Engage in Business/Private Practice/Teach',
                'pillar_id' => 0,
                'retention_years' => 2,
                'description' => 'Retention: 2 years after expired',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Personnel Folders (201 Files) -> All Contents',
                'pillar_id' => 0,
                'retention_years' => 15,
                'description' => 'Retention: 15 years after separated/retired',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Requests -> Accumulated Leave Credits',
                'pillar_id' => 0,
                'retention_years' => 1,
                'description' => 'Retention: 1 year after acted upon/cleared',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Requests -> Bonding Officials/Employees',
                'pillar_id' => 0,
                'retention_years' => 1,
                'description' => 'Retention: 1 year after acted upon/cleared',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Salary Standardization Records',
                'pillar_id' => 0,
                'retention_years' => 5,
                'description' => 'Retention: 5 years after superseded',
                'requires_employee' => false,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Statements of Assets and Liabilities',
                'pillar_id' => 0,
                'retention_years' => 10,
                'description' => 'Retention: 10 years',
                'requires_employee' => true,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $inserted = DB::table('document_types')->insert($documentTypes);
        
        $newCount = DB::table('document_types')->count();
        $this->command->info("Successfully inserted " . count($documentTypes) . " document types with pillar_id = 0!");
        $this->command->info("Total document types in database: {$newCount}");
    }
}