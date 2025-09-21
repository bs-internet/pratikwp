<?php
/**
 * Template part for displaying results in search pages
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

$search_excerpt_length = get_theme_mod('search_excerpt_length', 25);
$highlight_search_terms = get_theme_mod('highlight_search_terms', true);
$search_query = get_search_query();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('search-result-item mb-4 pb-4 border-bottom'); ?>>

    <div class="row">
        
        <?php if (has_post_thumbnail()) : ?>
        <div class="col-md-3">
            <div class="post-thumbnail">
                <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                    <?php
                    the_post_thumbnail('medium', [
                        'class' => 'img-fluid rounded',
                        'loading' => 'lazy'
                    ]);
                    ?>
                </a>
            </div>
        </div>
        <div class="col-md-9">
        <?php else : ?>
        <div class="col-12">
        <?php endif; ?>

            <div class="search-result-content">
                
                <header class="entry-header mb-2">
                    
                    <?php if (get_post_type() !== 'post') : ?>
                    <div class="post-type-badge mb-2">
                        <span class="badge bg-secondary">
                            <?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name); ?>
                        </span>
                    </div>
                    <?php endif; ?>

                    <?php
                    $title = get_the_title();
                    
                    // Highlight search terms in title
                    if ($highlight_search_terms && $search_query) {
                        $title = preg_replace('/(' . preg_quote($search_query, '/') . ')/i', '<mark>$1</mark>', $title);
                    }
                    
                    the_title(
                        '<h3 class="entry-title mb-0"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">',
                        '</a></h3>'
                    );
                    ?>
                    
                </header>

                <div class="entry-meta mb-3">
                    
                    <?php
                    // Post type specific meta
                    if (get_post_type() === 'post') {
                        // Author
                        echo '<span class="author-meta me-3">';
                        echo '<i class="fas fa-user me-1"></i>';
                        echo '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a>';
                        echo '</span>';
                        
                        // Categories
                        $categories = get_the_category();
                        if ($categories) {
                            echo '<span class="category-meta me-3">';
                            echo '<i class="fas fa-folder me-1"></i>';
                            echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
                            echo '</span>';
                        }
                    }
                    
                    // Date
                    echo '<span class="date-meta me-3">';
                    echo '<i class="fas fa-calendar me-1"></i>';
                    echo '<time datetime="' . esc_attr(get_the_date('c')) . '">' . esc_html(get_the_date()) . '</time>';
                    echo '</span>';
                    
                    // URL for better UX
                    echo '<span class="url-meta d-block d-md-inline text-muted small">';
                    echo '<i class="fas fa-link me-1"></i>';
                    echo esc_url(get_permalink());
                    echo '</span>';
                    ?>
                    
                </div>

                <div class="entry-summary">
                    <?php
                    // Get excerpt or content
                    $excerpt = '';
                    
                    if (has_excerpt()) {
                        $excerpt = get_the_excerpt();
                    } else {
                        $content = get_the_content();
                        $content = wp_strip_all_tags($content);
                        $content = wp_trim_words($content, $search_excerpt_length, '...');
                        $excerpt = $content;
                    }
                    
                    // Highlight search terms in excerpt
                    if ($highlight_search_terms && $search_query && $excerpt) {
                        $excerpt = preg_replace('/(' . preg_quote($search_query, '/') . ')/i', '<mark>$1</mark>', $excerpt);
                    }
                    
                    if ($excerpt) {
                        echo '<p class="search-excerpt mb-3">' . wp_kses_post($excerpt) . '</p>';
                    }
                    
                    // Show matching content context
                    if (get_theme_mod('show_search_context', true)) {
                        $content = get_the_content();
                        $content = wp_strip_all_tags($content);
                        
                        if ($search_query && stripos($content, $search_query) !== false) {
                            // Find the position of the search term
                            $pos = stripos($content, $search_query);
                            $start = max(0, $pos - 100);
                            $context = substr($content, $start, 200);
                            
                            if ($start > 0) {
                                $context = '...' . $context;
                            }
                            
                            if (strlen($content) > $start + 200) {
                                $context = $context . '...';
                            }
                            
                            // Highlight search terms
                            $context = preg_replace('/(' . preg_quote($search_query, '/') . ')/i', '<mark>$1</mark>', $context);
                            
                            echo '<div class="search-context small text-muted">';
                            echo '<strong>' . esc_html__('Eşleşen içerik:', 'pratikwp') . '</strong> ';
                            echo wp_kses_post($context);
                            echo '</div>';
                        }
                    }
                    ?>
                </div>

                <?php if (get_theme_mod('show_search_read_more', true)) : ?>
                <div class="entry-footer">
                    <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                        <?php esc_html_e('Devamını Oku', 'pratikwp'); ?>
                        <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <?php endif; ?>

            </div>

        </div>
    </div>

</article>