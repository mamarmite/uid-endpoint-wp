
            // Method 2: If UNIQUE_ID is stored in post meta (uncomment if needed)
            /*
            $posts = get_posts(array(
                'meta_key' => 'unique_r_id',
                'meta_value' => $r_id,
                'post_type' => 'any',
                'post_status' => 'publish',
                'numberposts' => 1
            ));
            $post = !empty($posts) ? $posts[0] : null;
            */

            // Method 3: If UNIQUE_ID is stored in a custom field (uncomment if needed)
            /*
            global $wpdb;
            $post_id = $wpdb->get_var($wpdb->prepare(
                "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'r_unique_id' AND meta_value = %s",
                $r_id
            ));
            $post = $post_id ? get_post($post_id) : null;
            */
