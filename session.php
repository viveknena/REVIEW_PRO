<?php
session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = htmlspecialchars(trim($_POST['product_name'] ?? ''));
    $product_category = htmlspecialchars(trim($_POST['product_category'] ?? ''));
    $review_tone = htmlspecialchars(trim($_POST['review_tone'] ?? 'balanced'));
    
    // File upload handling
    $image_path = '';
    $has_image = false;
    
    if (isset($_FILES['product_photo']) && $_FILES['product_photo']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg', 'image/webp'];
        $file_type = $_FILES['product_photo']['type'];
        $file_size = $_FILES['product_photo']['size'];
        
        if (in_array($file_type, $allowed_types) && $file_size <= 5 * 1024 * 1024) {
            if (!file_exists('uploads')) {
                mkdir('uploads', 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['product_photo']['name'], PATHINFO_EXTENSION));
            $filename = 'product_' . time() . '_' . uniqid() . '.' . $file_extension;
            $upload_path = 'uploads/' . $filename;
            
            if (move_uploaded_file($_FILES['product_photo']['tmp_name'], $upload_path)) {
                $image_path = $upload_path;
                $has_image = true;
                
                // Save in session for persistence
                $_SESSION['last_image'] = $image_path;
            }
        }
    }
    
    // Generate AI review
    $review = generateAIReview($product_name, $product_category, $review_tone, $has_image);
    
    // Save in session
    $_SESSION['last_review'] = $review;
    $_SESSION['last_product_name'] = $product_name;
    $_SESSION['form_data'] = [
        'product_name' => $product_name,
        'product_category' => $product_category,
        'review_tone' => $review_tone
    ];
    
    // Redirect to avoid form resubmission
    header('Location: ' . $_SERVER['PHP_SELF'] . '?review=generated');
    exit;
}

// Function to generate AI review
function generateAIReview($product_name, $category, $tone, $has_image) {
    if (empty($product_name)) {
        return '<div class="error-message">Please enter a product name.</div>';
    }
    
    // Product type determination
    $product_data = getProductData($product_name, $category);
    $image_note = $has_image ? 
        'The product image shows a well-designed device with attention to detail in construction.' : 
        'Note: Review would be more accurate with a product image for visual analysis.';
    
    // Adjust tone
    $tone_intro = getToneIntro($tone);
    $tone_style = getToneStyle($tone);
    
    return "
        <h2 class='review-title'>{$product_name} - AI Review Analysis</h2>
        
        <div class='review-section at-a-glance'>
            <h3><i class='fas fa-bolt'></i> At a Glance</h3>
            <p><strong>Product Type:</strong> {$product_data['type']}</p>
            <p><strong>Best For:</strong> {$product_data['best_for']}</p>
            <p><strong>Price Range:</strong> {$product_data['price_range']}</p>
            <p><strong>Overall Rating:</strong> {$product_data['rating']}/5 stars</p>
            <p><strong>Image Analysis:</strong> {$image_note}</p>
            <p><strong>Review Tone:</strong> " . ucfirst($tone) . "</p>
        </div>
        
        <div class='review-section'>
            <h3><i class='fas fa-box-open'></i> First Impressions</h3>
            <p>{$tone_intro} The {$product_name} presents itself as a premium {$product_data['type']} right from unboxing. The packaging is professional and secure, with all essential components thoughtfully arranged.</p>
            <p>{$tone_style} Initial setup is intuitive, taking approximately 5-10 minutes to get fully operational. The device feels substantial in hand, suggesting durable materials and careful construction.</p>
        </div>
        
        <div class='review-section'>
            <h3><i class='fas fa-palette'></i> Design & Build Quality</h3>
            <p>Visually, the {$product_name} adopts a modern aesthetic that balances form and function. The finish appears consistent throughout, with no noticeable gaps or imperfections.</p>
            <p>Based on typical {$product_data['type']} construction, this product should withstand daily use well. Materials seem chosen for both durability and visual appeal.</p>
        </div>
        
        <div class='review-section'>
            <h3><i class='fas fa-tachometer-alt'></i> Performance & Features</h3>
            <p>In operation, the {$product_name} delivers solid performance across key metrics. {$product_data['features'][0]} ensures responsive operation, while {$product_data['features'][1]} provides practical utility.</p>
            <p>Key features work cohesively, creating a user experience that's accessible to beginners while offering depth for advanced users. The {$product_name} particularly excels at its core function.</p>
            <p><strong>Key Features:</strong> " . implode(', ', $product_data['features']) . "</p>
        </div>
        
        <div class='review-section'>
            <h3><i class='fas fa-balance-scale'></i> Pros & Cons</h3>
            <p><strong>Advantages:</strong></p>
            <ul>
                <li>" . implode('</li><li>', $product_data['pros']) . "</li>
            </ul>
            <p><strong>Considerations:</strong></p>
            <ul>
                <li>" . implode('</li><li>', $product_data['cons']) . "</li>
            </ul>
        </div>
        
        <div class='review-section'>
            <h3><i class='fas fa-gavel'></i> Final Verdict</h3>
            <p><strong>Who Should Buy:</strong> The {$product_name} is ideal for users who prioritize " . strtolower($product_data['pros'][0]) . " and need a reliable {$product_data['type']} for daily use. It's particularly suited for those willing to invest in a premium experience.</p>
            <p><strong>Who Should Look Elsewhere:</strong> If " . strtolower($product_data['cons'][0]) . " is a deal-breaker, or if you're on a very tight budget, you might find more suitable options elsewhere.</p>
            <p><strong>Bottom Line:</strong> The {$product_name} successfully combines " . strtolower($product_data['pros'][0]) . " with " . strtolower($product_data['pros'][1]) . ", making it a compelling choice in its category. While not perfect, its strengths significantly outweigh its weaknesses for the target audience.</p>
        </div>
        
        <div class='review-section'>
            <p><em>Note: This AI-generated review is based on product analysis and typical category performance. Always verify specific details with the manufacturer and check recent user reviews before purchasing.</em></p>
        </div>
    ";
}

function getProductData($name, $category) {
    $name = strtolower($name);
    $data = [
        'type' => 'electronic device',
        'best_for' => 'Tech enthusiasts, daily users',
        'price_range' => 'Mid to High',
        'rating' => '4.2',
        'features' => ['High-performance processor', 'Long-lasting battery', 'Premium materials'],
        'pros' => ['Excellent build quality', 'Impressive performance', 'Good value for money'],
        'cons' => ['Slightly heavy', 'Learning curve for some features', 'Accessories sold separately']
    ];
    
    if ($category === 'audio' || strpos($name, 'headphone') !== false || 
        strpos($name, 'earbud') !== false || strpos($name, 'speaker') !== false) {
        $data = [
            'type' => 'audio device',
            'best_for' => 'Music lovers, commuters, office workers',
            'price_range' => 'Premium',
            'rating' => '4.5',
            'features' => ['High-fidelity sound', 'Active noise cancellation', '30-hour battery life', 'Bluetooth 5.2'],
            'pros' => ['Crystal clear audio quality', 'Comfortable for long sessions', 'Effective noise cancellation'],
            'cons' => ['Case is slightly bulky', 'Touch controls can be sensitive', 'Premium price point']
        ];
    } elseif ($category === 'electronics' || strpos($name, 'phone') !== false || 
              strpos($name, 'laptop') !== false || strpos($name, 'tablet') !== false) {
        $data = [
            'type' => 'tech gadget',
            'best_for' => 'Professionals, students, tech enthusiasts',
            'price_range' => 'High-end',
            'rating' => '4.3',
            'features' => ['High-resolution display', 'Fast charging support', 'Multiple connectivity options'],
            'pros' => ['Sleek and modern design', 'Powerful performance', 'Excellent display quality'],
            'cons' => ['Battery life could be better', 'Gets warm under heavy load', 'Limited color options']
        ];
    } elseif ($category === 'home') {
        $data = [
            'type' => 'home appliance',
            'best_for' => 'Homeowners, busy families',
            'price_range' => 'Mid-range',
            'rating' => '4.1',
            'features' => ['Energy efficient', 'Multiple operating modes', 'Easy to clean'],
            'pros' => ['Very effective at its job', 'Easy to use controls', 'Durable construction'],
            'cons' => ['A bit expensive', 'Large footprint', 'Manual could be clearer']
        ];
    } elseif ($category === 'fashion') {
        $data = [
            'type' => 'wearable device',
            'best_for' => 'Fashion-conscious users, fitness enthusiasts',
            'price_range' => 'Varied',
            'rating' => '4.0',
            'features' => ['Comfortable fit', 'Multiple style options', 'Durable materials'],
            'pros' => ['Stylish design', 'Comfortable to wear', 'Versatile'],
            'cons' => ['Limited size options', 'Requires special care', 'May not fit all styles']
        ];
    }
    
    return $data;
}

function getToneIntro($tone) {
    switch($tone) {
        case 'technical': return 'From a technical standpoint,';
        case 'casual': return 'Honestly speaking,';
        case 'professional': return 'Professionally evaluated,';
        default: return 'Overall,';
    }
}

function getToneStyle($tone) {
    switch($tone) {
        case 'technical': return 'The technical implementation shows careful engineering choices.';
        case 'casual': return 'For everyday use, it feels just right.';
        case 'professional': return 'Professionally, it meets industry standards.';
        default: return 'The user experience is well-considered.';
    }
}
?>