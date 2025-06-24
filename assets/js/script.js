document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    const searchForm = document.querySelector('.search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', validateSearchForm);
    }
    
    addLoadingStates();
    addSmoothScrolling();
    addInteractiveElements();
}

function validateSearchForm(e) {
    const textarea = document.getElementById('scent_description');
    const value = textarea.value.trim();
    
    if (value.length < 3) {
        e.preventDefault();
        showAlert('Please enter at least 3 characters to describe your ideal scent.', 'error');
        textarea.focus();
        return false;
    }
    
    if (value.length > 500) {
        e.preventDefault();
        showAlert('Please keep your description under 500 characters.', 'error');
        textarea.focus();
        return false;
    }

    const submitBtn = e.target.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Searching...';
        submitBtn.disabled = true;
    }
    
    return true;
}

function fillDescription(description) {
    const textarea = document.getElementById('scent_description');
    if (textarea) {
        if (textarea.value.trim() === '') {
            textarea.value = description;
        } 
        else {
            textarea.value += ' ' + description;
        }
        
        textarea.focus();
        textarea.setSelectionRange(textarea.value.length, textarea.value.length);
        
        const noteTag = event.target;
        noteTag.style.transform = 'scale(0.95)';
        setTimeout(() => {
            noteTag.style.transform = '';
        }, 150);
    }
}

function showAlert(message, type = 'info') {
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.innerHTML = `
        <i class="fas fa-${type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
        ${message}
        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
    `;
    
    const style = document.createElement('style');
    style.textContent = `
        .alert {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .alert-close {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            margin-left: auto;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }
        .alert-close:hover {
            opacity: 1;
        }
    `;
    document.head.appendChild(style);
    
    const mainContent = document.querySelector('.main-content');
    if (mainContent) {
        mainContent.insertBefore(alert, mainContent.firstChild);
    }
    
    setTimeout(() => {
        if (alert.parentElement) {
            alert.remove();
        }
    }, 5000);
}

function addLoadingStates() {
    const buttons = document.querySelectorAll('.btn-primary, .btn-secondary');
    
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.type === 'submit' || this.target === '_blank') {
                const originalText = this.innerHTML;
                
                if (this.type === 'submit') {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                    this.disabled = true;
                } 
                else if (this.target === '_blank') {
                    this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Opening...';
                    
                    // Reset after a short delay
                    setTimeout(() => {
                        this.innerHTML = originalText;
                    }, 1000);
                }
            }
        });
    });
}

function addSmoothScrolling() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

function addInteractiveElements() {
    const perfumeCards = document.querySelectorAll('.perfume-card');
    
    perfumeCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
    
    const noteTags = document.querySelectorAll('.note-tag');
    
    noteTags.forEach(tag => {
        tag.addEventListener('click', function() {
            // Add ripple effect
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            
            const ripple = document.createElement('span');
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            `;
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = (event.clientX - rect.left - size / 2) + 'px';
            ripple.style.top = (event.clientY - rect.top - size / 2) + 'px';
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
}

function addCharacterCounter() {
    const textarea = document.getElementById('scent_description');
    if (textarea) {
        const maxLength = 500;
        
        const counter = document.createElement('div');
        counter.className = 'character-counter';
        counter.style.cssText = `
            text-align: right;
            font-size: 0.8rem;
            color: #666;
            margin-top: 0.5rem;
        `;
        
        textarea.parentNode.insertBefore(counter, textarea.nextSibling);
        
        function updateCounter() {
            const remaining = maxLength - textarea.value.length;
            counter.textContent = `${textarea.value.length}/${maxLength} characters`;
            
            if (remaining < 50) {
                counter.style.color = '#dc3545';
            } else if (remaining < 100) {
                counter.style.color = '#ffc107';
            } else {
                counter.style.color = '#666';
            }
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter(); // Initial update
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        showAlert('Copied to clipboard!', 'success');
    }).catch(() => {
        showAlert('Failed to copy to clipboard', 'error');
    });
}

function addSearchSuggestions() {
    const suggestions = [
        "fresh and citrusy for summer",
        "warm vanilla and woody notes",
        "floral and romantic",
        "spicy and mysterious",
        "clean and aquatic",
        "sweet gourmand dessert-like",
        "green and herbal",
        "musky and sensual",
        "fruity and playful",
        "oriental and exotic"
    ];
    
    const textarea = document.getElementById('scent_description');
    if (textarea) {
        textarea.addEventListener('focus', function() {
            if (this.value.trim() === '') {
                const randomSuggestion = suggestions[Math.floor(Math.random() * suggestions.length)];
                this.placeholder = `e.g., ${randomSuggestion}`;
            }
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    addCharacterCounter();
    addSearchSuggestions();
});

window.addEventListener('popstate', function(e) {
    // Handle any cleanup if needed when user navigates back
    const submitBtn = document.querySelector('button[type="submit"]');
    if (submitBtn && submitBtn.disabled) {
        submitBtn.innerHTML = '<i class="fas fa-magic"></i> Find My Perfume';
        submitBtn.disabled = false;
    }
});

function addLazyLoading() {
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
}

document.addEventListener('DOMContentLoaded', addLazyLoading);