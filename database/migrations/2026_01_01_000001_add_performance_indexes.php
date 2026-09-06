<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Performance Indexes Migration
 *
 * Adds indexes to columns that appear in WHERE, ORDER BY, and JOIN clauses
 * on the most frequently executed queries identified during the performance audit.
 *
 * Only adds indexes not already present from the original migrations.
 * Uses hasIndex() checks to make this migration safe to re-run.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ── requests table ────────────────────────────────────────────────────────
        Schema::table('requests', function (Blueprint $table) {
            // WHERE status = ? (homepage stats, dashboard, admin counts)
            if (!$this->hasIndex('requests', 'requests_status_index')) {
                $table->index('status', 'requests_status_index');
            }
            // WHERE user_id = ? ORDER BY created_at DESC (user dashboard)
            if (!$this->hasIndex('requests', 'requests_user_id_created_at_index')) {
                $table->index(['user_id', 'created_at'], 'requests_user_id_created_at_index');
            }
            // WHERE assigned_technician_id = ? AND status = ? (technicianProfile completedRequests count)
            if (!$this->hasIndex('requests', 'requests_technician_status_index')) {
                $table->index(['assigned_technician_id', 'status'], 'requests_technician_status_index');
            }
            // ORDER BY created_at DESC (admin latest requests)
            if (!$this->hasIndex('requests', 'requests_created_at_index')) {
                $table->index('created_at', 'requests_created_at_index');
            }
        });

        // ── reviews table ─────────────────────────────────────────────────────────
        Schema::table('reviews', function (Blueprint $table) {
            // WHERE technician_id = ? AND status = 'approved' (technician profile reviews)
            if (!$this->hasIndex('reviews', 'reviews_technician_id_status_index')) {
                $table->index(['technician_id', 'status'], 'reviews_technician_id_status_index');
            }
            // WHERE user_id = ? (check existing review by user)
            if (!$this->hasIndex('reviews', 'reviews_user_id_index')) {
                $table->index('user_id', 'reviews_user_id_index');
            }
            // WHERE status = ? (homepage approved reviews, admin pending count)
            if (!$this->hasIndex('reviews', 'reviews_status_index')) {
                $table->index('status', 'reviews_status_index');
            }
        });

        // ── notifications table ───────────────────────────────────────────────────
        Schema::table('notifications', function (Blueprint $table) {
            // notifiable_type + notifiable_id already covered by morphs() index.
            // WHERE read_at IS NULL (unread count query — the most frequent notification query)
            if (!$this->hasIndex('notifications', 'notifications_read_at_index')) {
                $table->index('read_at', 'notifications_read_at_index');
            }
        });

        // ── technicians table ─────────────────────────────────────────────────────
        Schema::table('technicians', function (Blueprint $table) {
            // ORDER BY rating DESC (technicians listing page)
            if (!$this->hasIndex('technicians', 'technicians_rating_index')) {
                $table->index('rating', 'technicians_rating_index');
            }
            // WHERE specialization_id = ? (services by specialization)
            if (!$this->hasIndex('technicians', 'technicians_specialization_id_index')) {
                $table->index('specialization_id', 'technicians_specialization_id_index');
            }
        });

        // ── services table ────────────────────────────────────────────────────────
        Schema::table('services', function (Blueprint $table) {
            // WHERE specialization_id = ? (technicianProfile services)
            if (!$this->hasIndex('services', 'services_specialization_id_index')) {
                $table->index('specialization_id', 'services_specialization_id_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropIndexIfExists('requests_status_index');
            $table->dropIndexIfExists('requests_user_id_created_at_index');
            $table->dropIndexIfExists('requests_technician_status_index');
            $table->dropIndexIfExists('requests_created_at_index');
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndexIfExists('reviews_technician_id_status_index');
            $table->dropIndexIfExists('reviews_user_id_index');
            $table->dropIndexIfExists('reviews_status_index');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndexIfExists('notifications_read_at_index');
        });

        Schema::table('technicians', function (Blueprint $table) {
            $table->dropIndexIfExists('technicians_rating_index');
            $table->dropIndexIfExists('technicians_specialization_id_index');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndexIfExists('services_specialization_id_index');
        });
    }

    /**
     * Check if an index already exists to make the migration idempotent.
     */
    private function hasIndex(string $table, string $indexName): bool
    {
        $indexes = \Illuminate\Support\Facades\DB::select(
            "SHOW INDEX FROM `{$table}` WHERE Key_name = ?",
            [$indexName]
        );
        return count($indexes) > 0;
    }
};
