<?php $title = 'Ingia'; ?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="text-center">
                <i class="fas fa-mosque text-6xl text-green-600"></i>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Ingia kwenye akaunti yako
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Au
                    <a href="/register" class="font-medium text-green-600 hover:text-green-500">
                        jisajili kama mtumiaji mpya
                    </a>
                </p>
            </div>
        </div>
        
        <form class="mt-8 space-y-6" action="/login" method="POST">
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
            
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Barua pepe</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" 
                           placeholder="Barua pepe"
                           value="<?= e(old('email')) ?>">
                </div>
                <div>
                    <label for="password" class="sr-only">Nywila</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm" 
                           placeholder="Nywila">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-900">
                        Nikumbuke
                    </label>
                </div>

                <div class="text-sm">
                    <a href="/forgot-password" class="font-medium text-green-600 hover:text-green-500">
                        Umesahau nywila?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-lock text-green-500 group-hover:text-green-400"></i>
                    </span>
                    Ingia
                </button>
            </div>
        </form>
        
        <div class="mt-6">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-gray-50 text-gray-500">Au</span>
                </div>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">
                    Bado huna akaunti?
                    <a href="/register" class="font-medium text-green-600 hover:text-green-500">
                        Jisajili sasa
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>