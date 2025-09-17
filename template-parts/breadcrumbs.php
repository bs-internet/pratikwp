<?php
/**
 * Template part for displaying breadcrumbs
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Don't show on front page
if (is_front_page()) {
    return;
}

// Check if breadcrumbs should be displayed
$show_breadcrumbs = get_theme_mod('show_breadcrumbs', true);
if (!$show_breadcrumbs) {
    return;
}

// Check for individual page/post setting
if (is_singular()) {
    $hide_breadcrumbs = get_post_meta(get_the_ID(), '_pratikwp_hide_breadcrumbs', true);
    if ($hide_breadcrumbs === '1') {
        return;
    }
}

// Check for plugin breadcrumbs first
if (function_exists('yoast_breadcrumb')) {
    yoast_breadcrumb('<nav class="breadcrumbs yoast-breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'pratikwp') . '">', '</nav>');
    return;
}

if (function_exists('rank_math_the_breadcrumbs')) {
    rank_math_the_breadcrumbs();
    return;
}

if (function_exists('bcn_display')) {
    echo '<nav class="breadcrumbs bcn-breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'pratikwp') . '">';
    bcn_display();
    echo '</nav>';
    return;
}

// Custom breadcrumbs
$breadcrumb_style = get_theme_mod('breadcrumb_style', 'default');
$breadcrumb_separator = get_theme_mod('breadcrumb_separator', '/');
$show_home_icon = get_theme_mod('breadcrumb_show_home_icon', true);
$show_current_page = get_theme_mod('breadcrumb_show_current', true);

$home_title = __('Ana Sayfa', 'pratikwp');
$home_url = home_url('/');

// Build breadcrumb items
$breadcrumbs = [];

// Home link
$breadcrumbs[] = [
    'title' => $home_title,
    'url' => $home_url,
    'current' => false,
    'icon' => 'fas fa-home'
];

if (is_category() || is_single()) {
    // Category hierarchy
    if (is_single()) {
        $categories = get_the_category();
        if ($categories) {
            $category = $categories[0];
            
            // Get category hierarchy
            $cat_parents = [];
            $parent_id = $category->parent;
            
            while ($parent_id) {
                $parent = get_category($parent_id);
                $cat_parents[] = $parent;
                $parent_id = $parent->parent;
            }
            
            // Reverse to show correct order
            $cat_parents = array_reverse($cat_parents);
            
            // Add parent categories
            foreach ($cat_parents as $parent_cat) {
                $breadcrumbs[] = [
                    'title' => $parent_cat->name,
                    'url' => get_category_link($parent_cat->term_id),
                    'current' => false
                ];
            }
            
            // Add current category
            $breadcrumbs[] = [
                'title' => $category->name,
                'url' => get_category_link($category->term_id),
                'current' => false
            ];
        }
    } elseif (is_category()) {
        $category = get_queried_object();
        
        // Get category hierarchy
        $cat_parents = [];
        $parent_id = $category->parent;
        
        while ($parent_id) {
            $parent = get_category($parent_id);
            $cat_parents[] = $parent;
            $parent_id = $parent->parent;
        }
        
        // Reverse to show correct order
        $cat_parents = array_reverse($cat_parents);
        
        // Add parent categories
        foreach ($cat_parents as $parent_cat) {
            $breadcrumbs[] = [
                'title' => $parent_cat->name,
                'url' => get_category_link($parent_cat->term_id),
                'current' => false
            ];
        }
    }
    
    // Add current post
    if (is_single()) {
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => get_permalink(),
            'current' => true
        ];
    } else {
        $breadcrumbs[] = [
            'title' => single_cat_title('', false),
            'url' => '',
            'current' => true
        ];
    }
    
} elseif (is_page()) {
    // Page hierarchy
    $ancestors = get_post_ancestors(get_the_ID());
    $ancestors = array_reverse($ancestors);
    
    foreach ($ancestors as $ancestor) {
        $breadcrumbs[] = [
            'title' => get_the_title($ancestor),
            'url' => get_permalink($ancestor),
            'current' => false
        ];
    }
    
    $breadcrumbs[] = [
        'title' => get_the_title(),
        'url' => get_permalink(),
        'current' => true
    ];
    
} elseif (is_tag()) {
    $breadcrumbs[] = [
        'title' => sprintf(__('Etiket: %s', 'pratikwp'), single_tag_title('', false)),
        'url' => '',
        'current' => true
    ];
    
} elseif (is_author()) {
    $breadcrumbs[] = [
        'title' => sprintf(__('Yazar: %s', 'pratikwp'), get_the_author()),
        'url' => '',
        'current' => true
    ];
    
} elseif (is_date()) {
    if (is_year()) {
        $breadcrumbs[] = [
            'title' => get_the_date('Y'),
            'url' => '',
            'current' => true
        ];
    } elseif (is_month()) {
        $breadcrumbs[] = [
            'title' => get_the_date('Y'),
            'url' => get_year_link(get_the_date('Y')),
            'current' => false
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('F'),
            'url' => '',
            'current' => true
        ];
    } elseif (is_day()) {
        $breadcrumbs[] = [
            'title' => get_the_date('Y'),
            'url' => get_year_link(get_the_date('Y')),
            'current' => false
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('F'),
            'url' => get_month_link(get_the_date('Y'), get_the_date('m')),
            'current' => false
        ];
        $breadcrumbs[] = [
            'title' => get_the_date('j'),
            'url' => '',
            'current' => true
        ];
    }
    
} elseif (is_search()) {
    $breadcrumbs[] = [
        'title' => sprintf(__('Arama: "%s"', 'pratikwp'), get_search_query()),
        'url' => '',
        'current' => true
    ];
    
} elseif (is_404()) {
    $breadcrumbs[] = [
        'title' => __('404 - Sayfa Bulunamadı', 'pratikwp'),
        'url' => '',
        'current' => true
    ];
    
} elseif (is_home() && !is_front_page()) {
    $page_for_posts = get_option('page_for_posts');
    if ($page_for_posts) {
        $breadcrumbs[] = [
            'title' => get_the_title($page_for_posts),
            'url' => get_permalink($page_for_posts),
            'current' => true
        ];
    } else {
        $breadcrumbs[] = [
            'title' => __('Blog', 'pratikwp'),
            'url' => '',
            'current' => true
        ];
    }
    
} elseif (is_post_type_archive()) {
    $post_type = get_post_type_object(get_post_type());
    $breadcrumbs[] = [
        'title' => $post_type->labels->name,
        'url' => '',
        'current' => true
    ];
}

// Custom post type single
if (is_singular() && get_post_type() !== 'post' && get_post_type() !== 'page') {
    $post_type = get_post_type_object(get_post_type());
    $archive_link = get_post_type_archive_link(get_post_type());
    
    if ($archive_link) {
        // Remove current post and add archive link
        array_pop($breadcrumbs);
        $breadcrumbs[] = [
            'title' => $post_type->labels->name,
            'url' => $archive_link,
            'current' => false
        ];
        $breadcrumbs[] = [
            'title' => get_the_title(),
            'url' => get_permalink(),
            'current' => true
        ];
    }
}

// Don't remove current page if setting is enabled
if (!$show_current_page && !empty($breadcrumbs)) {
    $last_item = end($breadcrumbs);
    if ($last_item['current']) {
        array_pop($breadcrumbs);
    }
}

if (empty($breadcrumbs) || count($breadcrumbs) <= 1) {
    return;
}

// Schema.org structured data
$schema_items = [];
foreach ($breadcrumbs as $index => $crumb) {
    $schema_items[] = [
        '@type' => 'ListItem',
        'position' => $index + 1,
        'name' => $crumb['title'],
        'item' => $crumb['url'] ? $crumb['url'] : null
    ];
}

$schema = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => $schema_items
];
?>

<nav class="breadcrumbs breadcrumb-<?php echo esc_attr($breadcrumb_style); ?>" aria-label="<?php esc_attr_e('Breadcrumb', 'pratikwp'); ?>">
    
    <!-- Schema.org structured data -->
    <script type="application/ld+json">
    <?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
    </script>
    
    <?php if ($breadcrumb_style === 'bootstrap') : ?>
    <!-- Bootstrap Style -->
    <ol class="breadcrumb mb-0">
        <?php foreach ($breadcrumbs as $index => $crumb) : ?>
        <li class="breadcrumb-item<?php echo $crumb['current'] ? ' active' : ''; ?>"<?php echo $crumb['current'] ? ' aria-current="page"' : ''; ?>>
            <?php if (!$crumb['current'] && $crumb['url']) : ?>
            <a href="<?php echo esc_url($crumb['url']); ?>" class="text-decoration-none">
                <?php if ($index === 0 && $show_home_icon && isset($crumb['icon'])) : ?>
                <i class="<?php echo esc_attr($crumb['icon']); ?> me-1"></i>
                <?php endif; ?>
                <?php echo esc_html($crumb['title']); ?>
            </a>
            <?php else : ?>
                <?php if ($index === 0 && $show_home_icon && isset($crumb['icon'])) : ?>
                <i class="<?php echo esc_attr($crumb['icon']); ?> me-1"></i>
                <?php endif; ?>
                <?php echo esc_html($crumb['title']); ?>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ol>
    
    <?php else : ?>
    <!-- Default Style -->
    <div class="breadcrumb-items d-flex flex-wrap align-items-center">
        <?php foreach ($breadcrumbs as $index => $crumb) : ?>
        
        <?php if ($index > 0) : ?>
        <span class="breadcrumb-separator mx-2 text-muted" aria-hidden="true">
            <?php
            if ($breadcrumb_separator === 'arrow') {
                echo '<i class="fas fa-chevron-right"></i>';
            } elseif ($breadcrumb_separator === 'slash') {
                echo '/';
            } else {
                echo esc_html($breadcrumb_separator);
            }
            ?>
        </span>
        <?php endif; ?>
        
        <span class="breadcrumb-item<?php echo $crumb['current'] ? ' current' : ''; ?>"<?php echo $crumb['current'] ? ' aria-current="page"' : ''; ?>>
            <?php if (!$crumb['current'] && $crumb['url']) : ?>
            <a href="<?php echo esc_url($crumb['url']); ?>" class="breadcrumb-link text-decoration-none">
                <?php if ($index === 0 && $show_home_icon && isset($crumb['icon'])) : ?>
                <i class="<?php echo esc_attr($crumb['icon']); ?> me-1"></i>
                <?php endif; ?>
                <?php echo esc_html($crumb['title']); ?>
            </a>
            <?php else : ?>
                <?php if ($index === 0 && $show_home_icon && isset($crumb['icon'])) : ?>
                <i class="<?php echo esc_attr($crumb['icon']); ?> me-1"></i>
                <?php endif; ?>
                <span class="breadcrumb-current"><?php echo esc_html($crumb['title']); ?></span>
            <?php endif; ?>
        </span>
        
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    
</nav>