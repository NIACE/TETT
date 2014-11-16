<?php
/*
Plugin Name: NIACE Resources Manager
Plugin URI: www.sitewriters.co.uk
Description: NIACE Resources Manager
Version: 0.1
Author: Adam Sargant
Author URI: www.sitewriters.co.uk
License: GPL2
Copyright 2014  Adam Sargant  (email : adam@sargant.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*Options used
 * swniace_resources_manager_TaxonomyGroupNames
 * swniace_resources_manager_category_meta
 * swniace_resources_manager_settings
 */

DEFINE("WPRESOURCESMANAGERURL",plugins_url('' , __FILE__));
DEFINE("WPRESOURCESMANAGERPATH",plugin_dir_path(__FILE__));
DEFINE("WPRESOURCESMANAGERMAINFILEPATH",__FILE__);

require_once( dirname(__FILE__) . '/includes/installation/install.php' );

wp_enqueue_style( 'niace_resource_styles', plugins_url('/css/niace_resource_styles.css', __FILE__) );
require_once( dirname(__FILE__) . '/includes/swniace-resources-manager-functions.php' );       
require_once( dirname(__FILE__) . '/includes/swniace-resources-manager-init.php' );
require_once( dirname(__FILE__) . '/includes/swniace-resources-manager-post-types.php' );
require_once( dirname(__FILE__) . '/includes/swniace-resources-manager-taxonomies.php' );
require_once( dirname(__FILE__) . '/includes/swniace-resources-manager-categoriesform.php' );
require_once( dirname(__FILE__) . '/includes/swniace-resources-manager-settings.php' );
require_once( dirname(__FILE__) . '/includes/swniace-resources-manager-taxonomies-custom-column.php' );
require_once( dirname(__FILE__) . '/includes/swniace-resources-manager-ajaxfunctions.php' );
require_once( dirname(__FILE__) . '/includes/swniace-resources-manager-search-form.php' );
require_once( dirname(__FILE__) . '/includes/swniace-resources-manager-resource-rating.php' );
require_once( dirname(__FILE__) . '/includes/swniace-resources-manager-resource-page.php' );
require_once( dirname(__FILE__) . '/includes/swniace-resources-manager-resource-archive.php' );
require_once( dirname(__FILE__) . '/includes/widgets/swniace_resources_manager_curriculum_tagcloud_widget.php' );
require_once( dirname(__FILE__) . '/includes/widgets/swniace_resources_manager_target_tagcloud_widget.php' );
require_once( dirname(__FILE__) . '/includes/widgets/swniace_resources_manager_personalised_tagcloud_widget.php' );
if(!is_admin()){
    require_once( dirname(__FILE__) . '/includes/swniace-resources-manager-submission-form.php' );
}
else{
    require_once( dirname(__FILE__) . '/includes/metaboxes/swniace-resources-manager-resource-metadata.php' );
}
?>
