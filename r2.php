
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>REVIEWPRO | Smart Product Reviews</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
	<link rel="session" href="session.php">
	
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <a href="#" class="logo">
                <i class="fas fa-robot logo-icon"></i>
                <span class="logo-text">REVIEWPRO</span>
            </a>
            
            <div class="nav-right">
                <div class="auth-buttons">
                    <button class="btn-login" id="loginBtn">
                        <i class="fas fa-sign-in-alt"></i> <span class="btn-text">Login</span>
                    </button>
                    <button class="btn-signup" id="signupBtn">
                        <i class="fas fa-user-plus"></i> <span class="btn-text">Sign Up</span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Generate Smart Product Reviews with AI</h1>
        <p class="hero-subtitle">Upload a product photo, enter the name, and let our AI generate a comprehensive, balanced review in seconds. Make informed decisions faster.</p>
        
        <div class="stats">
            <div class="stat-item">
                <span class="stat-number">10K+</span>
                <span class="stat-label">Reviews Generated</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">4.8</span>
                <span class="stat-label">Avg Rating</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">98%</span>
                <span class="stat-label">Accuracy</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">24/7</span>
                <span class="stat-label">AI Support</span>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Input Section -->
        <div class="input-section">
            <h2 class="section-title">
                <i class="fas fa-edit"></i> <span class="section-title-text">Product Details</span>
            </h2>
            
            <form method="POST" enctype="multipart/form-data" id="reviewForm">
                <div class="form-group">
                    <label for="productName">
                        <i class="fas fa-tag"></i> Product Name & Model
                    </label>
                    <input type="text" id="productName" name="product_name" class="form-input" 
                           placeholder="e.g., SoundBlast Pro X200 Wireless Earbuds" 
                           value="<?php echo htmlspecialchars($_SESSION['form_data']['product_name'] ?? ''); ?>" 
                           required>
                </div>
                
                <div class="form-group">
                    <label for="productPhoto">
                        <i class="fas fa-camera"></i> Product Photo (Optional)
                    </label>
                    <div class="upload-area" id="uploadArea">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click to upload or drag and drop</p>
                        <p class="file-types">JPG, PNG, GIF, WEBP up to 5MB</p>
                        <input type="file" id="productPhoto" name="product_photo" accept="image/*" hidden>
                    </div>
                    <div class="preview-container" id="previewContainer">
                        <div class="preview-image-container">
                            <img id="imagePreview" class="preview-image" src="" alt="Product Preview">
                            <button type="button" id="removeImage" class="remove-btn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="productCategory">
                        <i class="fas fa-list"></i> Product Category
                    </label>
                    <select id="productCategory" name="product_category" class="form-select">
                        <option value="">Select a category</option>
                        <option value="electronics" <?php echo ($_SESSION['form_data']['product_category'] ?? '') == 'electronics' ? 'selected' : ''; ?>>Electronics</option>
                        <option value="audio" <?php echo ($_SESSION['form_data']['product_category'] ?? '') == 'audio' ? 'selected' : ''; ?>>Audio Devices</option>
                        <option value="home" <?php echo ($_SESSION['form_data']['product_category'] ?? '') == 'home' ? 'selected' : ''; ?>>Home Appliances</option>
                        <option value="tech" <?php echo ($_SESSION['form_data']['product_category'] ?? '') == 'tech' ? 'selected' : ''; ?>>Tech Gadgets</option>
                        <option value="fashion" <?php echo ($_SESSION['form_data']['product_category'] ?? '') == 'fashion' ? 'selected' : ''; ?>>Fashion & Wearables</option>
                        <option value="other" <?php echo ($_SESSION['form_data']['product_category'] ?? '') == 'other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="reviewTone">
                        <i class="fas fa-comment"></i> Review Tone
                    </label>
                    <select id="reviewTone" name="review_tone" class="form-select">
                        <option value="balanced" <?php echo ($_SESSION['form_data']['review_tone'] ?? 'balanced') == 'balanced' ? 'selected' : ''; ?>>Balanced & Objective</option>
                        <option value="professional" <?php echo ($_SESSION['form_data']['review_tone'] ?? '') == 'professional' ? 'selected' : ''; ?>>Professional & Detailed</option>
                        <option value="casual" <?php echo ($_SESSION['form_data']['review_tone'] ?? '') == 'casual' ? 'selected' : ''; ?>>Casual & Friendly</option>
                        <option value="technical" <?php echo ($_SESSION['form_data']['review_tone'] ?? '') == 'technical' ? 'selected' : ''; ?>>Technical & Spec-Focused</option>
                    </select>
                </div>
                
                <button type="submit" class="generator-btn" id="generateBtn">
                    <i class="fas fa-robot"></i> <span class="btn-text">Generate AI Review</span>
                </button>
            </form>
        </div>
        
        <!-- Output Section -->
        <div class="output-section">
            <div class="output-header">
                <h2 class="section-title">
                    <i class="fas fa-star"></i> <span class="section-title-text">AI Generated Review</span>
                </h2>
                <div class="output-actions">
                    <button class="action-btn" id="copyBtn" title="Copy to clipboard">
                        <i class="fas fa-copy"></i>
                        <span class="sr-only">Copy review</span>
                    </button>
                    <button class="action-btn" id="downloadBtn" title="Download as text">
                        <i class="fas fa-download"></i>
                        <span class="sr-only">Download review</span>
                    </button>
                    <button class="action-btn" id="shareBtn" title="Share review">
                        <i class="fas fa-share-alt"></i>
                        <span class="sr-only">Share review</span>
                    </button>
                </div>
            </div>
            
            <div class="review-container" id="reviewContainer">
                <?php if(isset($_SESSION['last_review']) && isset($_GET['review']) && $_GET['review'] === 'generated'): ?>
                    <div id="reviewOutput">
                        <?php echo $_SESSION['last_review']; ?>
                    </div>
                <?php else: ?>
                    <div id="reviewPlaceholder" class="placeholder-content">
                        <i class="fas fa-clipboard-list"></i>
                        <h3>Your AI Review Awaits</h3>
                        <p>Enter product details and click "Generate AI Review" to see the magic happen.</p>
                    </div>
                <?php endif; ?>
                
                <div id="loadingContainer" class="loading-container">
                    <div class="loading-spinner"></div>
                    <p class="loading-text">AI is analyzing and generating your review...</p>
                    <div class="loading-steps">
                        <div class="step active"><span>1</span> Analyzing</div>
                        <div class="step"><span>2</span> Researching</div>
                        <div class="step"><span>3</span> Writing</div>
                        <div class="step"><span>4</span> Finalizing</div>
                    </div>
                </div>
            </div>
            
            <?php if(isset($_SESSION['last_review'])): ?>
            <div class="recent-reviews mt-3">
                <h3 class="section-title">
                    <i class="fas fa-history"></i> <span class="section-title-text">Recent Review</span>
                </h3>
                <div class="review-card">
                    <h4><?php echo htmlspecialchars($_SESSION['last_product_name'] ?? 'Previous Product'); ?></h4>
                    <p class="text-muted">Generated on <?php echo date('F j, Y, g:i a'); ?></p>
                    <a href="#" onclick="location.reload(); return false;" class="btn-login" style="display: inline-block; padding: 0.5rem 1rem; margin-top: 0.5rem;">
                        <i class="fas fa-redo"></i> Generate New
                    </a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Footer -->
    <footer>
        <div class="footer-container">
            <div class="footer-column">
                <h3>AI Review Pro</h3>
                <p>Cut through information overload with AI-powered, insightful product reviews that help you make informed decisions faster.</p>
                <div class="social-icons">
                   
                    <a href="#" class="social-icon" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-icon" aria-label="LinkedIn">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="#" class="social-icon" aria-label="GitHub">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>
            
            <div class="footer-column">
                <h3>Quick Links</h3>
                <a href="#">Home</a>
                <a href="#">How It Works</a>
                <a href="#">Examples</a>
                <a href="#">Pricing</a>
                <a href="#">API Access</a>
            </div>
            
          
            
            <div class="footer-column">
                <h3>Contact Info</h3>
                <p><i class="fas fa-map-marker-alt"></i> INDIA</p>
                <p><i class="fas fa-phone"></i> +91 63566 51811</p>
                <p><i class="fas fa-envelope"></i> support@aireviewpro.com</p>
            </div>
        </div>
        
        <div class="copyright">
            <p>&copy; <?php echo date('Y'); ?> AI Review Pro. All rights reserved. | Powered by Advanced AI Technology</p>
            <p class="mt-1"><small>This tool generates AI-powered reviews for demonstration purposes. Always verify with additional research.</small></p>
        </div>
    </footer>
    
    <!-- Notification -->
    <div id="notification" class="notification"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const loginBtn = document.getElementById('loginBtn');
            const signupBtn = document.getElementById('signupBtn');
            const uploadArea = document.getElementById('uploadArea');
            const productPhotoInput = document.getElementById('productPhoto');
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            const removeImageBtn = document.getElementById('removeImage');
            const generateBtn = document.getElementById('generateBtn');
            const reviewForm = document.getElementById('reviewForm');
            const reviewPlaceholder = document.getElementById('reviewPlaceholder');
            const reviewOutput = document.getElementById('reviewOutput');
            const loadingContainer = document.getElementById('loadingContainer');
            const reviewContainer = document.getElementById('reviewContainer');
            const copyBtn = document.getElementById('copyBtn');
            const downloadBtn = document.getElementById('downloadBtn');
            const shareBtn = document.getElementById('shareBtn');
            const notification = document.getElementById('notification');
            
            // Initialize
            initApp();
            
            function initApp() {
                // Check if we have a review from session
                <?php if(isset($_SESSION['last_review']) && isset($_GET['review']) && $_GET['review'] === 'generated'): ?>
                    if (reviewPlaceholder) reviewPlaceholder.style.display = 'none';
                    if (reviewOutput) reviewOutput.style.display = 'block';
                <?php endif; ?>
                
                // Set up event listeners
                setupEventListeners();
            }
            
            function setupEventListeners() {
                // Login/Signup Buttons
                if (loginBtn) {
                    loginBtn.addEventListener('click', function() {
                        showNotification('Login feature coming soon!', 'info');
                    });
                }
                
                if (signupBtn) {
                    signupBtn.addEventListener('click', function() {
                        showNotification('Sign up feature coming soon!', 'info');
                    });
                }
                
                // File Upload
                if (uploadArea && productPhotoInput) {
                    setupFileUpload();
                }
                
                // Form Submission
                if (reviewForm) {
                    reviewForm.addEventListener('submit', handleFormSubmit);
                }
                
                // Action Buttons
                if (copyBtn) {
                    copyBtn.addEventListener('click', copyReviewToClipboard);
                }
                
                if (downloadBtn) {
                    downloadBtn.addEventListener('click', downloadReview);
                }
                
                if (shareBtn) {
                    shareBtn.addEventListener('click', shareReview);
                }
            }
            
            function setupFileUpload() {
                // Click to upload
                uploadArea.addEventListener('click', () => {
                    productPhotoInput.click();
                });
                
                // Drag and drop
                ['dragenter', 'dragover'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, (e) => {
                        e.preventDefault();
                        uploadArea.classList.add('dragover');
                    });
                });
                
                ['dragleave', 'drop'].forEach(eventName => {
                    uploadArea.addEventListener(eventName, (e) => {
                        e.preventDefault();
                        uploadArea.classList.remove('dragover');
                    });
                });
                
                // Handle drop
                uploadArea.addEventListener('drop', (e) => {
                    e.preventDefault();
                    const files = e.dataTransfer.files;
                    if (files.length) {
                        productPhotoInput.files = files;
                        handleImageUpload(files[0]);
                    }
                });
                
                // Handle file selection
                productPhotoInput.addEventListener('change', (e) => {
                    if (e.target.files.length) {
                        handleImageUpload(e.target.files[0]);
                    }
                });
                
                // Remove image
                if (removeImageBtn) {
                    removeImageBtn.addEventListener('click', () => {
                        imagePreview.src = '';
                        previewContainer.style.display = 'none';
                        uploadArea.style.display = 'block';
                        productPhotoInput.value = '';
                    });
                }
            }
            
            function handleImageUpload(file) {
                if (!file.type.match('image.*')) {
                    showNotification('Please upload an image file (JPG, PNG, GIF, WEBP)', 'error');
                    return;
                }
                
                if (file.size > 5 * 1024 * 1024) {
                    showNotification('File size must be less than 5MB', 'error');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    previewContainer.style.display = 'block';
                    uploadArea.style.display = 'none';
                    showNotification('Image uploaded successfully!', 'success');
                };
                reader.readAsDataURL(file);
            }
            
            function handleFormSubmit(e) {
                const productName = document.getElementById('productName');
                if (!productName || !productName.value.trim()) {
                    e.preventDefault();
                    showNotification('Please enter a product name', 'error');
                    productName.focus();
                    return;
                }
                
                // Show loading state
                if (reviewPlaceholder) reviewPlaceholder.style.display = 'none';
                if (reviewOutput) reviewOutput.style.display = 'none';
                if (loadingContainer) loadingContainer.style.display = 'flex';
                
                // Disable submit button
                if (generateBtn) {
                    generateBtn.disabled = true;
                    generateBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> <span class="btn-text">Processing...</span>';
                }
                
                // Simulate loading steps animation
                const steps = document.querySelectorAll('.loading-steps .step');
                let currentStep = 0;
                const stepInterval = setInterval(() => {
                    if (currentStep > 0) steps[currentStep-1].classList.remove('active');
                    if (currentStep < steps.length) steps[currentStep].classList.add('active');
                    currentStep++;
                    
                    if (currentStep > steps.length) {
                        clearInterval(stepInterval);
                    }
                }, 400);
            }
            
            function copyReviewToClipboard() {
                const reviewOutput = document.getElementById('reviewOutput');
                if (!reviewOutput || reviewOutput.style.display === 'none') {
                    showNotification('No review to copy', 'error');
                    return;
                }
                
                const reviewText = reviewOutput.innerText;
                navigator.clipboard.writeText(reviewText).then(() => {
                    showNotification('Review copied to clipboard!', 'success');
                }).catch(err => {
                    console.error('Failed to copy:', err);
                    showNotification('Failed to copy review', 'error');
                });
            }
            
            function downloadReview() {
                const reviewOutput = document.getElementById('reviewOutput');
                if (!reviewOutput || reviewOutput.style.display === 'none') {
                    showNotification('No review to download', 'error');
                    return;
                }
                
                const productName = document.getElementById('productName');
                const productNameValue = productName ? productName.value.trim() : 'product_review';
                const reviewText = reviewOutput.innerText;
                const filename = `${productNameValue.replace(/\s+/g, '_')}_AI_Review_${Date.now()}.txt`;
                
                const element = document.createElement('a');
                element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(reviewText));
                element.setAttribute('download', filename);
                element.style.display = 'none';
                document.body.appendChild(element);
                element.click();
                document.body.removeChild(element);
                
                showNotification('Review downloaded successfully!', 'success');
            }
            
            function shareReview() {
                const reviewOutput = document.getElementById('reviewOutput');
                if (!reviewOutput || reviewOutput.style.display === 'none') {
                    showNotification('No review to share', 'error');
                    return;
                }
                
                if (navigator.share) {
                    const reviewText = reviewOutput.innerText.substring(0, 200) + '...';
                    const productName = document.getElementById('productName').value.trim();
                    
                    navigator.share({
                        title: `${productName} - AI Review`,
                        text: reviewText,
                        url: window.location.href
                    }).then(() => {
                        showNotification('Review shared successfully!', 'success');
                    }).catch(err => {
                        console.error('Error sharing:', err);
                        showNotification('Failed to share review', 'error');
                    });
                } else {
                    showNotification('Web Share API not supported in your browser', 'info');
                }
            }
            
            function showNotification(message, type = 'info') {
                if (!notification) return;
                
                notification.textContent = message;
                notification.className = 'notification';
                notification.classList.add('show');
                
                // Set color based on type
                const colors = {
                    'error': 'var(--danger)',
                    'success': 'var(--success)',
                    'warning': 'var(--warning)',
                    'info': 'var(--primary)'
                };
                
                notification.style.borderLeftColor = colors[type] || 'var(--primary)';
                
                // Auto-hide
                setTimeout(() => {
                    notification.classList.remove('show');
                }, 3000);
            }
            
            // Initialize sample data for demo
            setTimeout(() => {
                const productName = document.getElementById('productName');
                const productCategory = document.getElementById('productCategory');
                
                if (productName && !productName.value && !<?php echo isset($_SESSION['form_data']['product_name']) ? 'true' : 'false'; ?>) {
                    productName.value = 'SoundBlast Pro X200 Wireless Earbuds';
                }
                
                if (productCategory && !productCategory.value && !<?php echo isset($_SESSION['form_data']['product_category']) ? 'true' : 'false'; ?>) {
                    productCategory.value = 'audio';
                }
            }, 100);
            
            // Handle touch events for mobile
            document.addEventListener('touchstart', function() {}, {passive: true});
        });
        
        // Handle page visibility for better mobile performance
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                // Page is hidden
            } else {
                // Page is visible
            }
        });
    </script>
</body>
</html>
<?php
// Clear session data after display
if (isset($_GET['review']) && $_GET['review'] === 'generated') {
    unset($_SESSION['last_review']);
    unset($_SESSION['last_product_name']);
    unset($_SESSION['form_data']);
}
?>