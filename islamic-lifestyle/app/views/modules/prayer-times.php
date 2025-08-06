<?php $title = 'Nyakati za Sala'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold mb-4">Nyakati za Sala</h1>
        <p class="text-gray-600">Pata nyakati sahihi za sala kwa miji yote ya Tanzania</p>
    </div>
    
    <!-- Location Selector -->
    <div class="max-w-4xl mx-auto mb-8">
        <form action="/prayer-times/location" method="POST" class="bg-white rounded-lg shadow p-6">
            <?= csrfField() ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div class="md:col-span-2">
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                        Chagua Mji
                    </label>
                    <select name="city" id="city" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500">
                        <?php foreach ($cities as $key => $city): ?>
                            <option value="<?= e($key) ?>" <?= $selectedCity === $key ? 'selected' : '' ?>>
                                <?= e($city['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition">
                        <i class="fas fa-map-marker-alt mr-2"></i> Badilisha Mji
                    </button>
                </div>
            </div>
        </form>
    </div>
    
    <!-- Next Prayer Card -->
    <?php if ($nextPrayer): ?>
    <div class="max-w-4xl mx-auto mb-8">
        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg shadow-lg p-6">
            <div class="text-center">
                <h2 class="text-2xl font-semibold mb-2">Sala Inayofuata</h2>
                <div class="text-5xl font-bold mb-2"><?= e($nextPrayer['name']) ?></div>
                <div class="text-3xl mb-2"><?= e($nextPrayer['time']) ?></div>
                <div class="text-lg opacity-90">
                    <i class="fas fa-clock mr-2"></i> Imebaki <?= e($nextPrayer['remaining']) ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Today's Prayer Times -->
    <?php if ($prayerTimes): ?>
    <div class="max-w-4xl mx-auto mb-8">
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b">
                <h3 class="text-xl font-semibold flex items-center justify-between">
                    <span>Nyakati za Sala - <?= e($cities[$selectedCity]['name']) ?></span>
                    <span class="text-sm text-gray-500"><?= formatDate($selectedDate, 'l, d F Y') ?></span>
                </h3>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <!-- Fajr -->
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <h4 class="font-semibold text-gray-700 mb-2">Alfajiri</h4>
                        <p class="text-2xl font-bold text-green-600"><?= date('H:i', strtotime($prayerTimes['fajr'])) ?></p>
                    </div>
                    
                    <!-- Sunrise -->
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <h4 class="font-semibold text-gray-700 mb-2">Macheo</h4>
                        <p class="text-2xl font-bold text-gray-400"><?= date('H:i', strtotime($prayerTimes['sunrise'])) ?></p>
                    </div>
                    
                    <!-- Dhuhr -->
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <h4 class="font-semibold text-gray-700 mb-2">Adhuhuri</h4>
                        <p class="text-2xl font-bold text-green-600"><?= date('H:i', strtotime($prayerTimes['dhuhr'])) ?></p>
                    </div>
                    
                    <!-- Asr -->
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <h4 class="font-semibold text-gray-700 mb-2">Alasiri</h4>
                        <p class="text-2xl font-bold text-green-600"><?= date('H:i', strtotime($prayerTimes['asr'])) ?></p>
                    </div>
                    
                    <!-- Maghrib -->
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <h4 class="font-semibold text-gray-700 mb-2">Magharibi</h4>
                        <p class="text-2xl font-bold text-green-600"><?= date('H:i', strtotime($prayerTimes['maghrib'])) ?></p>
                    </div>
                    
                    <!-- Isha -->
                    <div class="bg-gray-50 rounded-lg p-4 text-center">
                        <h4 class="font-semibold text-gray-700 mb-2">Isha</h4>
                        <p class="text-2xl font-bold text-green-600"><?= date('H:i', strtotime($prayerTimes['isha'])) ?></p>
                    </div>
                </div>
                
                <div class="mt-4 text-sm text-gray-500 text-center">
                    <i class="fas fa-info-circle mr-1"></i> 
                    Mbinu ya kukokotoa: <?= e($prayerTimes['calculation_method'] ?? 'ISNA') ?>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="max-w-4xl mx-auto mb-8">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
            <i class="fas fa-exclamation-triangle text-yellow-600 text-3xl mb-3"></i>
            <p class="text-yellow-800">Samahani, hatuwezi kupata nyakati za sala kwa sasa. Tafadhali jaribu tena baadaye.</p>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Date Picker -->
    <div class="max-w-4xl mx-auto mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Chagua Tarehe</h3>
            <form method="GET" action="/prayer-times" class="flex flex-col sm:flex-row gap-4">
                <input type="date" 
                       name="date" 
                       value="<?= e($selectedDate) ?>" 
                       max="<?= date('Y-m-d', strtotime('+30 days')) ?>"
                       min="<?= date('Y-m-d', strtotime('-30 days')) ?>"
                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500">
                <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-md hover:bg-green-700 transition">
                    <i class="fas fa-calendar mr-2"></i> Onyesha
                </button>
            </form>
        </div>
    </div>
    
    <!-- Monthly Calendar -->
    <?php if (!empty($monthlyTimes)): ?>
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b">
                <h3 class="text-xl font-semibold">Ratiba ya Mwezi - <?= formatDate($selectedDate, 'F Y') ?></h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tarehe</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Alfajiri</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jua</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Adhuhuri</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Alasiri</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Magharibi</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Isha</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($monthlyTimes as $day): ?>
                        <tr class="<?= $day['date'] === date('Y-m-d') ? 'bg-green-50' : '' ?>">
                            <td class="px-4 py-3 whitespace-nowrap text-sm <?= $day['date'] === date('Y-m-d') ? 'font-semibold' : '' ?>">
                                <?= formatDate($day['date'], 'd') ?>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center"><?= date('H:i', strtotime($day['fajr'])) ?></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-400"><?= date('H:i', strtotime($day['sunrise'])) ?></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center"><?= date('H:i', strtotime($day['dhuhr'])) ?></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center"><?= date('H:i', strtotime($day['asr'])) ?></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center"><?= date('H:i', strtotime($day['maghrib'])) ?></td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center"><?= date('H:i', strtotime($day['isha'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Info Section -->
    <div class="max-w-4xl mx-auto mt-12">
        <div class="bg-blue-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-3 text-blue-900">
                <i class="fas fa-info-circle mr-2"></i> Maelezo Muhimu
            </h3>
            <ul class="space-y-2 text-blue-800">
                <li>• Nyakati za sala zinakokotolewa kwa kutumia mbinu ya ISNA (Islamic Society of North America)</li>
                <li>• Nyakati hizi ni za kawaida na zinaweza kutofautiana kidogo kulingana na mahali ulipo</li>
                <li>• Ni vyema kushauriana na Msikiti uliokaribu nawe kwa nyakati za uhakika</li>
                <li>• Wakati wa jua (Sunrise) ni wakati ambao hairuhusiwi sala ya Fardh</li>
            </ul>
        </div>
    </div>
</div>

<script>
// Auto-refresh countdown
setInterval(function() {
    const now = new Date();
    const hours = now.getHours();
    const minutes = now.getMinutes();
    
    // Update countdown if on current date
    if ('<?= $selectedDate ?>' === '<?= date('Y-m-d') ?>') {
        // You can add live countdown update here
    }
}, 60000); // Update every minute
</script>