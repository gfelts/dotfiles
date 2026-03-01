<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('referral_number', 20)->unique();
            $table->enum('status', ['draft', 'pending', 'sent', 'accepted', 'declined', 'scheduled', 'completed', 'cancelled'])->default('draft');

            // Referring To
            $table->string('to_specialty', 150);
            $table->string('to_phone', 20)->nullable();
            $table->string('to_fax', 20)->nullable();
            $table->text('to_practice')->nullable();

            // Schedule
            $table->boolean('schedule_urgent')->default(false);
            $table->string('schedule_urgent_called', 255)->nullable();
            $table->boolean('schedule_routine_specific')->default(false);
            $table->string('schedule_routine_physician', 255)->nullable();
            $table->boolean('schedule_first_available')->default(false);

            // Referring Provider
            $table->string('referring_provider_name', 150)->nullable();
            $table->string('referring_provider_phone', 20)->nullable();
            $table->string('referring_provider_fax', 20)->nullable();

            // Type of Referral
            $table->boolean('referral_type_eval_primary')->default(false);
            $table->boolean('referral_type_eval_assumed')->default(false);
            $table->boolean('referral_type_eval_shared')->default(false);
            $table->boolean('referral_type_specialist')->default(false);
            $table->boolean('referral_type_other')->default(false);
            $table->string('referral_type_other_text', 255)->nullable();

            // Patient Information
            $table->string('patient_name', 150);
            $table->date('patient_dob');
            $table->string('patient_parent_name', 150)->nullable();
            $table->string('patient_phone', 20);
            $table->string('patient_best_time', 100)->nullable();
            $table->text('patient_special_considerations')->nullable();
            $table->text('patient_insurance')->nullable();
            $table->string('patient_pcp_name', 150)->nullable();
            $table->string('patient_pcp_phone', 20)->nullable();
            $table->string('patient_pcp_fax', 20)->nullable();

            // General Information
            $table->text('reason_for_referral');
            $table->text('comments_considerations')->nullable();
            $table->boolean('patient_aware')->default(false);
            $table->text('patient_aware_explain')->nullable();

            // Confirmation (filled later)
            $table->boolean('referral_accepted')->nullable();
            $table->text('referral_accepted_explain')->nullable();
            $table->string('appointment_with', 150)->nullable();
            $table->dateTime('appointment_datetime')->nullable();
            $table->enum('scheduling_status', ['scheduled', 'patient_refused', 'patient_will_schedule'])->nullable();
            $table->text('additional_info_request')->nullable();
            $table->string('confirmation_by', 150)->nullable();
            $table->date('confirmation_date')->nullable();

            // Metadata
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
