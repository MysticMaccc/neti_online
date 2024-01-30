<?php

namespace Database\Seeders;

use App\Models\roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            "2" => "Dashboard",
            "3" => "Enrollment Module",
            "4" => "Application Confirmation (Enrollment Module)",
            "5" => "Courses Module",
            "6" => "All Courses (Courses Module)",
            "7" => "Instructor Management Module",
            "8" => "Instructor List (Instructor Management Module)",
            "9" => "Billing Module",
            "10" => "Bank Accounts Management (Billing Module)",
            "11" => "Billing Monitoring (Billing Module)",
            "12" => "Billing Logs (Billing Module)",
            "13" => "Price Matrix (Billing Module)",
            "14" => "Report Module",
            "15" => "PDE Module",
            "16" => "Request PDE (PDE Module)",
            "17" => "PDE Status (PDE Module)",
            "18" => "PDE Report (PDE Module)",
            "19" => "Maintenance Module",
            "20" => "Announcement (Maintenance Module)",
            "21" => "FAQ (Maintenance Module)",
            "22" => "Handout (Maintenance Module)",
            "23" => "Rank (Maintenance Module)",
            "24" => "Roles (Maintenance Module)",
            "25" => "Room (Maintenance Module)",
            "26" => "Course Department (Maintenance Module)",
            "27" => "Home Page (Maintenance Module)",
            "28" => "Company (Maintenance Module)",
            "29" => "Certificate (Maintenance Module)",
            "30" => "Communication Module",
            "31" => "Text Blast (Communication Module)",
            "32" => "Send Email (Communication Module)",
            "33" => "Inquiries (Communication Module)",
            "34" => "Trainees Module",
            "35" => "Manage Trainees (Trainees Module)",
            "36" => "Trainee Inquiries (Trainees Module)",
            "37" => "Admin Accounts Module",
            "38" => "Manage Accounts (Admin Accounts Module)",
            "39" => "Training Calendar Module",
            "40" => "Training Schedule (Training Calendar Module)",
            "41" => "Special Class (Training Calendar Module)",
            "42" => "Event logs",
            "43" => "Notification History",
            "44" => "Pde Maintenance (PDE Module)",
            "45" => "Remedial (Enrollment Module)",
            "46" => "Logs (Enrollment Module)",
            "47" => "Manage Reservation",
            "48" => "Check In (Manage Reservation Module)",
            "49" => "Check Out (Manage Reservation Module)",
            "50" => "No Show (Manage Reservation Module)",
            "51" => "Room Assignment Report (Manage Reservation Module)",
            "52" => "Waiver-Amenities Checklist (Manage Reservation Module)",
            "53" => "Check Out List (Manage Reservation Module)",
            "54" => "Room Price Maintenance (Manage Reservation Module)",
        ];


        foreach ($roles as $id => $role_name) {
            roles::create([
                'id' => $id , 
                'rolename' => $role_name
            ]);
        }
    }
}
