Custom Products API Plugin
A WordPress plugin for integrating with a remote WooCommerce API to manage products. This plugin allows WordPress administrators to list, create, and configure products on a remote WooCommerce site directly from the WordPress admin dashboard.

Features
•	List Products: Fetch and display products from a remote WooCommerce store in a table format (ID, Name, Price, Stock Status).
•	Create Products: Add new products to the remote WooCommerce store using an intuitive form.
•	API Configuration: Manage API credentials and endpoint URLs via a user-friendly settings page.
•	Secure Integration: Implements authentication with WooCommerce API using Consumer Key and Consumer Secret.
•	Error Handling: Displays success or error messages for API operations.

Installation
1.	Download the Plugin:
o	Clone this repository or download the ZIP file.
git clone https://github.com/yourusername/custom-products-api.git
2.	Upload to WordPress:
o	Go to your WordPress admin dashboard.
o	Navigate to Plugins > Add New > Upload Plugin.
o	Choose the downloaded ZIP file and click Install Now.
3.	Activate the Plugin:
o	After installation, activate the plugin in the Plugins menu.

Configuration
1.	Go to the WordPress admin menu and click on Custom Products API > Configuration.
2.	Enter the following details:
o	API Endpoint URL: The base URL for the remote WooCommerce API (e.g., https://stagingdeveloper.site/wp-json/wc/v3).
o	Consumer Key: Your WooCommerce API consumer key.
o	Consumer Secret: Your WooCommerce API consumer secret.
3.	Click Save Settings.

Usage
List Products
•	Navigate to Custom Products API > List Products.
•	View a table of products fetched from the remote WooCommerce store.
Create Products
•	Navigate to Custom Products API > Create Product.
•	Fill in the product details (Name, Description, Price, Stock Quantity).
•	Submit the form to create a new product in the remote WooCommerce store.
Pagination (Optional)
•	If the product list is large, add pagination to manage large datasets effectively.

Requirements
•	WordPress version: 5.0 or higher
•	PHP version: 7.4 or higher
•	WooCommerce installed on the remote site
•	Active API keys for the remote WooCommerce site

Security Features
•	Uses HTTPS for secure API communication.
•	Implements nonce verification for form submissions.
•	Sanitizes and validates all inputs to prevent malicious data submission.

Development Notes
Code Structure
•	Main Plugin File: custom-products-api.php
•	Includes:
o	class-cpa-settings.php: Handles configuration settings.
o	class-cpa-products.php: Manages product listing and creation logic.
API Requests
•	Fetch Products: Uses wp_remote_get to retrieve product data.
•	Create Products: Uses wp_remote_post to send product data.

Contributing
1.	Fork the repository.
2.	Create a new branch for your feature or bug fix:
git checkout -b feature-or-bugfix-name
3.	Commit your changes:
git commit -m "Description of changes"
4.	Push to your branch:
git push origin feature-or-bugfix-name
5.	Submit a pull request.
License
This project is licensed under the MIT License. See the LICENSE file for details.
Support
If you encounter any issues or have questions about the plugin, please open an issue in the GitHub Issues section.

