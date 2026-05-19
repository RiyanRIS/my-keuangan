/**
 * Master Data Cache Manager
 * Handles localStorage caching for wallets, categories, and related data
 * Pattern: Load from cache first → Render instant → Background sync
 */

const CACHE_VERSION = 'v1';
const CACHE_KEYS = {
    WALLETS: `wallets_${CACHE_VERSION}`,
    CATEGORIES: `categories_${CACHE_VERSION}`,
    LAST_SYNC: 'last_sync_timestamp'
};

// In-memory storage for instant access
window.appData = {
    allWallets: [],
    allCategories: [],
    lastSync: null
};

window.CacheManager = {
    initMasterData,
    fetchMasterData,
    backgroundSyncMasterData,
    addWalletToCache,
    updateWalletInCache,
    removeWalletFromCache,
    addCategoryToCache,
    updateCategoryInCache,
    removeCategoryFromCache,
    clearMasterDataCache,
    getWalletById,
    getCategoryById,
    getAllWallets,
    getAllCategories
};

/**
 * Initialize master data from cache
 * Shows cached data instantly, then syncs in background
 */
async function initMasterData() {
    const cachedWallets = localStorage.getItem(CACHE_KEYS.WALLETS);
    const cachedCategories = localStorage.getItem(CACHE_KEYS.CATEGORIES);

    if (cachedWallets && cachedCategories) {
        // Load from cache instantly
        window.appData.allWallets = JSON.parse(cachedWallets);
        window.appData.allCategories = JSON.parse(cachedCategories);

        // Trigger rendering if callback exists
        if (window.onMasterDataReady) {
            window.onMasterDataReady();
        }

        // Background sync (non-blocking)
        backgroundSyncMasterData();
        return;
    }

    // First time load - fetch from server
    await fetchMasterData();
}

/**
 * Fetch master data from server and cache it
 */
async function fetchMasterData() {
    const token = localStorage.getItem('api_token');

    try {
        const [walletsRes, categoriesRes] = await Promise.all([
            fetch('/api/wallets', {
                headers: { 'Authorization': `Bearer ${token}` }
            }),
            fetch('/api/categories', {
                headers: { 'Authorization': `Bearer ${token}` }
            })
        ]);

        if (!walletsRes.ok || !categoriesRes.ok) {
            throw new Error('Failed to fetch master data');
        }

        const walletsData = await walletsRes.json();
        const categoriesData = await categoriesRes.json();

        // Update in-memory data
        window.appData.allWallets = walletsData.data || [];
        window.appData.allCategories = categoriesData.data || [];

        // Update localStorage cache
        localStorage.setItem(CACHE_KEYS.WALLETS, JSON.stringify(window.appData.allWallets));
        localStorage.setItem(CACHE_KEYS.CATEGORIES, JSON.stringify(window.appData.allCategories));

        // Update sync timestamp
        window.appData.lastSync = new Date().toISOString();
        localStorage.setItem(CACHE_KEYS.LAST_SYNC, window.appData.lastSync);

        // Trigger rendering if callback exists
        if (window.onMasterDataReady) {
            window.onMasterDataReady();
        }
    } catch (err) {
        console.error('Failed to fetch master data:', err);
        // If offline, at least use cached data if available
        const cachedWallets = localStorage.getItem(CACHE_KEYS.WALLETS);
        const cachedCategories = localStorage.getItem(CACHE_KEYS.CATEGORIES);
        if (cachedWallets) window.appData.allWallets = JSON.parse(cachedWallets);
        if (cachedCategories) window.appData.allCategories = JSON.parse(cachedCategories);
    }
}

/**
 * Background sync - updates cache silently
 * Called after initial render to keep cache fresh
 */
async function backgroundSyncMasterData() {
    const token = localStorage.getItem('api_token');

    try {
        const [walletsRes, categoriesRes] = await Promise.all([
            fetch('/api/wallets', {
                headers: { 'Authorization': `Bearer ${token}` }
            }),
            fetch('/api/categories', {
                headers: { 'Authorization': `Bearer ${token}` }
            })
        ]);

        if (walletsRes.ok) {
            const walletsData = await walletsRes.json();
            const newWallets = walletsData.data || [];
            
            // Check if data changed
            if (JSON.stringify(newWallets) !== JSON.stringify(window.appData.allWallets)) {
                window.appData.allWallets = newWallets;
                localStorage.setItem(CACHE_KEYS.WALLETS, JSON.stringify(newWallets));
                
                // Trigger update callback if exists
                if (window.onWalletsUpdated) {
                    window.onWalletsUpdated();
                }
            }
        }

        if (categoriesRes.ok) {
            const categoriesData = await categoriesRes.json();
            const newCategories = categoriesData.data || [];
            
            // Check if data changed
            if (JSON.stringify(newCategories) !== JSON.stringify(window.appData.allCategories)) {
                window.appData.allCategories = newCategories;
                localStorage.setItem(CACHE_KEYS.CATEGORIES, JSON.stringify(newCategories));
                
                // Trigger update callback if exists
                if (window.onCategoriesUpdated) {
                    window.onCategoriesUpdated();
                }
            }
        }

        // Update sync timestamp
        window.appData.lastSync = new Date().toISOString();
        localStorage.setItem(CACHE_KEYS.LAST_SYNC, window.appData.lastSync);
    } catch (err) {
        console.log('Background sync failed (app will use cached data):', err.message);
    }
}

/**
 * Add wallet to cache (after create)
 */
function addWalletToCache(wallet) {
    window.appData.allWallets.push(wallet);
    localStorage.setItem(CACHE_KEYS.WALLETS, JSON.stringify(window.appData.allWallets));
    if (window.onWalletsUpdated) window.onWalletsUpdated();
}

/**
 * Update wallet in cache (after edit)
 */
function updateWalletInCache(walletId, updatedWallet) {
    const index = window.appData.allWallets.findIndex(w => w.id === walletId);
    if (index !== -1) {
        window.appData.allWallets[index] = updatedWallet;
        localStorage.setItem(CACHE_KEYS.WALLETS, JSON.stringify(window.appData.allWallets));
        if (window.onWalletsUpdated) window.onWalletsUpdated();
    }
}

/**
 * Remove wallet from cache (after delete)
 */
function removeWalletFromCache(walletId) {
    window.appData.allWallets = window.appData.allWallets.filter(w => w.id !== walletId);
    localStorage.setItem(CACHE_KEYS.WALLETS, JSON.stringify(window.appData.allWallets));
    if (window.onWalletsUpdated) window.onWalletsUpdated();
}

/**
 * Add category to cache (after create)
 */
function addCategoryToCache(category) {
    window.appData.allCategories.push(category);
    localStorage.setItem(CACHE_KEYS.CATEGORIES, JSON.stringify(window.appData.allCategories));
    if (window.onCategoriesUpdated) window.onCategoriesUpdated();
}

/**
 * Update category in cache (after edit)
 */
function updateCategoryInCache(categoryId, updatedCategory) {
    const index = window.appData.allCategories.findIndex(c => c.id === categoryId);
    if (index !== -1) {
        window.appData.allCategories[index] = updatedCategory;
        localStorage.setItem(CACHE_KEYS.CATEGORIES, JSON.stringify(window.appData.allCategories));
        if (window.onCategoriesUpdated) window.onCategoriesUpdated();
    }
}

/**
 * Remove category from cache (after delete)
 */
function removeCategoryFromCache(categoryId) {
    window.appData.allCategories = window.appData.allCategories.filter(c => c.id !== categoryId);
    localStorage.setItem(CACHE_KEYS.CATEGORIES, JSON.stringify(window.appData.allCategories));
    if (window.onCategoriesUpdated) window.onCategoriesUpdated();
}

/**
 * Clear all cache (useful for logout or troubleshooting)
 */
function clearMasterDataCache() {
    localStorage.removeItem(CACHE_KEYS.WALLETS);
    localStorage.removeItem(CACHE_KEYS.CATEGORIES);
    localStorage.removeItem(CACHE_KEYS.LAST_SYNC);
    window.appData = {
        allWallets: [],
        allCategories: [],
        lastSync: null
    };
}

/**
 * Get wallet by ID
 */
function getWalletById(id) {
    return window.appData.allWallets.find(w => w.id === parseInt(id));
}

/**
 * Get category by ID
 */
function getCategoryById(id) {
    return window.appData.allCategories.find(c => c.id === parseInt(id));
}

/**
 * Get all wallets
 */
function getAllWallets() {
    return window.appData.allWallets || [];
}

/**
 * Get all categories
 */
function getAllCategories() {
    return window.appData.allCategories || [];
}
