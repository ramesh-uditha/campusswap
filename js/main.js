/**
 * CampusSwap - Client-Side Interactive Script
 * Course: ICT 2209 Web Technologies Mini Project
 * Rajarata University of Sri Lanka
 */

document.addEventListener('DOMContentLoaded', function () {
    'use strict';

    // -------------------------------------------------------------
    // 1. Password Visibility Toggle
    // -------------------------------------------------------------
    const togglePasswordBtns = document.querySelectorAll('.toggle-password-btn');
    togglePasswordBtns.forEach(btn => {
        btn.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (targetInput) {
                if (targetInput.type === 'password') {
                    targetInput.type = 'text';
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    targetInput.type = 'password';
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            }
        });
    });

    // -------------------------------------------------------------
    // 2. Marketplace Dynamic Filtering & Real-Time Search
    // -------------------------------------------------------------
    const searchInput = document.getElementById('marketplaceSearch');
    const clearSearchBtn = document.getElementById('clearSearchBtn');
    const categoryFilterBtns = document.querySelectorAll('.category-filter-btn');
    const sortSelect = document.getElementById('sortSelect');
    const productGrid = document.getElementById('productsGrid');
    const productCols = document.querySelectorAll('.product-card-col');
    const itemCountDisplay = document.getElementById('itemCountDisplay');
    const noResultsAlert = document.getElementById('noResultsAlert');
    const resetFiltersBtn = document.getElementById('resetFiltersBtn');

    let currentCategory = 'all';
    let searchQuery = '';

    function filterAndSortProducts() {
        if (!productGrid) return;

        let visibleCount = 0;
        const itemsArray = Array.from(productCols);

        itemsArray.forEach(cardCol => {
            const itemCat = cardCol.getAttribute('data-category') || '';
            const itemTitle = (cardCol.getAttribute('data-title') || '').toLowerCase();
            const itemDesc = (cardCol.getAttribute('data-description') || '').toLowerCase();

            const matchesCategory = (currentCategory === 'all' || itemCat.toLowerCase() === currentCategory.toLowerCase());
            const matchesQuery = (!searchQuery || itemTitle.includes(searchQuery) || itemDesc.includes(searchQuery));

            if (matchesCategory && matchesQuery) {
                cardCol.style.display = '';
                visibleCount++;
            } else {
                cardCol.style.display = 'none';
            }
        });

        // Update item count badge
        if (itemCountDisplay) {
            itemCountDisplay.textContent = visibleCount;
        }

        // Show/hide no results message
        if (noResultsAlert) {
            if (visibleCount === 0) {
                noResultsAlert.classList.remove('d-none');
            } else {
                noResultsAlert.classList.add('d-none');
            }
        }
    }

    // Search input listener
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            searchQuery = this.value.trim().toLowerCase();
            filterAndSortProducts();
        });
    }

    // Clear search button
    if (clearSearchBtn && searchInput) {
        clearSearchBtn.addEventListener('click', function () {
            searchInput.value = '';
            searchQuery = '';
            filterAndSortProducts();
            searchInput.focus();
        });
    }

    // Category button click listener
    if (categoryFilterBtns.length > 0) {
        categoryFilterBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                categoryFilterBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentCategory = this.getAttribute('data-category') || 'all';
                filterAndSortProducts();
            });
        });
    }

    // Sorting functionality
    if (sortSelect && productGrid) {
        sortSelect.addEventListener('change', function () {
            const sortMode = this.value;
            const itemsArray = Array.from(productCols);

            itemsArray.sort((a, b) => {
                const priceA = parseFloat(a.getAttribute('data-price')) || 0;
                const priceB = parseFloat(b.getAttribute('data-price')) || 0;
                const titleA = a.getAttribute('data-title') || '';
                const titleB = b.getAttribute('data-title') || '';

                if (sortMode === 'price-low') {
                    return priceA - priceB;
                } else if (sortMode === 'price-high') {
                    return priceB - priceA;
                } else if (sortMode === 'title-asc') {
                    return titleA.localeCompare(titleB);
                }
                return 0;
            });

            // Re-append sorted elements
            itemsArray.forEach(item => productGrid.appendChild(item));
        });
    }

    // Reset filters button
    if (resetFiltersBtn) {
        resetFiltersBtn.addEventListener('click', function () {
            if (searchInput) searchInput.value = '';
            searchQuery = '';
            currentCategory = 'all';
            categoryFilterBtns.forEach(b => {
                if (b.getAttribute('data-category') === 'all') {
                    b.classList.add('active');
                } else {
                    b.classList.remove('active');
                }
            });
            if (sortSelect) sortSelect.value = 'default';
            filterAndSortProducts();
        });
    }

    // -------------------------------------------------------------
    // 3. Item Quick Details Modal Event Handling
    // -------------------------------------------------------------
    const detailButtons = document.querySelectorAll('.view-details-btn');
    const modalImage = document.getElementById('modalItemImage');
    const modalCategory = document.getElementById('modalItemCategory');
    const modalCondition = document.getElementById('modalItemCondition');
    const modalTitle = document.getElementById('modalItemTitle');
    const modalPrice = document.getElementById('modalItemPrice');
    const modalDesc = document.getElementById('modalItemDesc');
    const modalSeller = document.getElementById('modalItemSeller');
    const modalContactLink = document.getElementById('modalContactLink');
    const detailsModalElement = document.getElementById('itemDetailsModal');

    if (detailButtons.length > 0 && detailsModalElement) {
        const bsModal = new bootstrap.Modal(detailsModalElement);

        detailButtons.forEach(btn => {
            btn.addEventListener('click', function () {
                const title = this.getAttribute('data-title');
                const cat = this.getAttribute('data-category');
                const price = this.getAttribute('data-price');
                const cond = this.getAttribute('data-condition');
                const desc = this.getAttribute('data-desc');
                const img = this.getAttribute('data-image');
                const seller = this.getAttribute('data-seller');

                if (modalTitle) modalTitle.textContent = title;
                if (modalCategory) modalCategory.textContent = cat;
                if (modalCondition) modalCondition.textContent = cond;
                if (modalPrice) modalPrice.textContent = price;
                if (modalDesc) modalDesc.textContent = desc;
                if (modalImage) modalImage.src = img;
                if (modalSeller) modalSeller.textContent = seller;
                if (modalContactLink) {
                    modalContactLink.href = 'contact.php?subject=' + encodeURIComponent('Inquiry regarding: ' + title);
                }

                bsModal.show();
            });
        });
    }

    // -------------------------------------------------------------
    // 4. Contact Form Live Character Counter
    // -------------------------------------------------------------
    const messageTextarea = document.getElementById('message');
    const charCountDisplay = document.getElementById('charCount');

    if (messageTextarea && charCountDisplay) {
        const updateCharCount = () => {
            const count = messageTextarea.value.length;
            charCountDisplay.textContent = `${count} / 500 characters`;
            if (count > 480) {
                charCountDisplay.classList.add('text-danger');
            } else {
                charCountDisplay.classList.remove('text-danger');
            }
        };

        messageTextarea.addEventListener('input', updateCharCount);
        updateCharCount();
    }

    // -------------------------------------------------------------
    // 5. Bootstrap 5 Form Client-side Validation Feedback
    // -------------------------------------------------------------
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // -------------------------------------------------------------
    // 6. Smooth Scrolling for Anchor Links
    // -------------------------------------------------------------
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetSelector = this.getAttribute('href');
            if (targetSelector.length > 1) {
                const target = document.querySelector(targetSelector);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });

    // -------------------------------------------------------------
    // 7. Auto Dismiss Alerts after 6 seconds
    // -------------------------------------------------------------
    const autoAlerts = document.querySelectorAll('.alert-dismissible');
    autoAlerts.forEach(alertEl => {
        setTimeout(() => {
            try {
                const alertInstance = bootstrap.Alert.getOrCreateInstance(alertEl);
                alertInstance.close();
            } catch (e) {
                // Alert might already be closed by user
            }
        }, 6000);
    });
});
