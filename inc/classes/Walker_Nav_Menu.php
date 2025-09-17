<?php
/**
 * Custom Walker Nav Menu Class
 * 
 * @package PratikWp
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom Walker for Bootstrap Navigation Menus
 */
class PratikWp_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    /**
     * What the class handles
     */
    public $tree_type = ['post_type', 'taxonomy', 'custom'];
    
    /**
     * Database fields to use
     */
    public $db_fields = [
        'parent' => 'menu_item_parent',
        'id'     => 'db_id'
    ];

    /**
     * Start Level - Output opening wrapper for level
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        // Default indentation for readability
        $indent = str_repeat("\t", $depth);
        
        // Bootstrap dropdown menu classes
        $dropdown_class = 'dropdown-menu';
        
        // Add depth class for styling
        if ($depth > 0) {
            $dropdown_class .= ' dropdown-submenu';
        }
        
        // Allow filtering of dropdown classes
        $dropdown_class = apply_filters('pratikwp_dropdown_menu_class', $dropdown_class, $depth, $args);
        
        $output .= "\n$indent<ul class=\"$dropdown_class\">\n";
    }

    /**
     * End Level - Output closing wrapper for level
     */
    public function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    /**
     * Start Element - Output opening wrapper for element
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        // Item classes
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        // Bootstrap nav item class
        $nav_item_class = 'nav-item';
        
        // Check if item has children (dropdown)
        $has_children = in_array('menu-item-has-children', $classes);
        
        if ($has_children) {
            $nav_item_class .= ' dropdown';
            
            // Add dropdown class if not present
            if (!in_array('dropdown', $classes)) {
                $classes[] = 'dropdown';
            }
        }
        
        // Current page highlighting
        if (in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes)) {
            $nav_item_class .= ' active';
        }
        
        // Filter classes
        $classes = apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth);
        $class_names = join(' ', $classes);
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        
        // Item ID
        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';
        
        // Output list item
        $output .= $indent . '<li class="' . esc_attr($nav_item_class) . '"' . $id . $class_names . '>';
        
        // Link attributes
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        
        // Bootstrap nav link class
        $link_class = 'nav-link';
        
        if ($has_children) {
            $link_class .= ' dropdown-toggle';
            $attributes .= ' role="button" data-bs-toggle="dropdown" aria-expanded="false"';
        }
        
        // Add active class to current page link
        if (in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes)) {
            $link_class .= ' active';
        }
        
        $attributes .= ' class="' . esc_attr($link_class) . '"';
        
        // Build link
        $item_output = isset($args->before) ? $args->before : '';
        $item_output .= '<a' . $attributes . '>';
        $item_output .= (isset($args->link_before) ? $args->link_before : '') . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');
        
        // Add dropdown indicator for parent items
        if ($has_children && $depth === 0) {
            $item_output .= ' <span class="dropdown-indicator"></span>';
        }
        
        $item_output .= '</a>';
        $item_output .= isset($args->after) ? $args->after : '';
        
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    /**
     * End Element - Output closing wrapper for element
     */
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}

/**
 * Mobile Walker Nav Menu Class
 */
class PratikWp_Mobile_Walker_Nav_Menu extends Walker_Nav_Menu {
    
    /**
     * Start Level
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"mobile-submenu\" style=\"display: none;\">\n";
    }

    /**
     * End Level
     */
    public function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    /**
     * Start Element
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $has_children = in_array('menu-item-has-children', $classes);
        
        $li_class = 'mobile-menu-item';
        if ($has_children) {
            $li_class .= ' has-submenu';
        }
        
        if (in_array('current-menu-item', $classes) || in_array('current-menu-ancestor', $classes)) {
            $li_class .= ' active';
        }
        
        $output .= $indent . '<li class="' . esc_attr($li_class) . '">';
        
        // Link attributes
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        $attributes .= ' class="mobile-menu-link"';
        
        $item_output = '<a' . $attributes . '>';
        $item_output .= apply_filters('the_title', $item->title, $item->ID);
        $item_output .= '</a>';
        
        // Add toggle button for parent items
        if ($has_children) {
            $item_output .= '<button class="mobile-submenu-toggle" type="button" aria-label="' . esc_attr__('Alt menüyü aç/kapat', 'pratikwp') . '"><span class="toggle-icon">+</span></button>';
        }
        
        $output .= $item_output;
    }

    /**
     * End Element
     */
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= "</li>\n";
    }
}

/**
 * Mega Menu Walker
 */
class PratikWp_Mega_Menu_Walker extends Walker_Nav_Menu {
    
    /**
     * Start Level
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        
        if ($depth === 0) {
            // Mega menu container
            $output .= "\n$indent<div class=\"mega-menu dropdown-menu\">\n";
            $output .= "$indent\t<div class=\"container\">\n";
            $output .= "$indent\t\t<div class=\"row\">\n";
        } else {
            // Sub-submenu
            $output .= "\n$indent<ul class=\"mega-submenu\">\n";
        }
    }

    /**
     * End Level
     */
    public function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        
        if ($depth === 0) {
            $output .= "$indent\t\t</div>\n";
            $output .= "$indent\t</div>\n";
            $output .= "$indent</div>\n";
        } else {
            $output .= "$indent</ul>\n";
        }
    }

    /**
     * Start Element
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        
        $classes = empty($item->classes) ? [] : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        
        $has_children = in_array('menu-item-has-children', $classes);
        
        if ($depth === 0) {
            // Top level item
            $nav_item_class = 'nav-item';
            if ($has_children) {
                $nav_item_class .= ' dropdown position-static';
            }
            
            $output .= $indent . '<li class="' . esc_attr($nav_item_class) . '">';
            
            $link_class = 'nav-link';
            if ($has_children) {
                $link_class .= ' dropdown-toggle';
            }
            
            $attributes = ' class="' . esc_attr($link_class) . '"';
            $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
            
            if ($has_children) {
                $attributes .= ' role="button" data-bs-toggle="dropdown" aria-expanded="false"';
            }
            
            $item_output = '<a' . $attributes . '>';
            $item_output .= apply_filters('the_title', $item->title, $item->ID);
            $item_output .= '</a>';
            
            $output .= $item_output;
            
        } elseif ($depth === 1) {
            // Mega menu column
            $column_class = get_post_meta($item->ID, '_menu_item_mega_column_class', true);
            if (!$column_class) {
                $column_class = 'col-md-3';
            }
            
            $output .= $indent . '<div class="' . esc_attr($column_class) . ' mega-menu-column">';
            
            if (!empty($item->title)) {
                $output .= '<h6 class="mega-menu-title">';
                $output .= apply_filters('the_title', $item->title, $item->ID);
                $output .= '</h6>';
            }
            
            if ($has_children) {
                $output .= '<ul class="mega-menu-list">';
            }
            
        } else {
            // Mega menu items
            $output .= $indent . '<li class="mega-menu-item">';
            
            $attributes = !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
            $attributes .= ' class="mega-menu-link"';
            
            $item_output = '<a' . $attributes . '>';
            $item_output .= apply_filters('the_title', $item->title, $item->ID);
            $item_output .= '</a>';
            
            $output .= $item_output;
        }
    }

    /**
     * End Element
     */
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        if ($depth === 0) {
            $output .= "</li>\n";
        } elseif ($depth === 1) {
            $classes = empty($item->classes) ? [] : (array) $item->classes;
            $has_children = in_array('menu-item-has-children', $classes);
            
            if ($has_children) {
                $output .= '</ul>';
            }
            $output .= "</div>\n";
        } else {
            $output .= "</li>\n";
        }
    }
}

/**
 * Breadcrumb Walker
 */
class PratikWp_Breadcrumb_Walker {
    
    /**
     * Generate breadcrumb items
     */
    public static function get_breadcrumb_items() {
        $items = [];
        
        // Home
        $items[] = [
            'title' => __('Ana Sayfa', 'pratikwp'),
            'url' => home_url('/'),
            'current' => false
        ];
        
        if (is_category() || is_single()) {
            $categories = get_the_category();
            if ($categories) {
                $category = $categories[0];
                $items[] = [
                    'title' => $category->name,
                    'url' => get_category_link($category->term_id),
                    'current' => is_category()
                ];
            }
            
            if (is_single()) {
                $items[] = [
                    'title' => get_the_title(),
                    'url' => get_permalink(),
                    'current' => true
                ];
            }
        } elseif (is_page()) {
            $ancestors = get_post_ancestors(get_the_ID());
            $ancestors = array_reverse($ancestors);
            
            foreach ($ancestors as $ancestor) {
                $items[] = [
                    'title' => get_the_title($ancestor),
                    'url' => get_permalink($ancestor),
                    'current' => false
                ];
            }
            
            $items[] = [
                'title' => get_the_title(),
                'url' => get_permalink(),
                'current' => true
            ];
        } elseif (is_archive()) {
            $items[] = [
                'title' => get_the_archive_title(),
                'url' => '',
                'current' => true
            ];
        } elseif (is_search()) {
            $items[] = [
                'title' => sprintf(__('Arama: %s', 'pratikwp'), get_search_query()),
                'url' => '',
                'current' => true
            ];
        } elseif (is_404()) {
            $items[] = [
                'title' => __('404 - Sayfa Bulunamadı', 'pratikwp'),
                'url' => '',
                'current' => true
            ];
        }
        
        return apply_filters('pratikwp_breadcrumb_items', $items);
    }
    
    /**
     * Render breadcrumbs
     */
    public static function render_breadcrumbs($args = []) {
        $defaults = [
            'separator' => ' / ',
            'container_class' => 'breadcrumbs',
            'list_class' => 'breadcrumb',
            'item_class' => 'breadcrumb-item',
            'active_class' => 'active',
            'show_home' => true
        ];
        
        $args = wp_parse_args($args, $defaults);
        $items = self::get_breadcrumb_items();
        
        if (empty($items) || (count($items) <= 1 && !$args['show_home'])) {
            return '';
        }
        
        $output = '<nav class="' . esc_attr($args['container_class']) . '" aria-label="' . esc_attr__('Breadcrumb', 'pratikwp') . '">';
        $output .= '<ol class="' . esc_attr($args['list_class']) . '">';
        
        foreach ($items as $item) {
            $class = $args['item_class'];
            if ($item['current']) {
                $class .= ' ' . $args['active_class'];
            }
            
            $output .= '<li class="' . esc_attr($class) . '"';
            if ($item['current']) {
                $output .= ' aria-current="page"';
            }
            $output .= '>';
            
            if (!$item['current'] && !empty($item['url'])) {
                $output .= '<a href="' . esc_url($item['url']) . '">' . esc_html($item['title']) . '</a>';
            } else {
                $output .= esc_html($item['title']);
            }
            
            $output .= '</li>';
        }
        
        $output .= '</ol>';
        $output .= '</nav>';
        
        return $output;
    }
}

/**
 * Footer Menu Walker (Simple)
 */
class PratikWp_Footer_Walker extends Walker_Nav_Menu {
    
    /**
     * Start Level
     */
    public function start_lvl(&$output, $depth = 0, $args = null) {
        // Footer menus typically don't have submenus
        return;
    }

    /**
     * End Level
     */
    public function end_lvl(&$output, $depth = 0, $args = null) {
        return;
    }

    /**
     * Start Element
     */
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $attributes = !empty($item->attr_title) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target) ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url) ? ' href="' . esc_attr($item->url) . '"' : '';
        $attributes .= ' class="footer-menu-link"';
        
        $item_output = '<a' . $attributes . '>';
        $item_output .= apply_filters('the_title', $item->title, $item->ID);
        $item_output .= '</a>';
        
        $output .= $item_output;
    }

    /**
     * End Element
     */
    public function end_el(&$output, $item, $depth = 0, $args = null) {
        // Add separator except for last item
        $output .= '<span class="footer-menu-separator"> | </span>';
    }
}