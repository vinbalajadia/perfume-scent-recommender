<div class="hero-section">
    <div class="hero-container">
        <div class="hero-content">
            <h1 class="hero-title">Find Your Perfect Scent</h1>
            <p class="hero-subtitle">Describe the fragrance you're looking for and discover your next favorite perfume</p>
            
            <form class="search-form" method="POST" action="index.php?action=search">
                <div class="form-group">
                    <label for="scent_description" class="form-label">
                        <i class="fas fa-search"></i> Describe your ideal scent
                    </label>
                    <textarea 
                        id="scent_description" 
                        name="scent_description" 
                        class="form-textarea"
                        placeholder="e.g., fresh and citrusy for summer, warm vanilla and woody notes, floral and romantic, spicy and mysterious..."
                        rows="4"
                        required
                    ></textarea>
                </div>
                
                <button type="submit" class="btn-primary">
                    <i class="fas fa-magic"></i> Find My Perfume
                </button>
            </form>
        </div>
        
        <div class="hero-image">
            <div class="perfume-bottles">
                <div class="bottle bottle-1"><i class="fas fa-flask"></i></div>
                <div class="bottle bottle-2"><i class="fas fa-vial"></i></div>
                <div class="bottle bottle-3"><i class="fas fa-spray-can"></i></div>
            </div>
        </div>
    </div>
</div>

<section class="features-section">
    <div class="container">
        <h2 class="section-title">How It Works</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-edit"></i>
                </div>
                <h3>Describe</h3>
                <p>Tell us about the scent you're looking for - notes, mood, occasion, or feeling</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <h3>Analyze</h3>
                <p>Our system analyzes your description and matches it with thousands of fragrances</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3>Discover</h3>
                <p>Get personalized recommendations with direct links to detailed reviews</p>
            </div>
        </div>
    </div>
</section>

<section class="popular-notes">
    <div class="container">
        <h2 class="section-title">Popular Fragrance Notes</h2>
        <div class="notes-grid">
            <div class="note-tag" onclick="fillDescription('fresh citrus bergamot lemon')">Citrus</div>
            <div class="note-tag" onclick="fillDescription('warm vanilla sweet gourmand')">Vanilla</div>
            <div class="note-tag" onclick="fillDescription('woody cedar sandalwood masculine')">Woody</div>
            <div class="note-tag" onclick="fillDescription('floral rose jasmine romantic feminine')">Floral</div>
            <div class="note-tag" onclick="fillDescription('spicy pepper cinnamon bold')">Spicy</div>
            <div class="note-tag" onclick="fillDescription('fresh aquatic ocean clean')">Aquatic</div>
            <div class="note-tag" onclick="fillDescription('musky amber sensual evening')">Musky</div>
            <div class="note-tag" onclick="fillDescription('green herbal fresh natural')">Green</div>
        </div>
    </div>
</section>