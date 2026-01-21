<?php
/**
 * Dashboard View
 *
 * @package WPRD
 */

if (!defined('ABSPATH')) {
    exit;
}

$baseline = WPRD_Storage::get_baseline();
$regressions = WPRD_Storage::get_regression_events();


?>

<div id="wprd-app" class="wrap">
    <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 leading-tight">WP Performance Regression Detector</h1>
            <p class="mt-2 text-sm text-gray-600">Real-time performance regression monitoring.</p>
        </div>

        <!-- Baseline Metrics Cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3 mb-10">
            <!-- Avg Page Load Time -->
            <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                <div class="px-5 py-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <!-- Icon: Clock -->
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Avg Load Time</dt>
                                <dd class="text-2xl font-semibold text-gray-900">
                                    <?php echo number_format(isset($baseline['avg_load_time']) ? $baseline['avg_load_time'] : 0, 4); ?>
                                    s
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avg DB Queries -->
            <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                <div class="px-5 py-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                            <!-- Icon: Database -->
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Avg DB Queries</dt>
                                <dd class="text-2xl font-semibold text-gray-900">
                                    <?php echo number_format(isset($baseline['avg_query_count']) ? $baseline['avg_query_count'] : 0, 1); ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Avg Memory Usage -->
            <div class="bg-white overflow-hidden shadow rounded-lg border border-gray-200">
                <div class="px-5 py-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <!-- Icon: Chip -->
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Avg Memory</dt>
                                <dd class="text-2xl font-semibold text-gray-900">
                                    <?php echo $this->format_bytes(isset($baseline['avg_memory_usage']) ? $baseline['avg_memory_usage'] : 0); ?>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Regression Events Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg border border-gray-200">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Regression Events</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Detected regressions correlated with recent system
                    actions.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Page URL</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Metric</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Baseline</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Current</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Severity</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trigger Event</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Time</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($regressions)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    No regressions detected yet.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($regressions as $r): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600 truncate max-w-xs">
                                        <?php echo esc_html($r['page_url']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php echo esc_html($r['metric']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php
                                        if ('Memory Usage' === $r['metric']) {
                                            echo $this->format_bytes($r['baseline_value']);
                                        } else {
                                            echo number_format($r['baseline_value'], 4);
                                        }
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                        <?php
                                        if ('Memory Usage' === $r['metric']) {
                                            echo $this->format_bytes($r['current_value']);
                                        } else {
                                            echo number_format($r['current_value'], 4);
                                        }
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php
                                        $badge_class = 'bg-gray-100 text-gray-800';
                                        if ('HIGH' === $r['severity']) {
                                            $badge_class = 'bg-red-100 text-red-800';
                                        } elseif ('MEDIUM' === $r['severity']) {
                                            $badge_class = 'bg-yellow-100 text-yellow-800';
                                        }
                                        ?>
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $badge_class; ?>">
                                            <?php echo esc_html($r['severity']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo esc_html($r['trigger_event']); ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php echo human_time_diff($r['timestamp'], time()); ?> ago
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>