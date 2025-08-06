<?php $title = 'Jisajili'; ?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="text-center">
                <i class="fas fa-mosque text-6xl text-green-600"></i>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Tengeneza akaunti mpya
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Jiunge na jamii ya Waislamu Tanzania
                </p>
            </div>
        </div>
        
        <form class="mt-8 space-y-6" action="/register" method="POST">
            <?= csrfField() ?>
            
            <?php if ($errors = flash('errors')): ?>
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        <?php foreach ($errors as $field => $fieldErrors): ?>
                            <?php foreach ($fieldErrors as $error): ?>
                                <li><?= e($error) ?></li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="space-y-4">
                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">
                        Jina la mtumiaji
                    </label>
                    <input id="username" name="username" type="text" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" 
                           placeholder="Jina la mtumiaji"
                           value="<?= e(old('username')) ?>">
                </div>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Barua pepe
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" 
                           placeholder="mfano@barua.com"
                           value="<?= e(old('email')) ?>">
                </div>
                
                <!-- Full Name -->
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700">
                        Jina kamili
                    </label>
                    <input id="full_name" name="full_name" type="text" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" 
                           placeholder="Jina lako kamili"
                           value="<?= e(old('full_name')) ?>">
                </div>
                
                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">
                        Namba ya simu (si lazima)
                    </label>
                    <input id="phone" name="phone" type="tel" 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" 
                           placeholder="0712345678"
                           value="<?= e(old('phone')) ?>">
                </div>
                
                <!-- Region -->
                <div>
                    <label for="region" class="block text-sm font-medium text-gray-700">
                        Mkoa
                    </label>
                    <select id="region" name="region" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm">
                        <option value="">Chagua mkoa</option>
                        <?php foreach ($regions as $key => $name): ?>
                            <option value="<?= e($key) ?>" <?= old('region') == $key ? 'selected' : '' ?>>
                                <?= e($name) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Nywila
                    </label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" 
                           placeholder="Nywila (angalau herufi 6)">
                </div>
                
                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Thibitisha nywila
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" 
                           placeholder="Rudia nywila">
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-user-plus text-green-500 group-hover:text-green-400"></i>
                    </span>
                    Jisajili
                </button>
            </div>
            
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Tayari una akaunti?
                    <a href="/login" class="font-medium text-green-600 hover:text-green-500">
                        Ingia hapa
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>