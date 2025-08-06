<?php $title = 'Dashboard'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Karibu, <?= e(currentUser()['full_name']) ?>!</h1>
        <p class="text-gray-600">Hapa ni muhtasari wa shughuli zako kwenye jukwaa la Islamic Lifestyle.</p>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Fatwas Asked -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Maswali Yaliyoulizwa</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['fatwas']) ?></p>
                </div>
                <i class="fas fa-question-circle text-4xl text-green-500 opacity-50"></i>
            </div>
        </div>
        
        <!-- Donations -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Michango</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['donations_count']) ?></p>
                </div>
                <i class="fas fa-hand-holding-heart text-4xl text-blue-500 opacity-50"></i>
            </div>
        </div>
        
        <!-- Total Donated -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Jumla ya Michango</p>
                    <p class="text-2xl font-bold text-gray-800"><?= formatCurrency($stats['donations_total']) ?></p>
                </div>
                <i class="fas fa-donate text-4xl text-purple-500 opacity-50"></i>
            </div>
        </div>
        
        <!-- Zakat Calculations -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Mahesabu ya Zakat</p>
                    <p class="text-3xl font-bold text-gray-800"><?= number_format($stats['zakat_calculations']) ?></p>
                </div>
                <i class="fas fa-calculator text-4xl text-orange-500 opacity-50"></i>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Vitendo vya Haraka</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="/prayer-times" class="bg-green-50 border border-green-200 rounded-lg p-4 text-center hover:bg-green-100 transition">
                <i class="fas fa-clock text-2xl text-green-600 mb-2"></i>
                <p class="text-sm font-medium">Nyakati za Sala</p>
            </a>
            <a href="/quran" class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center hover:bg-blue-100 transition">
                <i class="fas fa-book-quran text-2xl text-blue-600 mb-2"></i>
                <p class="text-sm font-medium">Soma Qurani</p>
            </a>
            <a href="/fatwa/ask" class="bg-purple-50 border border-purple-200 rounded-lg p-4 text-center hover:bg-purple-100 transition">
                <i class="fas fa-question text-2xl text-purple-600 mb-2"></i>
                <p class="text-sm font-medium">Uliza Swali</p>
            </a>
            <a href="/zakat-calculator" class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-center hover:bg-orange-100 transition">
                <i class="fas fa-calculator text-2xl text-orange-600 mb-2"></i>
                <p class="text-sm font-medium">Kokotoa Zakat</p>
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Fatwas -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold">Maswali Yako ya Hivi Karibuni</h2>
            </div>
            <div class="p-6">
                <?php if (!empty($recentFatwas)): ?>
                    <div class="space-y-4">
                        <?php foreach ($recentFatwas as $fatwa): ?>
                            <div class="border-l-4 border-green-500 pl-4">
                                <h3 class="font-medium mb-1">
                                    <a href="/fatwa/<?= $fatwa['id'] ?>" class="hover:text-green-600">
                                        <?= e($fatwa['question_title']) ?>
                                    </a>
                                </h3>
                                <p class="text-sm text-gray-500">
                                    <span class="inline-block px-2 py-1 text-xs rounded-full 
                                        <?= $fatwa['status'] === 'answered' ? 'bg-green-100 text-green-800' : 
                                            ($fatwa['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                            'bg-gray-100 text-gray-800') ?>">
                                        <?= ucfirst($fatwa['status']) ?>
                                    </span>
                                    <span class="ml-2"><?= timeAgo($fatwa['created_at']) ?></span>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-4">
                        <a href="/my-fatwas" class="text-green-600 hover:text-green-700 font-medium">
                            Ona maswali yote <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">Bado hujauliza swali lolote.</p>
                    <a href="/fatwa/ask" class="inline-block mt-4 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                        <i class="fas fa-plus mr-2"></i> Uliza Swali
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Recent Activities -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold">Shughuli za Hivi Karibuni</h2>
            </div>
            <div class="p-6">
                <?php if (!empty($recentActivities)): ?>
                    <div class="space-y-3">
                        <?php foreach ($recentActivities as $activity): ?>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-circle text-xs text-green-500 mt-1"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm">
                                        <?php
                                        $actions = [
                                            'login' => 'Umeingia kwenye akaunti',
                                            'logout' => 'Umetoka kwenye akaunti',
                                            'register' => 'Umejisajili',
                                            'fatwa_asked' => 'Umeuliza swali',
                                            'donation_made' => 'Umefanya mchango',
                                            'zakat_calculated' => 'Umekokotoa Zakat',
                                            'profile_updated' => 'Umebadilisha wasifu'
                                        ];
                                        echo $actions[$activity['action']] ?? $activity['action'];
                                        ?>
                                    </p>
                                    <p class="text-xs text-gray-500"><?= timeAgo($activity['created_at']) ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">Hakuna shughuli za kuonyesha.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>