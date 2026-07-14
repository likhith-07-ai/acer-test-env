<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Drop old foreign keys if they exist
            try {
                $table->dropForeign(['document_id']);
            } catch (\Exception $e) {
                // Foreign key doesn't exist, skip
            }
            
            try {
                $table->dropForeign(['policy_id']);
            } catch (\Exception $e) {
                // Foreign key doesn't exist, skip
            }
        });

        // Check if columns already exist before adding
        if (!Schema::hasColumn('audit_logs', 'auditable_id')) {
            Schema::table('audit_logs', function (Blueprint $table) {
                // Add polymorphic columns
                $table->nullableMorphs('auditable'); // Creates auditable_id and auditable_type
                
                // Add model name column for easier querying
                $table->string('model_name')->nullable()->after('auditable_type');
                
                // Add description/notes column
                $table->text('description')->nullable()->after('new_data');
                
                // Add IP address and user agent for tracking
                $table->string('ip_address', 45)->nullable()->after('description');
                $table->text('user_agent')->nullable()->after('ip_address');
            });
        }

        // Migrate existing data
        if (Schema::hasColumn('audit_logs', 'document_id') && Schema::hasColumn('audit_logs', 'auditable_id')) {
            DB::statement("UPDATE audit_logs SET auditable_id = document_id, auditable_type = 'App\\\\Models\\\\Document', model_name = 'Document' WHERE document_id IS NOT NULL AND auditable_id IS NULL");
        }
        
        if (Schema::hasColumn('audit_logs', 'policy_id') && Schema::hasColumn('audit_logs', 'auditable_id')) {
            DB::statement("UPDATE audit_logs SET auditable_id = policy_id, auditable_type = 'App\\\\Models\\\\Policy', model_name = 'Policy' WHERE policy_id IS NOT NULL AND document_id IS NULL AND auditable_id IS NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            // Migrate data back if needed
            DB::statement("UPDATE audit_logs SET document_id = auditable_id WHERE auditable_type = 'App\\\\Models\\\\Document'");
            DB::statement("UPDATE audit_logs SET policy_id = auditable_id WHERE auditable_type = 'App\\\\Models\\\\Policy'");
            
            // Drop polymorphic columns
            $table->dropColumn(['auditable_id', 'auditable_type', 'model_name', 'description', 'ip_address', 'user_agent']);
            
            // Re-add foreign keys
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
            $table->foreign('policy_id')->references('id')->on('policies')->onDelete('cascade');
        });
    }
};
