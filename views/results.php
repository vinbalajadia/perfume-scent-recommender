<div class="results-section">
    <div class="container">
        <div class="results-header">
            <h1 class="results-title">
                <i class="fas fa-magic"></i> Scent Recommendations
            </h1>
            
            <?php if (!empty($query)): ?>
                <div class="search-query">
                    <p><strong>You searched for:</strong> "<?php echo htmlspecialchars($query); ?>"</p>
                </div>
            <?php endif; ?>
            
            <a href="index.php" class="btn-secondary">
                <i class="fas fa-arrow-left"></i> New Search
            </a>
        </div>

        <?php if (!empty($recommendations)): ?>
            <div class="results-count">
                <p><?php echo count($recommendations); ?> perfume(s) found</p>
            </div>
            
            <div class="perfumes-grid">
                <?php foreach ($recommendations as $perfume): ?>
                    <div class="perfume-card">
                        <div class="perfume-image">
                            <?php if ($perfume->getImage()): ?>
                                <img src="<?php echo htmlspecialchars($perfume->getImage()); ?>" 
                                     alt="<?php echo htmlspecialchars($perfume->getName()); ?>">
                            <?php else: ?>
                                <div class="placeholder-image">
                                    <i class="fas fa-spray-can"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="perfume-info">
                            <h3 class="perfume-name"><?php echo htmlspecialchars($perfume->getName()); ?></h3>
                            <p class="perfume-brand"><?php echo htmlspecialchars($perfume->getBrand()); ?></p>
                            
                            <?php if ($perfume->getRating()): ?>
                                <div class="perfume-rating">
                                    <div class="stars">
                                        <?php 
                                        $rating = $perfume->getRating();
                                        for ($i = 1; $i <= 5; $i++): 
                                            if ($i <= floor($rating)): ?>
                                                <i class="fas fa-star"></i>
                                            <?php elseif ($i <= ceil($rating)): ?>
                                                <i class="fas fa-star-half-alt"></i>
                                            <?php else: ?>
                                                <i class="far fa-star"></i>
                                            <?php endif;
                                        endfor; ?>
                                    </div>
                                    <span class="rating-value"><?php echo $rating; ?>/5</span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($perfume->getPrice()): ?>
                                <p class="perfume-price"><?php echo htmlspecialchars($perfume->getPrice()); ?></p>
                            <?php endif; ?>
                            
                            <p class="perfume-description"><?php echo htmlspecialchars($perfume->getDescription()); ?></p>
                            
                            <?php if (!empty($perfume->getNotes())): ?>
                                <div class="perfume-notes">
                                    <h4>Notes:</h4>
                                    <div class="notes-tags">
                                        <?php foreach ($perfume->getNotes() as $note): ?>
                                            <span class="note-tag"><?php echo htmlspecialchars($note); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($perfume->getTypes())): ?>
                                <div class="perfume-types">
                                    <h4>Type:</h4>
                                    <div class="type-tags">
                                        <?php foreach ($perfume->getTypes() as $type): ?>
                                            <span class="type-tag"><?php echo htmlspecialchars($type); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="perfume-actions">
                                <a href="<?php echo $controller->getFragranticaLink($perfume); ?>" 
                                   target="_blank" 
                                   class="btn-primary">
                                    <i class="fas fa-external-link-alt"></i> View on Fragrantica
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
        <?php else: ?>
            <div class="no-results">
                <div class="no-results-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h2>No perfumes found</h2>
                <p>We couldn't find any perfumes matching your description. Try different keywords or be more specific about the notes you're looking for.</p>
                <a href="index.php" class="btn-primary">Try Another Search</a>
            </div>
        <?php endif; ?>
    </div>
</div>