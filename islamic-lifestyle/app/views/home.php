<?php $title = 'Karibu'; ?>

<!-- Hero Section -->
<section class="bg-gradient-to-br from-green-600 to-green-800 text-white">
    <div class="container mx-auto px-4 py-16">
        <div class="text-center max-w-4xl mx-auto">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">
                Karibu Islamic Lifestyle
            </h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90">
                Jukwaa kamili la kidijitali kwa mahitaji ya Kiislamu kwa jamii ya Waislamu Tanzania
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/prayer-times" class="bg-white text-green-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    <i class="fas fa-clock mr-2"></i> Nyakati za Sala
                </a>
                <a href="/quran" class="bg-green-700 text-white px-8 py-3 rounded-lg font-semibold hover:bg-green-900 transition border border-white">
                    <i class="fas fa-book-quran mr-2"></i> Soma Qurani
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Prayer Times Widget -->
<?php if ($prayerTimes): ?>
<section class="bg-white py-8 shadow-sm">
    <div class="container mx-auto px-4">
        <h2 class="text-2xl font-bold text-center mb-6">Nyakati za Sala - Dar es Salaam</h2>
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 max-w-4xl mx-auto">
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <h3 class="font-semibold text-gray-600">Alfajiri</h3>
                <p class="text-2xl font-bold text-green-600"><?= date('H:i', strtotime($prayerTimes['fajr'])) ?></p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <h3 class="font-semibold text-gray-600">Jua</h3>
                <p class="text-2xl font-bold text-gray-400"><?= date('H:i', strtotime($prayerTimes['sunrise'])) ?></p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <h3 class="font-semibold text-gray-600">Adhuhuri</h3>
                <p class="text-2xl font-bold text-green-600"><?= date('H:i', strtotime($prayerTimes['dhuhr'])) ?></p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <h3 class="font-semibold text-gray-600">Alasiri</h3>
                <p class="text-2xl font-bold text-green-600"><?= date('H:i', strtotime($prayerTimes['asr'])) ?></p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <h3 class="font-semibold text-gray-600">Magharibi</h3>
                <p class="text-2xl font-bold text-green-600"><?= date('H:i', strtotime($prayerTimes['maghrib'])) ?></p>
            </div>
            <div class="text-center p-4 bg-gray-50 rounded-lg">
                <h3 class="font-semibold text-gray-600">Isha</h3>
                <p class="text-2xl font-bold text-green-600"><?= date('H:i', strtotime($prayerTimes['isha'])) ?></p>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Features Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Huduma Zetu</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <!-- Prayer Times -->
            <a href="/prayer-times" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <div class="text-center">
                    <i class="fas fa-clock text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Nyakati za Sala</h3>
                    <p class="text-gray-600">Pata nyakati sahihi za sala kwa miji yote ya Tanzania</p>
                </div>
            </a>
            
            <!-- Quran -->
            <a href="/quran" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <div class="text-center">
                    <i class="fas fa-book-quran text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Qurani Tukufu</h3>
                    <p class="text-gray-600">Soma Qurani na tafsiri ya Kiswahili</p>
                </div>
            </a>
            
            <!-- Hadith -->
            <a href="/hadith" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <div class="text-center">
                    <i class="fas fa-book text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Hadith</h3>
                    <p class="text-gray-600">Makusanyo ya Hadith za Mtume (S.A.W)</p>
                </div>
            </a>
            
            <!-- Fatwa -->
            <a href="/fatwa" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <div class="text-center">
                    <i class="fas fa-question-circle text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Fatwa & Maswali</h3>
                    <p class="text-gray-600">Uliza maswali na upate majibu kutoka kwa Masheikh</p>
                </div>
            </a>
            
            <!-- Zakat Calculator -->
            <a href="/zakat-calculator" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <div class="text-center">
                    <i class="fas fa-calculator text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Kokotoa Zakat</h3>
                    <p class="text-gray-600">Kokotoa kiasi cha Zakat unachopaswa kutoa</p>
                </div>
            </a>
            
            <!-- Inheritance -->
            <a href="/inheritance-calculator" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <div class="text-center">
                    <i class="fas fa-balance-scale text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Urithi wa Kiislamu</h3>
                    <p class="text-gray-600">Kokotoa mgawanyo wa urithi kwa mujibu wa Sharia</p>
                </div>
            </a>
            
            <!-- Halal Business -->
            <a href="/businesses" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <div class="text-center">
                    <i class="fas fa-store text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Biashara Halal</h3>
                    <p class="text-gray-600">Orodha ya biashara za halal Tanzania</p>
                </div>
            </a>
            
            <!-- Donations -->
            <a href="/donate" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
                <div class="text-center">
                    <i class="fas fa-hand-holding-heart text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Michango</h3>
                    <p class="text-gray-600">Changia Zakat, Sadaqah na michango mingine</p>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="py-16 bg-green-800 text-white">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Takwimu za Jukwaa</h2>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-4xl mx-auto">
            <div class="text-center">
                <div class="text-4xl font-bold mb-2"><?= number_format($stats['users']) ?></div>
                <div class="text-green-200">Watumiaji</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold mb-2"><?= number_format($stats['fatwas']) ?></div>
                <div class="text-green-200">Fatwa</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold mb-2"><?= number_format($stats['businesses']) ?></div>
                <div class="text-green-200">Biashara</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold mb-2"><?= formatCurrency($stats['donations_total']) ?></div>
                <div class="text-green-200">Jumla ya Michango</div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Articles -->
<?php if (!empty($recentArticles)): ?>
<section class="py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Makala za Hivi Karibuni</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            <?php foreach ($recentArticles as $article): ?>
                <article class="bg-white rounded-lg shadow hover:shadow-lg transition">
                    <?php if ($article['featured_image']): ?>
                        <img src="<?= e($article['featured_image']) ?>" alt="<?= e($article['title']) ?>" 
                             class="w-full h-48 object-cover rounded-t-lg">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                            <i class="fas fa-newspaper text-4xl text-gray-400"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">
                            <a href="/articles/<?= e($article['slug']) ?>" class="hover:text-green-600">
                                <?= e($article['title']) ?>
                            </a>
                        </h3>
                        <p class="text-gray-600 mb-4"><?= e($article['excerpt'] ?? substr($article['content'], 0, 150) . '...') ?></p>
                        <div class="flex justify-between items-center text-sm text-gray-500">
                            <span><i class="fas fa-user mr-1"></i> <?= e($article['author_name']) ?></span>
                            <span><i class="fas fa-calendar mr-1"></i> <?= formatDate($article['published_at']) ?></span>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-8">
            <a href="/articles" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                Soma Makala Zaidi <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="py-16 bg-gray-100">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Jiunge na Jamii Yetu</h2>
        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
            Jisajili leo na uanze kutumia huduma zetu zote za bure
        </p>
        <?php if (!isLoggedIn()): ?>
            <a href="/register" class="bg-green-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-green-700 transition">
                Jisajili Sasa <i class="fas fa-user-plus ml-2"></i>
            </a>
        <?php else: ?>
            <a href="/dashboard" class="bg-green-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-green-700 transition">
                Nenda Dashboard <i class="fas fa-tachometer-alt ml-2"></i>
            </a>
        <?php endif; ?>
    </div>
</section>